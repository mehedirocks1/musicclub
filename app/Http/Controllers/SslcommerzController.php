<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Raziul\Sslcommerz\Facades\Sslcommerz;
use App\Models\MemberPayment;
use Modules\Members\Models\Member;
use DevWizard\Textify\Facades\Textify;   // ADD ONLY

class SslcommerzController extends Controller
{
    /**
     * SSLCommerz checkout-এ রিডাইরেক্ট
     * Expect: tran_id (member_id লাগবে না)
     */
    public function init(Request $request)
    {
        $request->validate([
            'tran_id' => ['required', 'string', 'max:100'],
        ]);

        $payment = MemberPayment::where('tran_id', $request->tran_id)->firstOrFail();

        if ($payment->status !== 'pending') {
            return redirect()->route('home')
                ->withErrors(['payment' => 'This transaction is already processed ('.$payment->status.').']);
        }

        // Snapshot থেকেই কাস্টমার ইনফো
        $name  = $payment->full_name ?: 'Member';
        $email = $payment->email     ?: 'guest@example.com';
        $phone = $payment->phone     ?: '01700000000';
        $addr  = $payment->address   ?: 'Dhaka';

        // Callback URLs প্যাকেজ config/sslcommerz.php থেকেই নেয়
        $resp = Sslcommerz::setOrder($payment->amount, $payment->tran_id, 'POJ Membership')
            ->setCustomer($name, $email, $phone)
            ->setShippingInfo(1, $addr)
            ->makePayment();

        if ($resp->success()) {
            return redirect($resp->gatewayPageURL());
        }

        Log::warning('SSL INIT FAILED', [
            'tran_id' => $payment->tran_id,
            'resp'    => method_exists($resp, 'toArray') ? $resp->toArray() : (array) $resp,
        ]);

        return redirect()->route('home')->withErrors(['payment' => 'Unable to initialize payment.']);
    }

    /** Browser redirects */
    public function success(Request $request) { return $this->finalize($request, 'success'); }

    public function failure(Request $request)
    {
        $this->mark($request->input('tran_id'), 'failed');
        return redirect()->route('home')->with('error', 'Payment Failed');
    }

    public function cancel(Request $request)
    {
        $this->mark($request->input('tran_id'), 'cancelled');
        return redirect()->route('home')->with('warning', 'Payment Cancelled');
    }

    /** Server-to-server */
    public function ipn(Request $request) { return $this->finalize($request, 'ipn'); }

    /**
     * Success/IPN—ভ্যালিডেশন + idempotent ফাইনালাইজেশন
     * - পেমেন্ট OK হলে Member তৈরি (না থাকলে)
     * - payment.status = paid
     * - member.balance += amount
     * - ✅ Welcome SMS পাঠানো (Member ID + Amount)
     */
    protected function finalize(Request $request, string $source)
    {
        $tranId = $request->input('tran_id');
        if (!$tranId) return $this->finalizeResponse($source, false, 'Invalid callback');

        $payment = MemberPayment::where('tran_id', $tranId)->first();
        if (!$payment) return $this->finalizeResponse($source, false, 'Unknown transaction');

        if ($payment->status === 'paid') {
            return $this->finalizeResponse($source, true, 'Already processed');
        }

        // প্যাকেজ দিয়ে ভেরিফাই
        $isValid = Sslcommerz::validatePayment($request->all(), $tranId, $payment->amount);

        // (ঐচ্ছিক) Currency guard
        $reqCurrency   = $request->input('currency');
        $storeCurrency = config('sslcommerz.store.currency', 'BDT');
        if ($isValid && $reqCurrency && strtoupper($reqCurrency) !== strtoupper($storeCurrency)) {
            $isValid = false;
            Log::warning('SSL finalize: currency mismatch', compact('tranId','reqCurrency','storeCurrency'));
        }

        if (!$isValid) {
            $payment->update(['status' => 'validation_failed', 'gateway_payload' => $request->all()]);
            return $this->finalizeResponse($source, false, 'Validation failed');
        }

        // ---- SUCCESS PATH ----
        try {
            // ADD ONLY: ট্রানজ্যাকশনের বাইরে SMS পাঠাতে প্রয়োজনীয় ডাটা ক্যাপচার ভেরিয়েবল
            $smsCtx = ['phone' => null, 'member_id' => null, 'amount' => null];

            DB::transaction(function () use ($payment, $request, &$smsCtx) {
                // 1) Member না থাকলে এখনই তৈরি করব
                if (empty($payment->member_id)) {
                    // username unique করতে হবে
                    $username = $payment->username ?: ('user'.Str::lower(Str::random(6)));
                    $base     = $username; $i = 1;
                    while (Member::where('username', $username)->exists()) {
                        $username = $base.'-'.$i++;
                    }

                    $member = Member::create([
                        'profile_pic'             => $payment->profile_pic,
                        'member_id'               => 'M'.date('ymd').Str::upper(Str::random(4)),
                        'username'                => $username,
                        'name_bn'                 => $payment->name_bn,
                        'full_name'               => $payment->full_name ?: 'Member',
                        'email'                   => $payment->email,
                        'phone'                   => $payment->phone,
                        'password'                => Str::password(12), // temp password (SMS-এ দিচ্ছি না)
                        'dob'                     => $payment->dob,
                        'id_number'               => $payment->id_number,
                        'gender'                  => $payment->gender,
                        'blood_group'             => $payment->blood_group,
                        'education_qualification' => $payment->education_qualification,
                        'profession'              => $payment->profession,
                        'other_expertise'         => $payment->other_expertise,
                        'country'                 => $payment->country ?: 'Bangladesh',
                        'division'                => $payment->division,
                        'district'                => $payment->district,
                        'address'                 => $payment->address,
                        'membership_type'         => $payment->membership_type ?: 'Student',
                        'registration_date'       => now(),
                        'balance'                 => 0,
                    ]);

                    $payment->member_id = $member->id;

                    // ADD ONLY: SMS কনটেক্সট ক্যাপচার
                    $smsCtx['phone']     = $member->phone;
                    $smsCtx['member_id'] = $member->member_id;
                } else {
                    $existing = Member::find($payment->member_id);
                    $smsCtx['phone']     = $existing?->phone;
                    $smsCtx['member_id'] = $existing?->member_id;
                }

                // 2) Payment update
                $payment->status          = 'paid';
                $payment->bank_tran_id    = $request->input('bank_tran_id');
                $payment->val_id          = $request->input('val_id');
                $payment->card_type       = $request->input('card_type');
                $payment->gateway_payload = $request->all();
                $payment->save();

                // 3) Member balance += amount
                Member::where('id', $payment->member_id)
                    ->update(['balance' => DB::raw('balance + '.$payment->amount)]);

                // ADD ONLY: Amount ক্যাপচার
                $smsCtx['amount'] = $payment->amount;
            });

            // ✅ ট্রানজ্যাকশন সফল — এখন Welcome SMS পাঠান (Member ID + Amount)
            if (!empty($smsCtx['phone']) && !empty($smsCtx['member_id']) && !empty($smsCtx['amount'])) {
                $to  = $this->normalizeBdMsisdn($smsCtx['phone']);
                $amt = number_format((float) $smsCtx['amount'], 2);

                $msg = "Welcome to POJ Music Club\n"
                     . "You paid BDT {$amt}\n"
                     . "Member ID: {$smsCtx['member_id']}";

                // Textify ডিফল্ট ড্রাইভার bulksmsbd ধরে নিলাম
                Textify::to($to)->message($msg)->via('bulksmsbd')->send();
                // ভলিউম বেশি হলে: ->queue();
            }

            return $this->finalizeResponse($source, true, 'Payment Success');

        } catch (\Throwable $e) {
            Log::error('SSL finalize: DB error', ['tran_id' => $tranId, 'error' => $e->getMessage()]);
            return $this->finalizeResponse($source, false, 'Server error');
        }
    }

    /** Helper: paid না হলে স্ট্যাটাস সেট */
    protected function mark(?string $tranId, string $status): void
    {
        if (!$tranId) return;
        $payment = MemberPayment::where('tran_id', $tranId)->first();
        if ($payment && $payment->status !== 'paid') {
            $payment->update(['status' => $status]);
        }
    }

    /** Unified response */
    protected function finalizeResponse(string $source, bool $ok, string $message)
    {
        if ($source === 'ipn') {
            return response($ok ? 'IPN OK' : $message, $ok ? 200 : 422);
        }
        // সব ক্ষেত্রেই হোমে ফেরত
        return redirect()->route('home')->with($ok ? 'success' : 'error', $message);
    }

    /**
     * ADD ONLY: Bangladesh নম্বর নরমালাইজ
     * 017xxxxxxxx, +88017xxxxxxxx, 88017xxxxxxxx → 017xxxxxxxx
     */
    private function normalizeBdMsisdn(?string $phone): string
    {
        $p = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($p, '8801')) return '0' . substr($p, 3);
        if (str_starts_with($p, '01'))  return $p;
        if (str_starts_with($p, '1') && strlen($p) === 10) return '0' . $p;
        return $p;
    }
}

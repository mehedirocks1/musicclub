<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Raziul\Sslcommerz\Facades\Sslcommerz;
use App\Models\MemberPayment;
use Modules\Members\Models\Member;
use Modules\Packages\App\Models\Package;
use DevWizard\Textify\Facades\Textify;

class SslcommerzController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'package_id' => ['required','integer','exists:packages,id'],
            'plan' => ['required','in:monthly,yearly'],
            'full_name' => ['required','string','max:255'],
            'name_bn' => ['nullable','string','max:255'],
            'username' => ['required','string','max:50'],
            'email' => ['required','email'],
            'phone' => ['required','string','max:30'],
            'profile_pic' => ['nullable','string','max:255'],
            'password' => ['required','string','min:8','confirmed'],
            'dob' => ['nullable','date'],
            'id_number' => ['nullable','string','max:100'],
            'gender' => ['nullable','in:male,female,other'],
            'blood_group' => ['nullable','string','max:5'],
            'education_qualification' => ['nullable','string','max:255'],
            'profession' => ['nullable','string','max:255'],
            'other_expertise' => ['nullable','string'],
            'country' => ['required','string','max:100'],
            'division' => ['required','string','max:100'],
            'district' => ['required','string','max:100'],
            'address' => ['required','string'],
            'membership_type' => ['required','string','max:50'],
        ]);

        $package = Package::findOrFail($data['package_id']);
        $tranId = 'POJ'.now()->format('ymd').Str::upper(Str::random(6));

        $registration = $data;
        $registration['password'] = Hash::make($data['password']);
        session()->put("reg:$tranId", $registration);

        MemberPayment::create([
            'member_id' => null,
            'tran_id' => $tranId,
            'plan' => $data['plan'],
            'amount' => $package->price,
            'currency' => 'BDT',
            'status' => 'pending',
            'package_id' => $package->id,
            'package_name' => $package->name,
            'full_name' => $data['full_name'] ?? null,
            'name_bn' => $data['name_bn'] ?? null,
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
            'id_number' => $data['id_number'] ?? null,
            'education_qualification' => $data['education_qualification'] ?? null,
            'profession' => $data['profession'] ?? null,
            'other_expertise' => $data['other_expertise'] ?? null,
            'country' => $data['country'] ?? null,
            'division' => $data['division'] ?? null,
            'district' => $data['district'] ?? null,
            'address' => $data['address'] ?? null,
            'membership_type' => $data['membership_type'] ?? null,
            'profile_pic' => $data['profile_pic'] ?? null,
            'gateway_payload' => null,
        ]);

        return redirect()->route('ssl.init', ['tran_id' => $tranId]);
    }

    public function init(Request $request)
    {
        $request->validate([
            'tran_id' => ['required', 'string', 'max:100'],
        ]);

        $payment = MemberPayment::where('tran_id', $request->tran_id)->firstOrFail();

        if ($payment->status !== 'pending') {
            return redirect()->route('home')->withErrors(['payment' => 'This transaction is already processed ('.$payment->status.').']);
        }

        $name = $payment->full_name ?: 'Member';
        $email = $payment->email ?: 'guest@example.com';
        $phone = $payment->phone ?: '01700000000';
        $addr = $payment->address ?: 'Dhaka';

        $resp = Sslcommerz::setOrder($payment->amount, $payment->tran_id, 'POJ Membership')
            ->setCustomer($name, $email, $phone)
            ->setShippingInfo(1, $addr)
            ->makePayment();

        if ($resp->success()) {
            return redirect($resp->gatewayPageURL());
        }

        Log::warning('SSL INIT FAILED', [
            'tran_id' => $payment->tran_id,
            'resp' => method_exists($resp, 'toArray') ? $resp->toArray() : (array) $resp,
        ]);

        return redirect()->route('home')->withErrors(['payment' => 'Unable to initialize payment.']);
    }

    public function success(Request $request)
    {
        return $this->finalize($request, 'success');
    }

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

    public function ipn(Request $request)
    {
        return $this->finalize($request, 'ipn');
    }

protected function finalize(Request $request, string $source)
{
    $tranId = $request->input('tran_id');
    if (!$tranId) return $this->finalizeResponse($source, false, 'Invalid callback');

    $payment = MemberPayment::where('tran_id', $tranId)->first();
    if (!$payment) return $this->finalizeResponse($source, false, 'Unknown transaction');
    if ($payment->status === 'paid') return $this->finalizeResponse($source, true, 'Already processed');

    // Validate payment
    $isValid = Sslcommerz::validatePayment($request->all(), $tranId, $payment->amount);

    $reqCurrency = $request->input('currency');
    $storeCurrency = config('sslcommerz.store.currency', 'BDT');
    if ($isValid && $reqCurrency && strtoupper($reqCurrency) !== strtoupper($storeCurrency)) {
        $isValid = false;
        Log::warning('SSL finalize: currency mismatch', compact('tranId','reqCurrency','storeCurrency'));
    }

    if (!$isValid) {
        $payment->update(['status' => 'validation_failed', 'gateway_payload' => $request->all()]);
        return $this->finalizeResponse($source, false, 'Validation failed');
    }

    try {
        $smsCtx = ['phone' => null, 'member_id' => null, 'subscriber_id' => null, 'amount' => null];

        DB::transaction(function () use ($payment, $request, &$smsCtx) {

            // --- Determine if this is a Subscriber package purchase ---
            if ($payment->package_id) {

                $subscriber = \Modules\Subscribers\Models\Subscriber::updateOrCreate(
                    ['email' => $payment->email ?: $payment->phone],
                    [
                        'full_name' => $payment->full_name,
                        'username' => $payment->username,
                        'phone' => $payment->phone,
                        'profile_pic' => $payment->profile_pic,
                        'dob' => $payment->dob,
                        'gender' => $payment->gender,
                        'blood_group' => $payment->blood_group,
                        'id_number' => $payment->id_number,
                        'education' => $payment->education_qualification,
                        'profession' => $payment->profession,
                        'other_expertise' => $payment->other_expertise,
                        'country' => $payment->country ?: 'Bangladesh',
                        'division' => $payment->division,
                        'district' => $payment->district,
                        'address' => $payment->address,
                        'status' => 'active',
                        'package_title' => $payment->package_name,
                        'fee_type' => $payment->plan,
                        'fee_amount' => $payment->amount,
                        'last_payment_amount' => $payment->amount,
                        'last_payment_tran_id' => $payment->tran_id,
                        'last_payment_at' => now(),
                        'last_payment_gateway' => 'sslcommerz',
                    ]
                );

                // Update payment table: member_id stays null
                $payment->update([
                    'status' => 'paid',
                    'member_id' => null,
                    'gateway_payload' => $request->all(),
                    'bank_tran_id' => $request->input('bank_tran_id'),
                    'val_id' => $request->input('val_id'),
                    'card_type' => $request->input('card_type'),
                ]);

                $smsCtx['phone'] = $subscriber->phone;
                $smsCtx['subscriber_id'] = $subscriber->subscriber_id;
                $smsCtx['amount'] = $payment->amount;

            } else {
                // --- Membership payment (Member) ---
                $member = Member::create([
                    'profile_pic' => $payment->profile_pic,
                    'member_id' => 'M'.date('ymd').Str::upper(Str::random(4)),
                    'username' => $payment->username,
                    'name_bn' => $payment->name_bn,
                    'full_name' => $payment->full_name ?: 'Member',
                    'email' => $payment->email,
                    'phone' => $payment->phone,
                    'password' => Hash::make(Str::password(12)),
                    'dob' => $payment->dob,
                    'id_number' => $payment->id_number,
                    'gender' => $payment->gender,
                    'blood_group' => $payment->blood_group,
                    'education_qualification' => $payment->education_qualification,
                    'profession' => $payment->profession,
                    'other_expertise' => $payment->other_expertise,
                    'country' => $payment->country ?: 'Bangladesh',
                    'division' => $payment->division,
                    'district' => $payment->district,
                    'address' => $payment->address,
                    'membership_type' => $payment->membership_type ?: 'Student',
                    'registration_date' => now(),
                    'balance' => $payment->amount,
                    'membership_plan' => $payment->plan,
                    'membership_status' => 'active',
                    'membership_started_at' => now(),
                    'membership_expires_at' => now()->addMonth(), // adjust if monthly/yearly
                    'last_payment_amount' => $payment->amount,
                    'last_payment_tran_id' => $payment->tran_id,
                    'last_payment_at' => now(),
                    'last_payment_gateway' => 'sslcommerz',
                ]);

                $payment->update([
                    'status' => 'paid',
                    'member_id' => $member->id,
                    'gateway_payload' => $request->all(),
                    'bank_tran_id' => $request->input('bank_tran_id'),
                    'val_id' => $request->input('val_id'),
                    'card_type' => $request->input('card_type'),
                ]);

                $smsCtx['phone'] = $member->phone;
                $smsCtx['member_id'] = $member->member_id;
                $smsCtx['amount'] = $payment->amount;
            }

        });

        // Send SMS
        if (!empty($smsCtx['phone']) && !empty($smsCtx['amount'])) {
            $to = $this->normalizeBdMsisdn($smsCtx['phone']);
            $amt = number_format((float)$smsCtx['amount'], 2);

            $msg = "Welcome to POJ Music Club\nYou paid BDT {$amt}";
            if (!empty($smsCtx['member_id'])) {
                $msg .= "\nMember ID: {$smsCtx['member_id']}";
            } elseif (!empty($smsCtx['subscriber_id'])) {
                $msg .= "\nSubscriber ID: {$smsCtx['subscriber_id']}";
            }

            Textify::to($to)->message($msg)->via('bulksmsbd')->send();
        }

        return $this->finalizeResponse($source, true, 'Payment Success');

    } catch (\Throwable $e) {
        Log::error('SSL finalize: DB error', ['tran_id' => $tranId, 'error' => $e->getMessage()]);
        return $this->finalizeResponse($source, false, 'Server error');
    }
}


    protected function mark(?string $tranId, string $status): void
    {
        if (!$tranId) return;
        $payment = MemberPayment::where('tran_id', $tranId)->first();
        if ($payment && $payment->status !== 'paid') {
            $payment->update(['status' => $status]);
        }
    }

    protected function finalizeResponse(string $source, bool $ok, string $message)
    {
        if ($source === 'ipn') {
            return response($ok ? 'IPN OK' : $message, $ok ? 200 : 422);
        }
        return redirect()->route('home')->with($ok ? 'success' : 'error', $message);
    }

    private function normalizeBdMsisdn(?string $phone): string
    {
        $p = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($p, '8801')) return '0' . substr($p, 3);
        if (str_starts_with($p, '01')) return $p;
        if (str_starts_with($p, '1') && strlen($p) === 10) return '0' . $p;
        return $p;
    }
}

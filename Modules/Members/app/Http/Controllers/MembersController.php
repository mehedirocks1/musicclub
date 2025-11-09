<?php

namespace Modules\Members\Http\Controllers;

use App\Models\Payments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Members\Models\Member;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class MembersController extends Controller
{
    use AuthorizesRequests;

    /**
     * Dashboard page
     */
    public function dashboard()
    {
        $member = Auth::guard('member')->user();
        return view('member.dashboard', compact('member'));
    }

    /**
     * Show member profile
     */
    public function profile()
    {
        $member = Auth::guard('member')->user();
        return view('member.profile', compact('member'));
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $member = Auth::guard('member')->user();

        $data = $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'nullable|string|max:50',
            'father_name'      => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'dob'              => 'nullable|date',
            'id_number'        => 'nullable|string|max:50',
            'gender'           => 'nullable|string|max:10',
            'blood_group'      => 'nullable|string|max:5',
            'profession'       => 'nullable|string|max:255',
            'other_expertise'  => 'nullable|string|max:255',
            'country'          => 'nullable|string|max:255',
            'division'         => 'nullable|string|max:255',
            'district'         => 'nullable|string|max:255',
            'address'          => 'nullable|string|max:500',
            'profile_pic'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profile_pics', $filename, 'public');
            $data['profile_pic'] = $filePath;
        }

        $member->update($data);

        return redirect()->route('member.profile')->with('success', 'Profile updated successfully');
    }

    /**
     * Show change password page
     */
    public function changePassword()
    {
        return view('member.change-password');
    }

    /**
     * Update member password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $member = Auth::guard('member')->user();

        if (!Hash::check($request->current_password, $member->password)) {
            return back()->with('error', 'Current password does not match');
        }

        $member->password = Hash::make($request->password);
        $member->save();

        return back()->with('success', 'Password updated successfully');
    }

    /**
     * Pay Fee page
     */
    public function payFee()
    {
        $member = Auth::guard('member')->user();
        return view('member.pay-fee', compact('member'));
    }

    /**
     * Store payment and initialize SSLCommerz
     */
    public function payFeeStore(Request $request)
    {
        $member = Auth::guard('member')->user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'tran_id' => 'required|string|unique:payments,tran_id',
        ]);

        $amount = $request->input('amount');

        $payment = Payments::create([
            'member_id' => $member->id,
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => 'pending',
            'gateway' => 'SSLCOMMERZ',
            'tran_id' => $request->tran_id,
        ]);

        $resp = Sslcommerz::setOrder($payment->amount, $payment->tran_id, 'POJ Membership Fee')
            ->setCustomer($member->full_name, $member->email, $member->phone)
            ->setShippingInfo(1, $member->address)
            ->makePayment();

        if ($resp->success()) {
            return redirect($resp->gatewayPageURL());
        }

        $payment->delete();
        return redirect()->back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    /**
     * SSLCommerz payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');

        $payment = Payments::where('tran_id', $tranId)->firstOrFail();
        $member = Member::findOrFail($payment->member_id);

        if ($payment->status === 'completed') {
            return redirect()->route('member.dashboard')->with('info', 'Payment was already processed.');
        }

        $payment->status = 'completed';
        $payment->transaction_id = $request->input('bank_tran_id') ?? $request->input('transaction_id');
        $payment->gateway_payload = json_encode($request->all());
        $payment->save();

        $member->balance += $payment->amount;
        $member->save();

        return redirect()->route('member.dashboard')->with('success', 'Payment completed successfully.');
    }

    /**
     * SSLCommerz payment failure/cancel callback
     */
    public function paymentFailed(Request $request)
    {
        $tranId = $request->input('tran_id');
        $payment = Payments::where('tran_id', $tranId)->first();

        if ($payment && $payment->status !== 'completed') {
            $payment->status = 'failed';
            $payment->gateway_payload = json_encode($request->all());
            $payment->save();
        }

        return redirect()->route('member.dashboard')->with('error', 'Payment failed or cancelled.');
    }

    /**
     * Member's own payment history (paginated)
     */
    public function checkPayments()
    {
        $member = Auth::guard('member')->user();

        $payments = Payments::where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.check-payments', compact('member', 'payments'));
    }

    /**
     * All payments (admin/member view)
     */
    public function paymentHistory()
    {
        $payments = Payments::with('member')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('member.payment-history', compact('payments'));
    }

    /**
     * Optional: fallback index
     */
    public function index()
    {
        return redirect()->route('member.dashboard');
    }

    /**
     * Download member ID card as PDF
     */
    public function memberCard($id)
    {
        $member = Member::findOrFail($id);
        $data = ['allData' => $member];

        $pdf = Pdf::loadView('backend.customer.member-card', $data)
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
            ])
            ->setPaper('a4', 'portrait');

        $filename = 'member-card-' . ($member->member_id ?? $member->id) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    /**
     * Export all members to Excel
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        $members = Member::query()->orderBy('id')->get();

        $export = new class($members) implements FromCollection, WithHeadings {
            protected $members;
            public function __construct($members) { $this->members = $members; }

            public function collection()
            {
                return $this->members->map(function ($m) {
                    return [
                        $m->member_id,
                        $m->username,
                        $m->full_name,
                        $m->name_bn,
                        $m->email,
                        $m->phone,
                        $m->gender,
                        $m->blood_group,
                        $m->membership_type,
                        $m->membership_plan,
                        $m->membership_status,
                        $m->status,
                        number_format((float)$m->balance, 2),
                        optional($m->registration_date)->format('Y-m-d'),
                        $m->district,
                        $m->division,
                        $m->country,
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'Member ID', 'Username', 'Full Name', 'Name (BN)', 'Email', 'Phone',
                    'Gender', 'Blood Group', 'Membership Type', 'Membership Plan',
                    'Membership Status', 'Account Status', 'Balance (BDT)',
                    'Registration Date', 'District', 'Division', 'Country',
                ];
            }
        };

        $fileName = 'members-' . now()->format('Ymd-His') . '-' . Str::random(6) . '.xlsx';

        return Excel::download($export, $fileName);
    }
}

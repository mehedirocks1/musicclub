<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\MemberPayment;
use Modules\Members\Models\Member;
use DevWizard\Textify\Facades\Textify;   // ADD ONLY

class RegistrationController extends Controller
{
    public function create()
    {
        return view('Frontend.register');
    }

    public function store(Request $request)
    {
        // 1) Validate (password optional; Member পরে success/IPN-এ তৈরি হবে)
        $data = $request->validate([
            'full_name'               => ['required', 'string', 'max:255'],
            'name_bn'                 => ['nullable', 'string', 'max:255'],
            'username'                => ['required', 'string', 'max:50'], // ইউনিকনেস Member create সময় enforce হবে
            'email'                   => ['nullable', 'email', 'max:255'],
            'phone'                   => ['nullable', 'string', 'max:20'],
            'dob'                     => ['nullable', 'date'],
            'gender'                  => ['nullable', Rule::in(Member::GENDERS)],
            'blood_group'             => ['nullable', Rule::in(Member::BLOOD_GROUPS)],
            'id_number'               => ['nullable', 'string', 'max:100'],
            'education_qualification' => ['nullable', 'string', 'max:255'],
            'profession'              => ['nullable', 'string', 'max:255'],
            'other_expertise'         => ['nullable', 'string'],
            'country'                 => ['nullable', 'string', 'max:255'],
            'division'                => ['nullable', 'string', 'max:255'],
            'district'                => ['nullable', 'string', 'max:255'],
            'address'                 => ['nullable', 'string'],
            'membership_type'         => ['required', Rule::in(Member::MEMBERSHIP_TYPES)],
            'profile_pic'             => ['nullable', 'image', 'max:2048'],

            // ✅ Plan is required for fee
            'membership_plan'         => ['required', 'in:monthly,yearly'],

            // Password optional at this step
            'password'               => ['nullable', 'string', 'min:6', 'confirmed'],
            'password_confirmation'  => ['nullable', 'string', 'min:6'],
        ]);

        // 2) Normalize
        $data['country'] = $data['country'] ?? 'Bangladesh';
        if (!empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        // 3) Upload profile pic (path snapshot-এ রাখা হবে)
        $profilePath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePath = $request->file('profile_pic')->store('members/profile_pics', 'public');
        }

        // 4) Server-side fee
        $amount = $data['membership_plan'] === 'yearly' ? 2000.00 : 200.00;

        // 5) Create pending payment snapshot (❗member_id NULL)
        $payment = MemberPayment::create([
            'member_id'               => null,
            'tran_id'                 => 'INV-'.Str::uuid()->toString(),
            'plan'                    => $data['membership_plan'],
            'amount'                  => $amount,
            'currency'                => 'BDT',
            'status'                  => 'pending',

            // Snapshot (form data)
            'full_name'               => $data['full_name'],
            'name_bn'                 => $data['name_bn'] ?? null,
            'username'                => $data['username'],
            'email'                   => $data['email'] ?? null,
            'phone'                   => $data['phone'] ?? null,
            'dob'                     => $data['dob'] ?? null,
            'gender'                  => $data['gender'] ?? null,
            'blood_group'             => $data['blood_group'] ?? null,
            'id_number'               => $data['id_number'] ?? null,
            'education_qualification' => $data['education_qualification'] ?? null,
            'profession'              => $data['profession'] ?? null,
            'other_expertise'         => $data['other_expertise'] ?? null,
            'country'                 => $data['country'] ?? null,
            'division'                => $data['division'] ?? null,
            'district'                => $data['district'] ?? null,
            'address'                 => $data['address'] ?? null,
            'membership_type'         => $data['membership_type'],
            'profile_pic'             => $profilePath,
        ]);

        // 6) Redirect to SSLCommerz init (init এখন শুধু tran_id নেয়)
        return redirect()->route('sslc.init', [
            'tran_id' => $payment->tran_id,
        ]);
    }
}

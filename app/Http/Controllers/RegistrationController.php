<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MemberPayment;
use Modules\Members\Models\Member;
use DevWizard\Textify\Facades\Textify; // ADD ONLY

class RegistrationController extends Controller
{
    public function create()
    {
        return view('Frontend.register');
    }

    public function store(Request $request)
    {
        // 1) Validate (password is now required)
        $data = $request->validate([
            'full_name'               => ['required', 'string', 'max:255'],
            'name_bn'                 => ['nullable', 'string', 'max:255'],
            'username'                => ['required', 'string', 'max:50'],
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
            'membership_plan'         => ['required', 'in:monthly,yearly'],
            'password'                => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation'   => ['required', 'string', 'min:6'],
        ]);

        // 2) Normalize
        $data['country'] = $data['country'] ?? 'Bangladesh';
        if (!empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        // 3) Upload profile pic (path snapshot)
        $profilePath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePath = $request->file('profile_pic')->store('members/profile_pics', 'public');
        }

        // 4) Determine membership fee
        $amount = $data['membership_plan'] === 'yearly' ? 2000.00 : 200.00;

        // 5) Hash password
        $passwordHash = Hash::make($data['password']);

        // 6) Create pending payment snapshot
        $payment = MemberPayment::create([
            'member_id'               => null,
            'tran_id'                 => 'INV-' . Str::uuid()->toString(),
            'plan'                    => $data['membership_plan'],
            'amount'                  => $amount,
            'currency'                => 'BDT',
            'status'                  => 'pending',
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
            'password_hash'           => $passwordHash,
        ]);

        // 7) Redirect to SSLCommerz init
        return redirect()->route('sslc.init', [
            'tran_id' => $payment->tran_id,
        ]);
    }

    public function handleSslcSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');
        if (! $tranId) {
            return abort(400, 'tran_id is required.');
        }

        $payment = MemberPayment::where('tran_id', $tranId)->firstOrFail();

        // Assume payment verified
        $payment->status = 'completed';
        $payment->save();

        // If member already exists, login and redirect
        if ($payment->member_id) {
            $member = Member::find($payment->member_id);
            if ($member) {
                Auth::login($member);
                return redirect()->route('member.dashboard')->with('success', 'Payment received. Welcome back!');
            }
        }

        // Ensure username/email uniqueness
        $username = $payment->username ?: 'member_' . Str::random(6);
        $baseUsername = $username;
        $attempt = 0;
        while (Member::where('username', $username)->exists()) {
            $attempt++;
            $username = $baseUsername . '_' . Str::random(4);
            if ($attempt > 10) {
                $username = $baseUsername . '_' . time();
                break;
            }
        }

        $email = $payment->email;
        if ($email && Member::where('email', $email)->exists()) {
            if (strpos($email, '@') !== false) {
                [$local, $domain] = explode('@', $email, 2);
                $email = $local . '+dup' . Str::random(4) . '@' . $domain;
            } else {
                $email = null;
            }
        }

        $finalPasswordHash = $payment->password_hash ?? Hash::make(Str::random(12));

        // 1) Generate sequential member_id safely
        $member = DB::transaction(function () use ($payment, $username, $email, $finalPasswordHash) {
            $lastMemberId = DB::table('members')->lockForUpdate()->max('member_id');
            $nextMemberId = $lastMemberId ? $lastMemberId + 1 : 1;

            return Member::create([
                'member_id'               => $nextMemberId,
                'full_name'               => $payment->full_name,
                'name_bn'                 => $payment->name_bn,
                'username'                => $username,
                'email'                   => $email,
                'phone'                   => $payment->phone,
                'dob'                     => $payment->dob,
                'gender'                  => $payment->gender,
                'blood_group'             => $payment->blood_group,
                'id_number'               => $payment->id_number,
                'education_qualification' => $payment->education_qualification,
                'profession'              => $payment->profession,
                'other_expertise'         => $payment->other_expertise,
                'country'                 => $payment->country,
                'division'                => $payment->division,
                'district'                => $payment->district,
                'address'                 => $payment->address,
                'membership_type'         => $payment->membership_type,
                'profile_pic'             => $payment->profile_pic,
                'password'                => $finalPasswordHash,
            ]);
        });

        // Link payment to member
        $payment->member_id = $member->id;
        $payment->save();

        Auth::login($member);

        return redirect()->route('member.dashboard')->with('success', 'Registration successful. Welcome!');
    }
}

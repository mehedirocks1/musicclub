<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Modules\Members\Models\Member;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function create()
    {
        return view('Frontend.register');
    }

    public function store(Request $request)
    {
        // 1) Validate input
        $data = $request->validate([
            'full_name'               => ['required', 'string', 'max:255'],
            'name_bn'                 => ['nullable', 'string', 'max:255'],
            'username'                => ['required', 'string', 'max:50', 'unique:members,username'],
            'email'                   => ['nullable', 'email', 'max:255', 'unique:members,email'],
            'phone'                   => ['nullable', 'string', 'max:20', 'unique:members,phone'],
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

            // Password for members (required + confirmed)
            'password'               => ['required', Password::min(6), 'confirmed'],
            'password_confirmation'  => ['required'],
        ]);

        // 2) Defaults
        $data['member_id']         = 'M' . date('ymd') . Str::upper(Str::random(4));
        $data['registration_date'] = now();
        $data['balance']           = 0;
        $data['country']           = $data['country'] ?? 'Bangladesh';

        if (!empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        // 3) Profile picture
        if ($request->hasFile('profile_pic')) {
            $data['profile_pic'] = $request->file('profile_pic')->store('members/profile_pics', 'public');
        }

        // 4) Ensure password is set (hashed by model cast)
        $data['password'] = $request->input('password') ?: Str::password(12);

        // 5) Only members table
        $member = DB::transaction(function () use ($data) {
            return Member::create($data);
        });

        // 6) Login as member and redirect
        Auth::guard('member')->login($member);
        return redirect()->route('filament.member.pages.dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Members\Models\Member;

class RegistrationController extends Controller
{
    /**
     * Show registration form.
     */
    public function create()
    {
        return view('Frontend.register');
    }

    /**
     * Handle registration form submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'            => ['required', 'string', 'max:255'],
            'name_bn'              => ['nullable', 'string', 'max:255'],
            'username'             => ['required', 'string', 'max:50', 'unique:members,username'],
            'email'                => ['nullable', 'email', 'max:255', 'unique:members,email'],
            'phone'                => ['nullable', 'string', 'max:20', 'unique:members,phone'],
            'dob'                  => ['nullable', 'date'],
            'gender'               => ['nullable', Rule::in(Member::GENDERS)],
            'blood_group'          => ['nullable', Rule::in(Member::BLOOD_GROUPS)],
            'id_number'            => ['nullable', 'string', 'max:100'],
            'education_qualification' => ['nullable', 'string', 'max:255'],
            'profession'           => ['nullable', 'string', 'max:255'],
            'other_expertise'      => ['nullable', 'string'],
            'country'              => ['nullable', 'string', 'max:255'],
            'division'             => ['nullable', 'string', 'max:255'],
            'district'             => ['nullable', 'string', 'max:255'],
            'address'              => ['nullable', 'string'],
            'membership_type'      => ['required', Rule::in(Member::MEMBERSHIP_TYPES)],
            'profile_pic'          => ['nullable', 'image', 'max:2048'],
        ]);

        // Generate unique member_id & defaults
        $data['member_id']         = 'M' . date('ymd') . Str::upper(Str::random(4));
        $data['registration_date'] = now();
        $data['balance']           = 0;
        $data['country']           = $data['country'] ?? 'Bangladesh';

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $data['profile_pic'] = $request->file('profile_pic')->store('members/profile_pics', 'public');
        }

        // Save to Members module table
        Member::create($data);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to POJ Music Club.');
    }
}

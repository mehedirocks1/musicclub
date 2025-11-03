<?php

namespace Modules\Members\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class MembersController extends Controller
{
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
  
 * Update member profile
 */
public function updateProfile(Request $request)
{
    $member = auth('member')->user();

    // Validate input including profile_pic
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
        'profile_pic'      => 'nullable|image|max:2048', // max 2MB
    ]);

    // Handle profile picture upload
    if ($request->hasFile('profile_pic')) {
        $file = $request->file('profile_pic');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('profile_pics', $filename, 'public');

        // Update profile_pic in $data
        $data['profile_pic'] = $filePath;
    }

    // Update member data
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
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $member->password = Hash::make($request->password);
        $member->save();

        return redirect()->route('member.dashboard')->with('success', 'Password updated successfully');
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
     * Check Payments page
     */
    public function checkPayments()
    {
        $member = Auth::guard('member')->user();
        return view('member.check-payments', compact('member'));
    }

    // Optional: fallback index for resource routes
    public function index()
    {
        return redirect()->route('member.dashboard');
    }



/**
 * Download member ID card as PDF (barryvdh/laravel-dompdf)
 *
 * @param  int|string  $id
 * @return \Symfony\Component\HttpFoundation\StreamedResponse
 */
public function memberCard($id)
{
    // Try module Member model first, fallback to App\Models\User if needed
    if (class_exists(\Modules\Members\Models\Member::class)) {
        $modelClass = \Modules\Members\Models\Member::class;
    } elseif (class_exists(\App\Models\User::class)) {
        $modelClass = \App\Models\User::class;
    } else {
        abort(500, 'Member model not found.');
    }

    $member = $modelClass::find($id);

    if (! $member) {
        abort(404, 'Member not found.');
    }

    // Prepare data exactly like your Blade expects ($allData)
    $data = ['allData' => $member];

    // Generate PDF
    $pdf = Pdf::loadView('backend.customer.member-card', $data)
        ->setOptions([
            'defaultFont'    => 'sans-serif',
            'isRemoteEnabled'=> true, // enable if you use external fonts/assets
        ])
        ->setPaper('a4', 'portrait');

    $filename = 'member-card-' . ($member->member_id ?? $member->id) . '.pdf';

    return response()->streamDownload(
        fn () => print($pdf->output()),
        $filename
    );
}












}

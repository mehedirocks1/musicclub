<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Members\Models\Member;
use Illuminate\Support\Facades\Hash;
use DevWizard\Textify\Facades\Textify;

class OtpForgotPasswordController extends Controller
{
    // Step 1: Show phone input form
    public function showMobileForm()
    {
        return view('Frontend.otp-request');
    }

    // Step 2: Send OTP via phone only
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:members,phone',
        ]);

        $member = Member::where('phone', $request->phone)->first();

        if (!$member) {
            return back()->withErrors(['phone' => 'Phone number not found.']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP in session with 5-minute expiry
        session([
            'otp' => $otp,
            'otp_expires' => now()->addMinutes(5),
            'otp_phone' => $member->phone,
        ]);

        $msg = "Your OTP for POJ Music Club is: {$otp}. Valid for 5 minutes.";

        // Normalize Bangladeshi phone number
        $phone = $this->normalizeBdMsisdn($member->phone);

        try {
            Textify::to($phone)
                ->message($msg)
                ->via('bulksmsbd')
                ->send();
        } catch (\Throwable $e) {
            return back()->withErrors(['phone' => 'Failed to send OTP.']);
        }

        return redirect()->route('member.password.otp.verify.form')
            ->with('status', 'OTP sent successfully to your phone.');
    }

    // Step 3: Show OTP verification form
    public function showOtpForm()
    {
        $phone = session('otp_phone');

        if (!$phone) {
            return redirect()->route('member.password.otp.request');
        }

        return view('Frontend.otp-verify', compact('phone'));
    }

    // Step 4: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!session()->has('otp') || !session()->has('otp_expires') || !session()->has('otp_phone')) {
            return redirect()->route('member.password.otp.request');
        }

        $sessionOtp = session('otp');
        $sessionExpires = session('otp_expires');
        $sessionPhone = session('otp_phone');

        if ($request->otp != $sessionOtp || now()->gt($sessionExpires)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('member.password.reset.form');
    }

    // Step 5: Show reset password form
    public function showResetForm()
    {
        if (!session()->has('otp_verified') || !session()->has('otp_phone')) {
            return redirect()->route('member.password.otp.request');
        }

        $phone = session('otp_phone');
        return view('Frontend.reset-password', compact('phone'));
    }

    // Step 6: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:members,phone',
            'password' => 'required|confirmed|min:6',
        ]);

        $member = Member::where('phone', $request->phone)->first();
        $member->password = Hash::make($request->password);
        $member->save();

        // Clear OTP session
        session()->forget(['otp', 'otp_expires', 'otp_phone', 'otp_verified']);

        return redirect()->route('member.login')->with('status', 'Password reset successfully. You can now login.');
    }

    // Helper: normalize Bangladeshi phone number
    private function normalizeBdMsisdn($number)
    {
        // Remove all non-digit characters
        $number = preg_replace('/[^\d]/', '', $number);

        // Normalize
        if (preg_match('/^0\d+$/', $number)) {
            $number = '880' . substr($number, 1);
        } elseif (preg_match('/^880\d+$/', $number)) {
            // already correct
        } elseif (preg_match('/^\+880\d+$/', $number)) {
            $number = substr($number, 1); // remove plus
        }

        // Add plus for sending
        $number = '+' . $number;

        return $number;
    }
}

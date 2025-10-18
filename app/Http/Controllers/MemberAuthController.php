<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    public function login(Request $request)
    {
        // validate
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        // যদি Filament member panel আলাদা guard ইউজ করে থাকো, এখানে সেট করো
        // উদাহরণ: $guard = 'member';
        // নাহলে ডিফল্ট 'web' guard-ই ঠিক আছে
        $guard = config('auth.defaults.guard', 'web');

        // attempt
        if (! Auth::guard($guard)->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            (bool) ($credentials['remember'] ?? false)
        )) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        // session regenerate (security)
        $request->session()->regenerate();

        // Filament member panel ড্যাশবোর্ডে পাঠাও
        return redirect()->route('filament.member.pages.dashboard');
    }

    public function logout(Request $request)
    {
        $guard = config('auth.defaults.guard', 'web');

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // লগআউটের পর হোমে বা লগইন পেজে
        return redirect()->to('/');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    /**
     * Show custom member login form.
     */
public function showLoginForm()
{
    // If already logged in as a member, redirect to dashboard
    if (Auth::guard('member')->check()) {
        return redirect()->route('member.dashboard');
    }

    return view('member.login'); // show login form only if not logged in
}

    /**
     * Handle member login using 'member' guard.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $guard = 'member';
        $remember = (bool) ($credentials['remember'] ?? false);

        // Attempt login
        if (! Auth::guard($guard)->attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        )) {
            throw ValidationException::withMessages([
                'email' => __('Invalid email or password.'),
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to member dashboard
        return redirect()->route('member.dashboard');
    }

    /**
     * Logout member.
     */
    public function logout(Request $request)
    {
        $guard = 'member';

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login');
    }
}

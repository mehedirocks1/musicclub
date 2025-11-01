<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($data['remember'] ?? false);
        $email    = strtolower(trim($data['email']));
        $pass     = $data['password'];

        // 1) Try MEMBER guard first (members provider)
        if (Auth::guard('member')->attempt(['email' => $email, 'password' => $pass], $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('member')->user();

            // If member model has roles, respect them; otherwise go to member panel
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

              return redirect()->route('member.dashboard');
        }

        // 2) Fallback: try WEB guard (admins/users provider)
        if (Auth::guard('web')->attempt(['email' => $email, 'password' => $pass], $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            // If using Spatie roles on User, route by role; else default to admin panel
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('admin')) {
                    return redirect()->route('filament.admin.pages.dashboard');
                }
                if ($user->hasRole('member')) {
                    return redirect()->route('member.dashboard');

                }
            }

            return redirect()->route('filament.admin.pages.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('member')->check()) {
            Auth::guard('member')->logout();
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

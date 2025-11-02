<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            // this field may contain either email or username (we'll detect)
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($data['remember'] ?? false);
        $login    = trim($data['email']); // may be username or email
        $pass     = $data['password'];

        // detect if login looks like an email
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;

        // normalize email if used
        if ($isEmail) {
            $login = strtolower($login);
        }

        /**
         * 1) Try MEMBER guard first (members provider)
         *    - attempt by email if looks like email
         *    - else attempt by username
         */
        $memberCredentials = $isEmail
            ? ['email' => $login, 'password' => $pass]
            : ['username' => $login, 'password' => $pass];

        if (Auth::guard('member')->attempt($memberCredentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('member')->user();

            // If member model has roles, respect them; otherwise go to member panel
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            return redirect()->route('member.dashboard');
        }

        /**
         * 2) Fallback: try WEB guard (admins/users provider)
         *    - admins/users typically login via email. We still try username as fallback.
         */
        $webCredentials = $isEmail
            ? ['email' => $login, 'password' => $pass]
            : ['username' => $login, 'password' => $pass];

        if (Auth::guard('web')->attempt($webCredentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

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
        // logout from both guards if needed
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

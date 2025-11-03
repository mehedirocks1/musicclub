<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle a login request for either member or admin/user.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            // Can be email or username
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $login    = trim($data['email']);
        $password = $data['password'];
        $remember = (bool) ($data['remember'] ?? false);

        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;

        // Normalize email if needed
        if ($isEmail) {
            $login = strtolower($login);
        }

        // Attempt login via MEMBER guard first
        if ($this->attemptLogin('member', $login, $password, $remember, $isEmail)) {
            $user = Auth::guard('member')->user();
            return redirect()->route('member.dashboard');
        }

        // Fallback: attempt login via WEB guard (admin/user)
        if ($this->attemptLogin('web', $login, $password, $remember, $isEmail)) {
            $user = Auth::guard('web')->user();

            // Redirect based on role
            if ($user->hasRole('super_admin') || $user->hasRole('administrator') || $user->hasRole('editor')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }
            if ($user->hasRole('viewer')) {
                return redirect()->route('filament.admin.pages.dashboard'); // adjust if needed
            }

            // Default fallback
            return redirect()->route('filament.admin.pages.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    /**
     * Attempt login for a specific guard.
     */
    protected function attemptLogin(string $guard, string $login, string $password, bool $remember, bool $isEmail): bool
    {
        $credentials = $isEmail
            ? ['email' => $login, 'password' => $password]
            : ['username' => $login, 'password' => $password];

        if (Auth::guard($guard)->attempt($credentials, $remember)) {
            session()->regenerate();
            return true;
        }

        return false;
    }

    /**
     * Log the user out of all guards.
     */
    public function logout(Request $request)
    {
        foreach (['member', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

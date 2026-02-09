<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isCollegeAdmin()) {
            return redirect()->route('college.dashboard');
        }

        return view('auth.login', ['defaultRole' => 'college']);
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->isCollegeAdmin()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['You do not have College Admin access.'],
                ]);
            }

            if (!$user->college_id) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your account is not associated with a college.'],
                ]);
            }

            if ($user->college && $user->college->status !== 'active') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your college account is inactive. Please contact the administrator.'],
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('college.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProgramManagerCredential;
use App\Models\VendorEventCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UnifiedAuthController extends Controller
{
    /**
     * Show the unified login form.
     */
    public function showLoginForm()
    {
        // Check if already logged in
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }
            if ($user->isCollegeAdmin()) {
                return redirect()->route('college.dashboard');
            }
            if ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }
        }

        // Check if program manager is logged in
        if (session('program_manager_credential_id')) {
            $credential = ProgramManagerCredential::with(['program'])
                ->where('id', session('program_manager_credential_id'))
                ->where('status', 'active')
                ->first();

            if ($credential) {
                return redirect()->route('manager.program.dashboard', $credential->program_id);
            }
        }

        // Check if vendor is logged in (legacy)
        if (session('vendor_event_credential_id')) {
            $credential = VendorEventCredential::with(['vendor', 'event'])
                ->where('id', session('vendor_event_credential_id'))
                ->where('status', 'active')
                ->first();

            if ($credential) {
                return redirect()->route('vendor.event.dashboard', $credential->event_id);
            }
        }

        return view('auth.unified-login');
    }

    /**
     * Handle unified login - auto-detects user type and redirects.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = $request->username;
        $password = $request->password;
        $remember = $request->boolean('remember');

        // Try 1: Super Admin (email-based)
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $username)
                ->where('role', 'SUPER_ADMIN')
                ->first();

            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('super-admin.dashboard'));
            }
        }

        // Try 2: College Admin (email-based)
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $username)
                ->where('role', 'COLLEGE_ADMIN')
                ->first();

            if ($user && Hash::check($password, $user->password)) {
                // Check if college is active
                if (! $user->college_id) {
                    throw ValidationException::withMessages([
                        'username' => ['Your account is not associated with a college.'],
                    ]);
                }

                if ($user->college && $user->college->status !== 'active') {
                    throw ValidationException::withMessages([
                        'username' => ['Your college account is inactive. Please contact the administrator.'],
                    ]);
                }

                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('college.dashboard'));
            }
        }

        // Try 2b: Student (email-based)
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $username)
                ->where('role', 'STUDENT')
                ->first();

            if ($user && Hash::check($password, $user->password)) {
                if (! $user->college_id) {
                    throw ValidationException::withMessages([
                        'username' => ['Your account is not associated with a college.'],
                    ]);
                }

                if ($user->college && $user->college->status !== 'active') {
                    throw ValidationException::withMessages([
                        'username' => ['Your college account is inactive. Please contact the administrator.'],
                    ]);
                }

                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('student.dashboard'));
            }
        }

        // Try 3: Program Manager Credential (username-based)
        $programCredential = ProgramManagerCredential::with(['program.event', 'college'])
            ->where('username', $username)
            ->where('status', 'active')
            ->first();

        if ($programCredential && Hash::check($password, $programCredential->password)) {
            if ($programCredential->program->event->status !== 'Active') {
                throw ValidationException::withMessages([
                    'username' => ['This year/semester/event is not currently active.'],
                ]);
            }

            if ($programCredential->college->status !== 'active') {
                throw ValidationException::withMessages([
                    'username' => ['The college for this subject/program is inactive.'],
                ]);
            }

            session([
                'program_manager_credential_id' => $programCredential->id,
                'program_id' => $programCredential->program_id,
                'program_manager_type' => $programCredential->manager_type,
                'program_manager_id' => $programCredential->manager_id,
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('manager.program.dashboard', $programCredential->program_id));
        }

        // Try 4: Vendor Event Credential (username-based)
        $credential = VendorEventCredential::with(['vendor', 'event', 'college'])
            ->where('username', $username)
            ->where('status', 'active')
            ->first();

        if ($credential && Hash::check($password, $credential->password)) {
            // Check if event is active
            if ($credential->event->status !== 'Active') {
                throw ValidationException::withMessages([
                    'username' => ['This year/semester/event is not currently active.'],
                ]);
            }

            // Check if college is active
            if ($credential->college->status !== 'active') {
                throw ValidationException::withMessages([
                    'username' => ['The college for this year/semester/event is inactive.'],
                ]);
            }

            // Store credential ID in session
            session([
                'vendor_event_credential_id' => $credential->id,
                'vendor_event_id' => $credential->event_id,
                'vendor_id' => $credential->vendor_id,
            ]);

            $request->session()->regenerate();

            return redirect()->intended(route('vendor.event.dashboard', $credential->event_id));
        }

        // If none matched, show error
        throw ValidationException::withMessages([
            'username' => ['Invalid credentials. Please check your username/email and password.'],
        ]);
    }

    /**
     * Handle logout - clears all session data.
     */
    public function logout(Request $request)
    {
        // Clear program manager session if exists
        if (session('program_manager_credential_id')) {
            $request->session()->forget([
                'program_manager_credential_id',
                'program_id',
                'program_manager_type',
                'program_manager_id',
            ]);
        }

        // Clear vendor session if exists
        if (session('vendor_event_credential_id')) {
            $request->session()->forget([
                'vendor_event_credential_id',
                'vendor_event_id',
                'vendor_id',
            ]);
        }

        // Clear user auth if exists
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

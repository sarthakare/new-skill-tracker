<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        return view('student.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($request->only('email', 'password'), $remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = Auth::user();
        if (! $user->isStudent()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['These credentials are not for a student account.'],
            ]);
        }

        if (! $user->college_id) {
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

        return redirect()->intended(route('student.dashboard'));
    }

    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        $colleges = College::where('status', 'active')->orderBy('name')->get();

        return view('student.auth.register', compact('colleges'));
    }

    /**
     * JSON list of departments for an active college (used by registration form).
     */
    public function departmentsForCollege(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'college_id' => [
                'required',
                'integer',
                Rule::exists('colleges', 'id')->where('status', 'active'),
            ],
        ]);

        $departments = Department::query()
            ->where('college_id', $validated['college_id'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($departments);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'mobile' => ['required', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'college_id' => [
                'required',
                Rule::exists('colleges', 'id')->where('status', 'active'),
            ],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where('college_id', $request->input('college_id')),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'STUDENT',
            'college_id' => $validated['college_id'],
            'department_id' => $validated['department_id'],
            'mobile' => preg_replace('/\s+/', ' ', trim($validated['mobile'])),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }
}

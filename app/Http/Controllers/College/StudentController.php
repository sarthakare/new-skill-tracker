<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * List student accounts registered for this college.
     */
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;

        $students = User::query()
            ->where('college_id', $collegeId)
            ->where('role', 'STUDENT')
            ->with('department')
            ->orderByRaw('(CASE WHEN roll_number IS NULL OR TRIM(COALESCE(roll_number, \'\')) = \'\' THEN 1 ELSE 0 END)')
            ->orderBy('roll_number')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('college.students.index', compact('students'));
    }

    public function edit(User $student): View
    {
        $this->ensureStudentInCollege($student);

        $departments = Department::where('college_id', Auth::user()->college_id)
            ->orderBy('name')
            ->get();

        return view('college.students.edit', compact('student', 'departments'));
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $this->ensureStudentInCollege($student);

        $collegeId = Auth::user()->college_id;

        $mobileRaw = trim((string) $request->input('mobile', ''));
        $request->merge([
            'roll_number' => trim((string) $request->input('roll_number', '')),
            'mobile' => $mobileRaw === '' ? null : $mobileRaw,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->id)],
            'roll_number' => [
                'required',
                'string',
                'max:64',
                Rule::unique('users', 'roll_number')
                    ->ignore($student->id)
                    ->where(fn ($query) => $query->where('college_id', $collegeId)->where('role', 'STUDENT')),
            ],
            'mobile' => ['nullable', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where('college_id', $collegeId),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $passwordChanged = ! empty($validated['password'] ?? null);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'roll_number' => $validated['roll_number'],
            'mobile' => filled($validated['mobile'] ?? null)
                ? preg_replace('/\s+/', ' ', trim((string) $validated['mobile']))
                : null,
            'department_id' => $validated['department_id'],
        ];

        if ($passwordChanged) {
            $payload['password'] = $validated['password'];
        }

        $student->update($payload);

        ActivityLog::create([
            'college_id' => $collegeId,
            'user_id' => Auth::id(),
            'action' => 'student.updated',
            'description' => $passwordChanged
                ? "Student '{$student->name}' ({$student->email}) was updated; password was changed by admin"
                : "Student '{$student->name}' ({$student->email}) was updated",
        ]);

        return redirect()->route('college.students.index')
            ->with('success', 'Student updated successfully.');
    }

    private function ensureStudentInCollege(User $user): void
    {
        if ($user->college_id !== Auth::user()->college_id || ! $user->isStudent()) {
            abort(403, 'You cannot access this student account.');
        }
    }
}

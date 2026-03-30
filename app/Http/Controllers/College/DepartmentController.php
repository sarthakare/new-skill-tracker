<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;
        $departments = Department::where('college_id', $collegeId)
            ->withCount(['students'])
            ->orderBy('name')
            ->paginate(20);

        return view('college.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('college.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $collegeId = Auth::user()->college_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->where('college_id', $collegeId),
            ],
        ]);

        $department = Department::create([
            'college_id' => $collegeId,
            'name' => $validated['name'],
        ]);

        ActivityLog::create([
            'college_id' => $collegeId,
            'user_id' => Auth::id(),
            'action' => 'department.created',
            'description' => "Department '{$department->name}' was created",
        ]);

        return redirect()->route('college.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        $this->ensureCollegeScope($department);

        return view('college.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $this->ensureCollegeScope($department);

        $collegeId = Auth::user()->college_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')
                    ->where('college_id', $collegeId)
                    ->ignore($department->id),
            ],
        ]);

        $department->update($validated);

        ActivityLog::create([
            'college_id' => $collegeId,
            'user_id' => Auth::id(),
            'action' => 'department.updated',
            'description' => "Department '{$department->name}' was updated",
        ]);

        return redirect()->route('college.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->ensureCollegeScope($department);

        $name = $department->name;
        $department->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'department.deleted',
            'description' => "Department '{$name}' was deleted",
        ]);

        return redirect()->route('college.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function ensureCollegeScope(Department $department): void
    {
        if ($department->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this department.');
        }
    }
}

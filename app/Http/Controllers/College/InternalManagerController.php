<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InternalManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InternalManagerController extends Controller
{
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;
        $managers = InternalManager::where('college_id', $collegeId)
            ->latest()
            ->paginate(15);

        return view('college.internal-managers.index', compact('managers'));
    }

    public function create(): View
    {
        return view('college.internal-managers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'regex:/^[0-9]+$/', 'max:50'],
        ]);

        $manager = InternalManager::create([
            'college_id' => Auth::user()->college_id,
            'name' => $validated['name'],
            'department' => $validated['department'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'internal_manager.created',
            'description' => "Internal manager '{$manager->name}' was created",
        ]);

        return redirect()->route('college.internal-managers.index')
            ->with('success', 'Internal manager created successfully.');
    }

    public function edit(InternalManager $internalManager): View
    {
        $this->ensureCollegeScope($internalManager);

        return view('college.internal-managers.edit', compact('internalManager'));
    }

    public function update(Request $request, InternalManager $internalManager): RedirectResponse
    {
        $this->ensureCollegeScope($internalManager);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'regex:/^[0-9]+$/', 'max:50'],
        ]);

        $internalManager->update($validated);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'internal_manager.updated',
            'description' => "Internal manager '{$internalManager->name}' was updated",
        ]);

        return redirect()->route('college.internal-managers.index')
            ->with('success', 'Internal manager updated successfully.');
    }

    public function destroy(InternalManager $internalManager): RedirectResponse
    {
        $this->ensureCollegeScope($internalManager);

        $managerName = $internalManager->name;
        $internalManager->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'internal_manager.deleted',
            'description' => "Internal manager '{$managerName}' was deleted",
        ]);

        return redirect()->route('college.internal-managers.index')
            ->with('success', 'Internal manager deleted successfully.');
    }

    private function ensureCollegeScope(InternalManager $manager): void
    {
        if ($manager->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this manager.');
        }
    }
}

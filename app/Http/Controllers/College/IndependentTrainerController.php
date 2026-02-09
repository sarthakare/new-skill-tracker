<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IndependentTrainer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IndependentTrainerController extends Controller
{
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;
        $trainers = IndependentTrainer::where('college_id', $collegeId)
            ->latest()
            ->paginate(15);

        return view('college.independent-trainers.index', compact('trainers'));
    }

    public function create(): View
    {
        return view('college.independent-trainers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        $trainer = IndependentTrainer::create([
            'college_id' => Auth::user()->college_id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'expertise' => $validated['expertise'] ?? null,
        ]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'independent_trainer.created',
            'description' => "Independent trainer '{$trainer->name}' was created",
        ]);

        return redirect()->route('college.independent-trainers.index')
            ->with('success', 'Independent trainer created successfully.');
    }

    public function edit(IndependentTrainer $independentTrainer): View
    {
        $this->ensureCollegeScope($independentTrainer);

        return view('college.independent-trainers.edit', compact('independentTrainer'));
    }

    public function update(Request $request, IndependentTrainer $independentTrainer): RedirectResponse
    {
        $this->ensureCollegeScope($independentTrainer);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'expertise' => ['nullable', 'string', 'max:255'],
        ]);

        $independentTrainer->update($validated);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'independent_trainer.updated',
            'description' => "Independent trainer '{$independentTrainer->name}' was updated",
        ]);

        return redirect()->route('college.independent-trainers.index')
            ->with('success', 'Independent trainer updated successfully.');
    }

    public function destroy(IndependentTrainer $independentTrainer): RedirectResponse
    {
        $this->ensureCollegeScope($independentTrainer);

        $trainerName = $independentTrainer->name;
        $independentTrainer->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'independent_trainer.deleted',
            'description' => "Independent trainer '{$trainerName}' was deleted",
        ]);

        return redirect()->route('college.independent-trainers.index')
            ->with('success', 'Independent trainer deleted successfully.');
    }

    private function ensureCollegeScope(IndependentTrainer $trainer): void
    {
        if ($trainer->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this trainer.');
        }
    }
}

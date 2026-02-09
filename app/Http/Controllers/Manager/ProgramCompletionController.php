<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramCompletionRequest;
use App\Models\ProgramManagerCredential;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramCompletionController extends Controller
{
    public function create(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $existing = ProgramCompletionRequest::where('program_id', $program->id)
            ->latest()
            ->first();

        return view('manager.programs.completion', compact('program', 'existing', 'credential'));
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ]);

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->where('program_id', $program->id)
            ->firstOrFail();

        $storedFiles = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $storedFiles[] = $file->store('program-completions', 'public');
            }
        }

        ProgramCompletionRequest::create([
            'college_id' => $program->college_id,
            'program_id' => $program->id,
            'requested_by_credential_id' => $credential->id,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'attachments' => $storedFiles,
        ]);

        $program->update(['status' => 'Completed']);

        return redirect()->route('manager.program.dashboard', $program)
            ->with('success', 'Completion request submitted successfully.');
    }
}

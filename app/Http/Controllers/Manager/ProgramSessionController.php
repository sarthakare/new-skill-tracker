<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use App\Models\ProgramSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramSessionController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $sessions = ProgramSession::where('program_id', $program->id)
            ->orderBy('session_date')
            ->get();

        return view('manager.programs.sessions', compact('program', 'sessions', 'credential'));
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'session_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
        ]);

        ProgramSession::create([
            'college_id' => $program->college_id,
            'program_id' => $program->id,
            'title' => $validated['title'],
            'session_date' => $validated['session_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'status' => 'scheduled',
        ]);

        if (in_array($program->status, ['Manager_Assigned', 'Registration_Open'])) {
            $program->update(['status' => 'In_Progress']);
        }

        return redirect()->route('manager.program.sessions.index', $program)
            ->with('success', 'Session created successfully.');
    }
}

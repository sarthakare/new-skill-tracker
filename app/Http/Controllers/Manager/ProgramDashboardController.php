<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use Illuminate\View\View;

class ProgramDashboardController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::with(['program.event', 'program.college'])
            ->where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $program->load(['event', 'students', 'sessions', 'completionRequests']);

        $stats = [
            'program_name' => $program->name,
            'event_name' => $program->event->name,
            'status' => $program->status,
            'students_count' => $program->students->count(),
            'sessions_count' => $program->sessions->count(),
            'pending_completion' => $program->completionRequests->where('status', 'pending')->count(),
            'manager_name' => $credential->managerLabel(),
        ];

        return view('manager.programs.dashboard', compact('program', 'credential', 'stats'));
    }
}

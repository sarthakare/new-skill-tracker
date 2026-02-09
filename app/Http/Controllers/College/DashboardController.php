<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Program;
use App\Models\ProgramCompletionRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the College Admin dashboard.
     */
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;

        $stats = [
            'total_events' => Event::where('college_id', $collegeId)->count(),
            'active_events' => Event::where('college_id', $collegeId)->where('status', 'Active')->count(),
            'completed_events' => Event::where('college_id', $collegeId)->where('status', 'Completed')->count(),
            'total_users' => User::where('college_id', $collegeId)->count(),
            'vendors_count' => Vendor::where('college_id', $collegeId)->count(),
            'total_programs' => Program::where('college_id', $collegeId)->count(),
            'active_programs' => Program::where('college_id', $collegeId)->where('status', 'In_Progress')->count(),
            'pending_completion_requests' => ProgramCompletionRequest::where('college_id', $collegeId)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('college.dashboard', compact('stats'));
    }

    public function completionRequests(): View
    {
        $collegeId = Auth::user()->college_id;

        $requests = ProgramCompletionRequest::with(['program.event', 'requestedBy'])
            ->where('college_id', $collegeId)
            ->orderByDesc('created_at')
            ->get();

        return view('college.completion-requests', compact('requests'));
    }
}

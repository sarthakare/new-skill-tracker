<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Http\Requests\College\AssignEventUserRequest;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventUserController extends Controller
{
    public function index(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $eventUsers = EventUser::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->with('user')
            ->latest()
            ->paginate(15);

        $collegeId = Auth::user()->college_id;
        $availableUsers = User::where('college_id', $collegeId)
            ->whereNotIn('id', function ($query) use ($event) {
                $query->select('user_id')
                    ->from('event_users')
                    ->where('event_id', $event->id);
            })
            ->get();

        return view('college.events.users.index', compact('event', 'eventUsers', 'availableUsers'));
    }

    public function store(AssignEventUserRequest $request, Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $collegeId = Auth::user()->college_id;

        // Check if user belongs to the same college
        $user = User::where('id', $request->user_id)
            ->where('college_id', $collegeId)
            ->firstOrFail();

        // Check if already assigned
        $existing = EventUser::where('event_id', $event->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'User is already assigned to this year/event.');
        }

        EventUser::create([
            'event_id' => $event->id,
            'user_id' => $request->user_id,
            'college_id' => $collegeId,
            'role' => $request->role,
        ]);

        ActivityLog::create([
            'college_id' => $collegeId,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'user.assigned',
            'description' => "User '{$user->name}' assigned as '{$request->role}' to year/event '{$event->name}'",
        ]);

        return redirect()->back()
            ->with('success', 'User assigned to year/event successfully.');
    }

    public function destroy(Event $event, EventUser $eventUser): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        if ($eventUser->event_id !== $event->id || $eventUser->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access.');
        }

        $userName = $eventUser->user->name;
        $eventUser->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'user.unassigned',
            'description' => "User '{$userName}' unassigned from year/event '{$event->name}'",
        ]);

        return redirect()->back()
            ->with('success', 'User unassigned from year/event successfully.');
    }

    private function ensureCollegeScope(Event $event): void
    {
        if ($event->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this year/event.');
        }
    }
}

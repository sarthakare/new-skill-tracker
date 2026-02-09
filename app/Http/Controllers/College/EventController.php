<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Http\Requests\College\StoreEventRequest;
use App\Http\Requests\College\UpdateEventRequest;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\VendorEventCredential;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;
        $events = Event::where('college_id', $collegeId)
            ->latest()
            ->paginate(15);

        return view('college.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('college.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $collegeId = Auth::user()->college_id;

        $event = Event::create([
            'college_id' => $collegeId,
            'name' => $request->name,
            'description' => $request->description,
            'type' => 'Training',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target_audience' => $request->target_audience,
            'status' => 'Draft',
        ]);

        ActivityLog::create([
            'college_id' => $collegeId,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'event.created',
            'description' => "Event '{$event->name}' was created",
        ]);

        return redirect()->route('college.events.show', $event)
            ->with('success', 'Event created successfully. Add programs under this event.');
    }

    public function show(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $event->load(['vendors', 'vendorCredentials.vendor', 'programs.oversightManager']);

        return view('college.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->ensureCollegeScope($event);

        return view('college.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target_audience' => $request->target_audience,
            'status' => $request->status,
        ]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'event.updated',
            'description' => "Event '{$event->name}' was updated",
        ]);

        return redirect()->route('college.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $eventName = $event->name;
        $event->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'event.deleted',
            'description' => "Event '{$eventName}' was deleted",
        ]);

        return redirect()->route('college.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function toggleStatus(Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $newStatus = match ($event->status) {
            'Draft' => 'Active',
            'Active' => 'Completed',
            'Completed' => 'Archived',
            default => $event->status,
        };

        if ($newStatus === $event->status) {
            return redirect()->back()->with('error', 'Archived events cannot be modified.');
        }
        $event->update(['status' => $newStatus]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'event.status_changed',
            'description' => "Event '{$event->name}' status changed to {$newStatus}",
        ]);

        return redirect()->back()
            ->with('success', "Event status changed to {$newStatus}.");
    }

    public function vendorCredentials(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $event->load(['vendors', 'vendorCredentials.vendor']);
        
        $credentials = VendorEventCredential::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->with('vendor')
            ->get();

        $generatedCredentials = session('generated_credentials', []);

        return view('college.events.vendor-credentials', compact('event', 'credentials', 'generatedCredentials'));
    }

    private function ensureCollegeScope(Event $event): void
    {
        if ($event->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
}

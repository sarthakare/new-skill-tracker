<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventModuleController extends Controller
{
    private const AVAILABLE_MODULES = [
        'Attendance',
        'Teams',
        'Judging',
        'Syllabus',
        'Certificates',
    ];

    public function index(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $modules = EventModule::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->get()
            ->keyBy('module_name');

        // Ensure all modules exist
        foreach (self::AVAILABLE_MODULES as $moduleName) {
            if (!isset($modules[$moduleName])) {
                EventModule::create([
                    'event_id' => $event->id,
                    'college_id' => Auth::user()->college_id,
                    'module_name' => $moduleName,
                    'is_enabled' => false,
                ]);
            }
        }

        $modules = EventModule::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->orderBy('module_name')
            ->get();

        return view('college.events.modules.index', compact('event', 'modules'));
    }

    public function toggle(Request $request, Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $request->validate([
            'module_name' => ['required', 'string', 'in:'.implode(',', self::AVAILABLE_MODULES)],
        ]);

        $module = EventModule::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->where('module_name', $request->module_name)
            ->firstOrFail();

        $module->update([
            'is_enabled' => !$module->is_enabled,
        ]);

        $status = $module->is_enabled ? 'enabled' : 'disabled';

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'module.toggled',
            'description' => "Module '{$request->module_name}' was {$status} for event '{$event->name}'",
        ]);

        return redirect()->back()
            ->with('success', "Module {$status} successfully.");
    }

    private function ensureCollegeScope(Event $event): void
    {
        if ($event->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this event.');
        }
    }
}

<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\VendorEventCredential;
use Illuminate\View\View;

class EventDashboardController extends Controller
{
    public function index(Event $event): View
    {
        // Verify vendor has access to this event
        $credentialId = session('vendor_event_credential_id');
        
        if (!$credentialId) {
            abort(403, 'Not authenticated.');
        }

        $credential = VendorEventCredential::with(['vendor', 'event', 'college'])
            ->where('id', $credentialId)
            ->where('event_id', $event->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Load event details
        $event->load([
            'college',
            'modules',
            'vendors' => function ($query) use ($credential) {
                $query->where('vendors.id', $credential->vendor_id);
            },
        ]);

        // Get event statistics
        $stats = [
            'event_name' => $event->name,
            'event_type' => $event->type,
            'event_status' => $event->status,
            'start_date' => $event->start_date->format('M d, Y'),
            'end_date' => $event->end_date->format('M d, Y'),
            'college_name' => $event->college->name,
            'modules_enabled' => $event->modules->where('is_enabled', true)->count(),
            'total_modules' => $event->modules->count(),
            'vendor_name' => $credential->vendor->name,
            'vendor_type' => $credential->vendor->type,
        ];

        return view('vendor.event-dashboard', compact('event', 'credential', 'stats'));
    }
}

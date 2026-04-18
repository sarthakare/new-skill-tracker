<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Http\Requests\College\StoreVendorRequest;
use App\Http\Requests\College\UpdateVendorRequest;
use App\Models\ActivityLog;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $collegeId = Auth::user()->college_id;
        $vendors = Vendor::where('college_id', $collegeId)
            ->latest()
            ->paginate(15);

        return view('college.vendors.index', compact('vendors'));
    }

    public function create(): View
    {
        return view('college.vendors.create');
    }

    public function store(StoreVendorRequest $request): RedirectResponse
    {
        $collegeId = Auth::user()->college_id;

        $vendor = Vendor::create([
            'college_id' => $collegeId,
            'name' => $request->name,
            'type' => $request->type,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
        ]);

        ActivityLog::create([
            'college_id' => $collegeId,
            'user_id' => Auth::id(),
            'action' => 'vendor.created',
            'description' => "Vendor '{$vendor->name}' was created",
        ]);

        return redirect()->route('college.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor): View
    {
        $this->ensureCollegeScope($vendor);

        $vendor->load('events');

        return view('college.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor): View
    {
        $this->ensureCollegeScope($vendor);

        return view('college.vendors.edit', compact('vendor'));
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $this->ensureCollegeScope($vendor);

        $vendor->update([
            'name' => $request->name,
            'type' => $request->type,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'address' => $request->address,
        ]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'vendor.updated',
            'description' => "Vendor '{$vendor->name}' was updated",
        ]);

        return redirect()->route('college.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $this->ensureCollegeScope($vendor);

        $vendorName = $vendor->name;
        $vendor->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'user_id' => Auth::id(),
            'action' => 'vendor.deleted',
            'description' => "Vendor '{$vendorName}' was deleted",
        ]);

        return redirect()->route('college.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    public function assignToEvent(Request $request, Vendor $vendor): RedirectResponse
    {
        $this->ensureCollegeScope($vendor);

        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
        ]);

        $event = \App\Models\Event::where('id', $request->event_id)
            ->where('college_id', Auth::user()->college_id)
            ->firstOrFail();

        // Check if already assigned
        $existing = \App\Models\EventVendor::where('event_id', $event->id)
            ->where('vendor_id', $vendor->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Vendor is already assigned to this year/event.');
        }

        \App\Models\EventVendor::create([
            'event_id' => $event->id,
            'vendor_id' => $vendor->id,
            'college_id' => Auth::user()->college_id,
        ]);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'vendor.assigned',
            'description' => "Vendor '{$vendor->name}' assigned to year/event '{$event->name}'",
        ]);

        return redirect()->back()
            ->with('success', 'Vendor assigned to year/event successfully.');
    }

    public function removeFromEvent(Vendor $vendor, \App\Models\Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($vendor);

        if ($event->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access.');
        }

        $eventVendor = \App\Models\EventVendor::where('event_id', $event->id)
            ->where('vendor_id', $vendor->id)
            ->where('college_id', Auth::user()->college_id)
            ->firstOrFail();

        $eventVendor->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'vendor.unassigned',
            'description' => "Vendor '{$vendor->name}' unassigned from year/event '{$event->name}'",
        ]);

        return redirect()->back()
            ->with('success', 'Vendor unassigned from year/event successfully.');
    }

    private function ensureCollegeScope(Vendor $vendor): void
    {
        if ($vendor->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this vendor.');
        }
    }
}

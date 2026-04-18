@extends('college.layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        {{ $vendor->name }}
    </h1>
    <div class="flex gap-2">
        <a href="{{ route('college.vendors.edit', $vendor) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            Edit
        </a>
        <a href="{{ route('college.vendors.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Vendor Information</h2>
            </div>
            <div class="p-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                    <dt class="font-medium text-slate-500">Name:</dt><dd class="text-slate-800">{{ $vendor->name }}</dd>
                    <dt class="font-medium text-slate-500">Type:</dt><dd><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $vendor->type }}</span></dd>
                    <dt class="font-medium text-slate-500">Contact Email:</dt><dd class="text-slate-800">{{ $vendor->contact_email ?? 'N/A' }}</dd>
                    <dt class="font-medium text-slate-500">Contact Phone:</dt><dd class="text-slate-800">{{ $vendor->contact_phone ?? 'N/A' }}</dd>
                    <dt class="font-medium text-slate-500">Address:</dt><dd class="text-slate-800">{{ $vendor->address ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Assigned years/events</h2>
            </div>
            <div class="p-5">
                @if($vendor->events->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-100 border-b border-border">
                                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Year/Event name</th>
                                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Type</th>
                                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Status</th>
                                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendor->events as $event)
                                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5">
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $event->name }}</td>
                                        <td class="px-4 py-3"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">{{ $event->type }}</span></td>
                                        <td class="px-4 py-3">
                                            @if($event->status === 'Active')
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>
                                            @elseif($event->status === 'Completed')
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">Completed</span>
                                            @else
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-warning-light text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('college.vendors.remove-from-event', [$vendor, $event]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this vendor from the year/event?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-button text-sm font-medium text-red-600 border border-red-200 hover:bg-red-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No years/events assigned.</p>
                @endif
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden sticky top-24">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Assign to year/event</h2>
            </div>
            <div class="p-5">
                <form action="{{ route('college.vendors.assign-event', $vendor) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-slate-700 mb-1">Year/Event <span class="text-red-500">*</span></label>
                        <select id="event_id" name="event_id" required class="w-full rounded-input border @error('event_id') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">Select year/event</option>
                            @foreach(\App\Models\Event::where('college_id', Auth::user()->college_id)->where('status', '!=', 'Draft')->get() as $event)
                                @if(!$vendor->events->contains($event))
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('event_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Assign to year/event</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

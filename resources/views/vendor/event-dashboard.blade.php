@extends('vendor.layouts.app')

@section('title', 'Event Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        </span>
        Event Dashboard
    </h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-primary/5 rounded-card border border-primary/20 shadow-card p-5">
        <p class="text-sm font-medium text-slate-600">Event Name</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ $stats['event_name'] }}</p>
    </div>
    <div class="bg-success-light rounded-card border border-success/20 shadow-card p-5">
        <p class="text-sm font-medium text-slate-600">Event Type</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ $stats['event_type'] }}</p>
    </div>
    <div class="bg-info-light rounded-card border border-info/20 shadow-card p-5">
        <p class="text-sm font-medium text-slate-600">Start Date</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ $stats['start_date'] }}</p>
    </div>
    <div class="bg-warning-light rounded-card border border-warning/20 shadow-card p-5">
        <p class="text-sm font-medium text-slate-600">End Date</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ $stats['end_date'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Event Information</h2>
            </div>
            <div class="p-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <dt class="text-slate-500">Event Name</dt><dd class="font-medium text-slate-900">{{ $event->name }}</dd>
                    <dt class="text-slate-500">Event Type</dt><dd><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $event->type }}</span></dd>
                    <dt class="text-slate-500">College</dt><dd class="text-slate-700">{{ $event->college->name }}</dd>
                    <dt class="text-slate-500">Start Date</dt><dd class="text-slate-700">{{ $event->start_date->format('F d, Y') }}</dd>
                    <dt class="text-slate-500">End Date</dt><dd class="text-slate-700">{{ $event->end_date->format('F d, Y') }}</dd>
                    <dt class="text-slate-500">Status</dt>
                    <dd>
                        @if($event->status === 'Active')
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>
                        @elseif($event->status === 'Completed')
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">Completed</span>
                        @else
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-warning-light text-warning">Draft</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Enabled Modules</h2>
            </div>
            <div class="p-5">
                @if($event->modules->where('is_enabled', true)->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($event->modules->where('is_enabled', true) as $module)
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium bg-success-light text-success">{{ $module->module_name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500">No modules are currently enabled for this event.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/10">
                <h2 class="text-lg font-semibold text-slate-800">Vendor Information</h2>
            </div>
            <div class="p-5">
                <dl class="space-y-2 text-sm">
                    <dt class="text-slate-500">Vendor Name</dt><dd class="font-medium text-slate-900">{{ $credential->vendor->name }}</dd>
                    <dt class="text-slate-500">Vendor Type</dt><dd><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $credential->vendor->type }}</span></dd>
                    <dt class="text-slate-500">Contact Email</dt><dd class="text-slate-700">{{ $credential->vendor->contact_email ?? 'N/A' }}</dd>
                    <dt class="text-slate-500">Contact Phone</dt><dd class="text-slate-700">{{ $credential->vendor->contact_phone ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Quick Stats</h2>
            </div>
            <div class="p-5 space-y-2 text-sm text-slate-700">
                <p><strong>{{ $stats['modules_enabled'] }}</strong> of <strong>{{ $stats['total_modules'] }}</strong> modules enabled</p>
                <p>Event Duration: {{ $event->start_date->diffInDays($event->end_date) + 1 }} days</p>
                <p>College: {{ $event->college->name }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

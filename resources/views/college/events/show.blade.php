@extends('college.layouts.app')

@section('title', 'Year/Event Dashboard')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </span>
        {{ $event->name }}
    </h1>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('college.events.programs.create', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Semester/program
        </a>
        <a href="{{ route('college.events.edit', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
        </a>
        <a href="{{ route('college.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-primary/10 rounded-card border border-primary/20 shadow-card p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Semesters/programs</p>
        <p class="text-2xl font-bold text-slate-800">{{ $event->programs->count() }}</p>
    </div>
    <div class="rounded-card border shadow-card p-5 {{ $event->status === 'Active' ? 'bg-success/10 border-success/20' : ($event->status === 'Completed' ? 'bg-slate-100 border-slate-200' : 'bg-warning/10 border-warning/20') }}">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Status</p>
        <p class="text-lg font-semibold text-slate-800">{{ $event->status }}</p>
    </div>
    <div class="bg-slate-50 rounded-card border border-slate-200 shadow-card p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Owner</p>
        <p class="text-lg font-semibold text-slate-800">College</p>
    </div>
    <div class="bg-slate-50 rounded-card border border-slate-200 shadow-card p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Period</p>
        <p class="text-sm font-medium text-slate-800">{{ $event->start_date->format('M d, Y') }} – {{ $event->end_date->format('M d, Y') }}</p>
    </div>
</div>

<div class="space-y-6">
    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
        <div class="px-5 py-4 border-b border-border bg-primary/5">
            <h2 class="text-lg font-semibold text-slate-800">Year/Event information</h2>
        </div>
        <div class="p-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                <dt class="font-medium text-slate-500">Name:</dt>
                <dd class="text-slate-800">{{ $event->name }}</dd>
                @if($event->description)<dt class="font-medium text-slate-500">Description:</dt>
                <dd class="text-slate-800">{{ $event->description }}</dd>@endif
                @if($event->target_audience)<dt class="font-medium text-slate-500">Target Audience:</dt>
                <dd class="text-slate-800">{{ $event->target_audience }}</dd>@endif
                <dt class="font-medium text-slate-500">Start Date:</dt>
                <dd class="text-slate-800">{{ $event->start_date->format('M d, Y') }}</dd>
                <dt class="font-medium text-slate-500">End Date:</dt>
                <dd class="text-slate-800">{{ $event->end_date->format('M d, Y') }}</dd>
                <dt class="font-medium text-slate-500">Status:</dt>
                <dd>
                    @if($event->status === 'Active')
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>
                    @elseif($event->status === 'Archived')
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">Archived</span>
                    @elseif($event->status === 'Completed')
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">Completed</span>
                    @else
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-warning-light text-warning">Draft</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
        <div class="px-5 py-4 border-b border-border bg-primary/5 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Semesters/programs</h2>
            <a href="{{ route('college.events.programs.create', $event) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-button text-sm font-medium text-white bg-primary hover:bg-primary-hover">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Semester/program
            </a>
        </div>
        <div class="overflow-x-auto">
            @if($event->programs->count() > 0)
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="bg-slate-100 border-b border-border">
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Type</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Duration</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Mode</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Run by</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Semester/Program manager</th>
                        <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->programs as $program)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5">
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $program->name }}</td>
                        <td class="px-5 py-3">@if($program->type)<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $program->type }}</span>@else<span class="text-slate-500">—</span>@endif</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $program->departmentsLabel() ?: '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $program->duration_days }} Days</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $program->mode }}</td>
                        <td class="px-5 py-3"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $program->status === 'Manager_Assigned' ? 'bg-info-light text-info' : ($program->status === 'Completed' ? 'bg-success-light text-success' : 'bg-slate-200 text-slate-700') }}">{{ str_replace('_', ' ', $program->status) }}</span></td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $program->executorLabel() }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $program->oversightManager?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center justify-end gap-2 flex-row">
                                <a href="{{ route('college.events.programs.show', [$event, $program]) }}" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">View</a>
                                <a href="{{ route('college.events.programs.edit', [$event, $program]) }}" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-8 text-center text-slate-500 text-sm">
                No semesters/programs yet. <a href="{{ route('college.events.programs.create', $event) }}" class="text-primary font-medium hover:underline">Add a semester/program</a> and assign who runs it (Vendor or Independent Trainer) and an internal Semester/Program manager.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
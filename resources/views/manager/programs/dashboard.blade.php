@extends('manager.layouts.app')

@section('title', 'Program Dashboard')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        </span>
        {{ $program->name }} Dashboard
    </h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-primary/5 rounded-card border border-primary/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <p class="text-sm font-medium text-slate-600">Status</p>
        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['status'] }}</p>
    </div>
    <div class="bg-success-light rounded-card border border-success/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <p class="text-sm font-medium text-slate-600">Students</p>
        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['students_count'] }}</p>
    </div>
    <div class="bg-info-light rounded-card border border-info/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <p class="text-sm font-medium text-slate-600">Sessions</p>
        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['sessions_count'] }}</p>
    </div>
</div>

<div class="mt-8 bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Program Details</h2>
    </div>
    <div class="p-5 space-y-2 text-sm">
        <p><strong class="text-slate-700">Event:</strong> <span class="text-slate-600">{{ $stats['event_name'] }}</span></p>
        <p><strong class="text-slate-700">Manager:</strong> <span class="text-slate-600">{{ $stats['manager_name'] }}</span></p>
        <p><strong class="text-slate-700">Pending Completion Requests:</strong> <span class="text-slate-600">{{ $stats['pending_completion'] }}</span></p>
    </div>
</div>
@endsection

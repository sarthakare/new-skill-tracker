@extends('college.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        </span>
        Dashboard
    </h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-primary/5 rounded-card border border-primary/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Total Years/Events</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total_events'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-success-light rounded-card border border-success/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Active Years/Events</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['active_events'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-success-light border border-success/30">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-info-light rounded-card border border-info/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Completed Years/Events</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['completed_events'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-info-light border border-info/30">
                <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-warning-light rounded-card border border-warning/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Total Users</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total_users'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-warning-light border border-warning/30">
                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-slate-100 rounded-card border border-slate-200 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Vendors Count</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['vendors_count'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-slate-200">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-slate-100 rounded-card border border-slate-200 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Total Semesters/programs</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['total_programs'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-slate-200">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-primary/5 rounded-card border border-primary/20 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">In-Progress Semesters/programs</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['active_programs'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </span>
        </div>
    </div>
    <div class="bg-red-50 rounded-card border border-red-200 shadow-card p-5 hover:shadow-card-hover transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-600">Pending Completions</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stats['pending_completion_requests'] }}</p>
            </div>
            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-100">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
            </span>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
        <div class="px-5 py-4 border-b border-border bg-primary/5">
            <h2 class="text-lg font-semibold text-slate-800">Quick Actions</h2>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('college.events.create') }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Create Year/Event
                </a>
                <a href="{{ route('college.vendors.create') }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Create Vendor
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('manager.layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </span>
        Daily Report - {{ $program->name }}
    </h1>
    <p class="mt-2 text-slate-600">Add sessions, mark attendance, and generate reports.</p>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add Session</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.sessions.store', $program) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                    <input type="text" name="title" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                    <input type="date" name="session_date" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                    <input type="time" name="end_time" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div><button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Add</button></div>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Sessions</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Title</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Date</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Time</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $session->title }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $session->session_date->format('Y-m-d') }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $session->status }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <a href="{{ route('manager.program.attendance.edit', [$program, $session]) }}" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">Attendance</a>
                                <a href="{{ route('manager.program.attendance.report', [$program, $session]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Attendance Report</a>
                                <a href="{{ route('manager.program.daily.report', [$program, $session]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Daily Report</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">No sessions created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

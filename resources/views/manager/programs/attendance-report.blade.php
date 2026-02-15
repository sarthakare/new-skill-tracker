@extends('manager.layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </span>
        Attendance Report
    </h1>
    <div class="flex gap-2">
        <a href="{{ route('manager.program.sessions.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back to Sessions</a>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover print:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print
        </button>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden printable-report">
    <div class="p-5">
        <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ $program->name }}</h2>
        <p class="text-sm text-slate-600 mb-1"><strong>Session:</strong> {{ $session->title }}</p>
        <p class="text-sm text-slate-600 mb-1"><strong>Date:</strong> {{ $session->session_date->format('F d, Y') }}</p>
        <p class="text-sm text-slate-600 mb-4"><strong>Time:</strong> {{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 summary-row">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Students</p>
                <p class="text-xl font-semibold text-slate-800">{{ $students->count() }}</p>
            </div>
            <div class="rounded-lg border border-success/30 bg-success/10 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Present</p>
                <p class="text-xl font-semibold text-success">{{ $presentCount }}</p>
            </div>
            <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Absent</p>
                <p class="text-xl font-semibold text-red-600">{{ $absentCount }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-border">
                <thead>
                    <tr class="bg-slate-100 border-b border-border">
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">#</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">Student Name</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">Student ID</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">Department</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $record = $attendance[$student->id] ?? null;
                            $status = $record && $record->status === 'present' ? 'Present' : 'Absent';
                        @endphp
                        <tr class="border-b border-border odd:bg-slate-50/50">
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->student_name }}</td>
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->student_identifier ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->department }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status === 'Present' ? 'bg-success/20 text-success' : 'bg-red-100 text-red-700' }}">{{ $status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No students enrolled.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        aside, header, nav, .print\\:hidden, .sidebar, [role="banner"] { display: none !important; }
        body { font-size: 10px; }
        .printable-report { border: 1px solid #cbd5e1; box-shadow: none !important; }
        .summary-row { display: flex !important; gap: 8px; }
        @page { margin: 8mm; }
    }
</style>
@endsection

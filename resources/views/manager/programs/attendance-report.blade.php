@extends('manager.layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </span>
        Attendance Report
    </h1>
    <div class="flex gap-2">
        <a href="{{ route('manager.program.sessions.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back to Daily Report</a>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print
        </button>
    </div>
</div>

{{-- On-screen view --}}
<div class="bg-white rounded-card border border-border shadow-card overflow-hidden print:hidden">
    <div class="p-5">
        <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ $program->name }}</h2>
        <p class="text-sm text-slate-600 mb-1"><strong>Session:</strong> {{ $session->title }}</p>
        <p class="text-sm text-slate-600 mb-1"><strong>Date:</strong> {{ $session->session_date->format('F d, Y') }}</p>
        <p class="text-sm text-slate-600 mb-4"><strong>Time:</strong> {{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
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
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">Roll no.</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2 border-r border-border">Name</th>
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
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->displayRollNumber() ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->displayName() }}</td>
                            <td class="px-4 py-2 text-sm border-r border-border">{{ $student->departmentLabel() }}</td>
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

{{-- Formal print document --}}
<div id="attendance-print-document" class="hidden print:block attendance-formal-doc">
    <div class="attendance-doc-header">
        @include('partials.formal-report-logo')
        <h1 class="attendance-doc-title">ATTENDANCE REPORT</h1>
        @if($program->college)
            <p class="attendance-doc-subtitle">Submitted to {{ $program->college->name }}</p>
        @endif
        <p class="attendance-doc-date">Date: {{ $session->session_date->format('d F Y') }}</p>
    </div>

    <table class="attendance-doc-info-table">
        <tr><td class="attendance-doc-label">Program Name</td><td>{{ $program->name }}</td></tr>
        @if($program->event)
            <tr><td class="attendance-doc-label">Year/Event</td><td>{{ $program->event->name }}</td></tr>
        @endif
        <tr><td class="attendance-doc-label">Session</td><td>{{ $session->title }}</td></tr>
        <tr><td class="attendance-doc-label">Date</td><td>{{ $session->session_date->format('d F Y') }}</td></tr>
        <tr><td class="attendance-doc-label">Time</td><td>{{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</td></tr>
        @if($program->departmentsLabel() !== '')
            <tr><td class="attendance-doc-label">Departments</td><td>{{ $program->departmentsLabel() }}</td></tr>
        @endif
        <tr><td class="attendance-doc-label">Trainer</td><td>{{ $program->executorLabel() }}</td></tr>
        <tr><td class="attendance-doc-label">Total Students</td><td>{{ $students->count() }}</td></tr>
        <tr><td class="attendance-doc-label">Present</td><td>{{ $presentCount }}</td></tr>
        <tr><td class="attendance-doc-label">Absent</td><td>{{ $absentCount }}</td></tr>
    </table>

    <h2 class="attendance-doc-section">Attendance Details</h2>
    <table class="attendance-doc-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Roll no.</th>
                <th>Name</th>
                <th>Department</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                @php
                    $record = $attendance[$student->id] ?? null;
                    $status = $record && $record->status === 'present' ? 'Present' : 'Absent';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->displayRollNumber() ?? '—' }}</td>
                    <td>{{ $student->displayName() }}</td>
                    <td>{{ $student->departmentLabel() }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No students enrolled.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .attendance-formal-doc {
        font-family: 'Times New Roman', Times, serif;
        max-width: 100%;
        padding: 24px;
        margin: 0;
        color: #1e293b;
        border: 1px solid #1e293b;
    }
    .attendance-doc-header {
        text-align: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #1e293b;
    }
    .attendance-doc-title {
        font-size: 22pt;
        font-weight: 700;
        letter-spacing: 0.1em;
        margin: 0 0 8px 0;
    }
    .attendance-doc-subtitle {
        font-size: 11pt;
        margin: 0 0 4px 0;
    }
    .attendance-doc-date {
        font-size: 10pt;
        margin: 0;
    }
    .attendance-doc-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
        font-size: 11pt;
    }
    .attendance-doc-info-table td {
        padding: 6px 12px;
        border: 1px solid #64748b;
        vertical-align: top;
    }
    .attendance-doc-label {
        width: 140px;
        font-weight: 600;
        background: #f8fafc;
    }
    .attendance-doc-section {
        font-size: 14pt;
        font-weight: 700;
        margin: 0 0 16px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #94a3b8;
    }
    .attendance-doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11pt;
    }
    .attendance-doc-table th,
    .attendance-doc-table td {
        padding: 8px 12px;
        border: 1px solid #64748b;
        text-align: left;
    }
    .attendance-doc-table th {
        font-weight: 600;
        background: #f8fafc;
    }
    .attendance-doc-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    @media print {
        html, body {
            overflow: visible !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        body > div, main {
            overflow: visible !important;
            min-height: 0 !important;
            height: auto !important;
            min-width: 100% !important;
        }
        aside, header, nav, .print\:hidden, .sidebar, [role="banner"] { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
        #attendance-print-document {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
            min-height: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }
        .attendance-formal-doc {
            overflow: visible !important;
        }
        @page {
            margin: 5mm;
            size: A4;
        }
        @page {
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9pt;
                font-family: 'Times New Roman', Times, serif;
            }
        }
    }
</style>
@endsection

@extends('student.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-end print:hidden">
            <button type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print / Save PDF
            </button>
        </div>

        {{-- On-screen view --}}
        <div class="space-y-6 print:hidden">
        <div class="bg-white rounded-card border border-slate-200/80 shadow-card p-6 sm:p-8">
            <h1 class="text-2xl font-semibold text-slate-800">Welcome, {{ $user->name }}</h1>
            <p class="mt-2 text-slate-600">
                @if($college)
                    You are signed in as a student at <span class="font-medium text-slate-800">{{ $college->name }}</span>.
                @else
                    Your dashboard is ready. College information will appear here when available.
                @endif
            </p>
            <dl class="mt-6 grid gap-3 sm:grid-cols-2 text-sm">
                <div>
                    <dt class="text-slate-500">Roll number</dt>
                    <dd class="font-medium text-slate-800">{{ $user->roll_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Department</dt>
                    <dd class="font-medium text-slate-800">{{ $user->department?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Mobile</dt>
                    <dd class="font-medium text-slate-800">{{ $user->mobile ?? '—' }}</dd>
                </div>
            </dl>
            <p class="mt-6 text-sm text-slate-500">
                Signed in as {{ $user->email }}
            </p>
        </div>

        <div class="bg-white rounded-card border border-slate-200/80 shadow-card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/80 bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Programs & attendance</h2>
                <p class="text-sm text-slate-500 mt-1">Events and sessions where you are enrolled as a program participant.</p>
            </div>
            <div class="p-6 sm:p-8">
                @forelse($enrollments as $enrollment)
                    @php
                        $program = $enrollment->program;
                        $event = $program?->event;
                        $presentRows = $attendanceByEnrollment->get($enrollment->id, collect());
                        $attendedSessions = $presentRows->map(fn ($row) => $row->session)->filter()->sortBy(function ($s) {
                            return ($s->session_date?->timestamp ?? 0).(string) ($s->start_time ?? '');
                        });
                    @endphp
                    <div class="border border-slate-200/90 rounded-xl p-5 mb-5 last:mb-0 bg-slate-50/50">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Event</p>
                                <p class="text-lg font-semibold text-slate-900">{{ $event?->name ?? '—' }}</p>
                                @if($event && ($event->start_date || $event->end_date))
                                    <p class="text-sm text-slate-500 mt-1">
                                        @if($event->start_date){{ $event->start_date->format('M j, Y') }}@endif
                                        @if($event->start_date && $event->end_date) – @endif
                                        @if($event->end_date){{ $event->end_date->format('M j, Y') }}@endif
                                    </p>
                                @endif
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Program</p>
                                <p class="font-medium text-slate-800">{{ $program?->name ?? '—' }}</p>
                                @if($program?->type)
                                    <span class="inline-flex mt-1 rounded-full px-2 py-0.5 text-xs font-medium bg-info-light text-info">{{ $program->type }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Sessions attended ({{ $attendedSessions->count() }})</p>
                            @if($attendedSessions->isEmpty())
                                <p class="text-sm text-slate-500 italic">No attendance recorded as present yet.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach($attendedSessions as $sess)
                                        <li class="flex flex-wrap items-baseline gap-x-3 gap-y-1 text-sm text-slate-700 border-l-2 border-primary/40 pl-3">
                                            <span class="font-medium text-slate-900">{{ $sess->title ?: 'Session' }}</span>
                                            @if($sess->session_date)
                                                <span class="text-slate-500">{{ $sess->session_date->format('M j, Y') }}</span>
                                            @endif
                                            @if($sess->start_time && $sess->end_time)
                                                <span class="text-slate-500">{{ substr((string) $sess->start_time, 0, 5) }}–{{ substr((string) $sess->end_time, 0, 5) }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="rounded-lg border border-amber-200/80 bg-amber-50/80 px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-900/80 mb-1">Program manager remarks</p>
                            @if($enrollment->manager_remarks)
                                <p class="text-sm text-slate-800 whitespace-pre-wrap">{{ $enrollment->manager_remarks }}</p>
                            @else
                                <p class="text-sm text-slate-500 italic">No remarks have been added for you in this program yet.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-600">
                        You are not listed on any program yet. When a program manager adds you to a program using your registered account, your events, attendance, and remarks will appear here.
                    </p>
                @endforelse
            </div>
        </div>
        </div>
    </div>

    {{-- Formal print document --}}
    <div id="student-report-print-document" class="hidden print:block student-report-formal-doc">
        <div class="student-report-doc-header">
            <h1 class="student-report-doc-title">STUDENT REPORT</h1>
            @if($college)
                <p class="student-report-doc-subtitle">Issued by {{ $college->name }}</p>
            @endif
            <p class="student-report-doc-date">Date: {{ now()->format('d F Y') }}</p>
        </div>

        <table class="student-report-doc-info-table">
            <tr><td class="student-report-doc-label">Student Name</td><td>{{ $user->name }}</td></tr>
            <tr><td class="student-report-doc-label">Roll Number</td><td>{{ $user->roll_number ?? '—' }}</td></tr>
            <tr><td class="student-report-doc-label">Email</td><td>{{ $user->email }}</td></tr>
            <tr><td class="student-report-doc-label">Department</td><td>{{ $user->department?->name ?? '—' }}</td></tr>
            <tr><td class="student-report-doc-label">Mobile</td><td>{{ $user->mobile ?? '—' }}</td></tr>
            <tr><td class="student-report-doc-label">Programs Enrolled</td><td>{{ $enrollments->count() }}</td></tr>
        </table>

        <h2 class="student-report-doc-section">Program Summary</h2>
        <table class="student-report-doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Program</th>
                    <th>Type</th>
                    <th>Sessions Attended</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $index => $enrollment)
                    @php
                        $program = $enrollment->program;
                        $event = $program?->event;
                        $presentRows = $attendanceByEnrollment->get($enrollment->id, collect());
                        $attendedSessions = $presentRows->map(fn ($row) => $row->session)->filter()->sortBy(function ($s) {
                            return ($s->session_date?->timestamp ?? 0).(string) ($s->start_time ?? '');
                        });
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $event?->name ?? '—' }}</td>
                        <td>{{ $program?->name ?? '—' }}</td>
                        <td>{{ $program?->type ?? '—' }}</td>
                        <td>{{ $attendedSessions->count() }}</td>
                        <td>{{ $enrollment->manager_remarks ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No programs assigned yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        .student-report-formal-doc {
            font-family: 'Times New Roman', Times, serif;
            max-width: 100%;
            padding: 24px;
            margin: 0;
            color: #1e293b;
            border: 1px solid #1e293b;
        }
        .student-report-doc-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #1e293b;
        }
        .student-report-doc-title {
            font-size: 22pt;
            font-weight: 700;
            letter-spacing: 0.1em;
            margin: 0 0 8px 0;
        }
        .student-report-doc-subtitle {
            font-size: 11pt;
            margin: 0 0 4px 0;
        }
        .student-report-doc-date {
            font-size: 10pt;
            margin: 0;
        }
        .student-report-doc-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11pt;
        }
        .student-report-doc-info-table td {
            padding: 6px 12px;
            border: 1px solid #64748b;
            vertical-align: top;
        }
        .student-report-doc-label {
            width: 170px;
            font-weight: 600;
            background: #f8fafc;
        }
        .student-report-doc-section {
            font-size: 14pt;
            font-weight: 700;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #94a3b8;
        }
        .student-report-doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
        }
        .student-report-doc-table th,
        .student-report-doc-table td {
            padding: 8px 12px;
            border: 1px solid #64748b;
            text-align: left;
            vertical-align: top;
        }
        .student-report-doc-table th {
            font-weight: 600;
            background: #f8fafc;
        }
        .student-report-doc-table tbody tr:nth-child(even) {
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
            #student-report-print-document {
                display: block !important;
                overflow: visible !important;
                height: auto !important;
                min-height: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
            .student-report-formal-doc {
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

@extends('student.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8 print:hidden">
        {{-- Hero --}}
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white via-white to-primary-light/30 ring-1 ring-slate-200/90 shadow-[0_8px_32px_-12px_rgba(67,56,202,0.22)]">
            <div class="pointer-events-none absolute -right-24 -top-24 h-48 w-48 rounded-full bg-primary/15 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-16 -left-16 h-40 w-40 rounded-full bg-primary-light/40 blur-3xl" aria-hidden="true"></div>
            <div class="relative p-6 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex min-w-0 gap-4 sm:gap-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary-hover text-lg font-bold text-white shadow-lg shadow-primary/35 sm:h-16 sm:w-16 sm:text-xl">
                            {{ mb_strtoupper(mb_substr($user->name ?? '?', 0, 1, 'UTF-8'), 'UTF-8') }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-primary">Student hub</p>
                            <h1 class="mt-1.5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Welcome back, {{ $user->name }}</h1>
                            <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base">
                                @if($college)
                                    You are enrolled at <span class="font-semibold text-slate-800">{{ $college->name }}</span>.
                                @else
                                    Your profile is ready. College details will appear when they are linked to your account.
                                @endif
                            </p>
                            <p class="mt-3 truncate text-xs text-slate-500 sm:text-sm">{{ $user->email }}</p>
                        </div>
                    </div>
                    <button type="button"
                        onclick="window.print()"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white/90 px-4 py-2.5 text-sm font-semibold text-primary shadow-sm ring-1 ring-primary/15 transition hover:bg-white hover:ring-primary/25">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print report
                    </button>
                </div>
                <div class="relative mt-8 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl bg-white/75 p-4 shadow-sm ring-1 ring-slate-200/60 backdrop-blur-sm transition hover:shadow-md">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Roll number</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->roll_number ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl bg-white/75 p-4 shadow-sm ring-1 ring-slate-200/60 backdrop-blur-sm transition hover:shadow-md">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Department</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->department?->name ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl bg-white/75 p-4 shadow-sm ring-1 ring-slate-200/60 backdrop-blur-sm transition hover:shadow-md">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Mobile</p>
                        <p class="mt-1 text-base font-semibold text-slate-900">{{ $user->mobile ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Code runner --}}
        <section class="overflow-hidden rounded-2xl bg-white shadow-[0_4px_28px_-8px_rgba(15,23,42,0.14)] ring-1 ring-slate-200/90">
            <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/90 px-5 py-4 sm:px-6">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </span>
                <h2 class="text-base font-bold text-slate-900">Run code</h2>
            </div>
            <div class="space-y-5 p-5 sm:p-6">
                <div>
                    <label for="code-run-language" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Language</label>
                    @php
                        $judge0Languages = [
                            ['id' => 45, 'name' => 'Assembly (NASM 2.14.02)'],
                            ['id' => 46, 'name' => 'Bash (5.0.0)'],
                            ['id' => 47, 'name' => 'Basic (FBC 1.07.1)'],
                            ['id' => 75, 'name' => 'C (Clang 7.0.1)'],
                            ['id' => 76, 'name' => 'C++ (Clang 7.0.1)'],
                            ['id' => 48, 'name' => 'C (GCC 7.4.0)'],
                            ['id' => 52, 'name' => 'C++ (GCC 7.4.0)'],
                            ['id' => 49, 'name' => 'C (GCC 8.3.0)'],
                            ['id' => 53, 'name' => 'C++ (GCC 8.3.0)'],
                            ['id' => 50, 'name' => 'C (GCC 9.2.0)'],
                            ['id' => 54, 'name' => 'C++ (GCC 9.2.0)'],
                            ['id' => 86, 'name' => 'Clojure (1.10.1)'],
                            ['id' => 51, 'name' => 'C# (Mono 6.6.0.161)'],
                            ['id' => 77, 'name' => 'COBOL (GnuCOBOL 2.2)'],
                            ['id' => 55, 'name' => 'Common Lisp (SBCL 2.0.0)'],
                            ['id' => 56, 'name' => 'D (DMD 2.089.1)'],
                            ['id' => 57, 'name' => 'Elixir (1.9.4)'],
                            ['id' => 58, 'name' => 'Erlang (OTP 22.2)'],
                            ['id' => 44, 'name' => 'Executable'],
                            ['id' => 87, 'name' => 'F# (.NET Core SDK 3.1.202)'],
                            ['id' => 59, 'name' => 'Fortran (GFortran 9.2.0)'],
                            ['id' => 60, 'name' => 'Go (1.13.5)'],
                            ['id' => 88, 'name' => 'Groovy (3.0.3)'],
                            ['id' => 61, 'name' => 'Haskell (GHC 8.8.1)'],
                            ['id' => 62, 'name' => 'Java (OpenJDK 13.0.1)'],
                            ['id' => 63, 'name' => 'JavaScript (Node.js 12.14.0)'],
                            ['id' => 78, 'name' => 'Kotlin (1.3.70)'],
                            ['id' => 64, 'name' => 'Lua (5.3.5)'],
                            ['id' => 89, 'name' => 'Multi-file program'],
                            ['id' => 79, 'name' => 'Objective-C (Clang 7.0.1)'],
                            ['id' => 65, 'name' => 'OCaml (4.09.0)'],
                            ['id' => 66, 'name' => 'Octave (5.1.0)'],
                            ['id' => 67, 'name' => 'Pascal (FPC 3.0.4)'],
                            ['id' => 85, 'name' => 'Perl (5.28.1)'],
                            ['id' => 68, 'name' => 'PHP (7.4.1)'],
                            ['id' => 43, 'name' => 'Plain Text'],
                            ['id' => 69, 'name' => 'Prolog (GNU Prolog 1.4.5)'],
                            ['id' => 70, 'name' => 'Python (2.7.17)'],
                            ['id' => 71, 'name' => 'Python (3.8.1)'],
                            ['id' => 80, 'name' => 'R (4.0.0)'],
                            ['id' => 72, 'name' => 'Ruby (2.7.0)'],
                            ['id' => 73, 'name' => 'Rust (1.40.0)'],
                            ['id' => 81, 'name' => 'Scala (2.13.2)'],
                            ['id' => 82, 'name' => 'SQL (SQLite 3.27.2)'],
                            ['id' => 83, 'name' => 'Swift (5.2.3)'],
                            ['id' => 74, 'name' => 'TypeScript (3.7.4)'],
                            ['id' => 84, 'name' => 'Visual Basic.Net (vbnc 0.0.0.5943)'],
                        ];
                    @endphp
                    <select id="code-run-language" class="w-full rounded-xl border-0 bg-slate-100/90 px-4 py-3 text-sm font-medium text-slate-800 shadow-inner ring-1 ring-slate-200/80 focus:outline-none focus:ring-2 focus:ring-primary/35">
                        @foreach ($judge0Languages as $lang)
                            <option value="{{ $lang['id'] }}" @selected($lang['id'] === 71)>{{ $lang['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="code-run-source" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Your code</label>
                    <textarea id="code-run-source" rows="12" class="w-full resize-y rounded-xl border-0 bg-slate-900 px-4 py-3 font-mono text-sm leading-relaxed text-slate-100 shadow-inner ring-1 ring-slate-700/80 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary/50" placeholder="Paste your code here…" required spellcheck="false"></textarea>
                </div>
                <div>
                    <button type="button" id="code-run-submit" class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 transition hover:bg-primary-hover disabled:pointer-events-none disabled:opacity-55">
                        <span id="code-run-submit-label">Run</span>
                        <svg id="code-run-spinner" class="hidden h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
                <div id="code-run-result" class="hidden rounded-xl bg-slate-100/80 p-4 text-sm ring-1 ring-slate-200/80 sm:p-5">
                    <p id="code-run-status" class="mb-4 text-sm font-semibold"></p>
                    <div id="code-run-sections" class="space-y-4"></div>
                </div>
                <p id="code-run-error" class="hidden rounded-xl border border-red-200/90 bg-red-50/95 px-4 py-3 text-sm font-medium text-red-800 shadow-sm"></p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl bg-white shadow-[0_4px_28px_-8px_rgba(15,23,42,0.1)] ring-1 ring-slate-200/90">
            <div class="border-b border-slate-100 bg-gradient-to-r from-primary/8 via-primary/4 to-transparent px-5 py-4 sm:px-6 sm:py-5">
                <h2 class="text-base font-bold text-slate-900 sm:text-lg">Semesters/programs & attendance</h2>
                <p class="mt-1 text-sm text-slate-500">Years/events and sessions where you are enrolled as a semester/program participant.</p>
            </div>
            <div class="p-5 sm:p-6">
                @forelse($enrollments as $enrollment)
                    @php
                        $program = $enrollment->program;
                        $event = $program?->event;
                        $presentRows = $attendanceByEnrollment->get($enrollment->id, collect());
                        $attendedSessions = $presentRows->map(fn ($row) => $row->session)->filter()->sortBy(function ($s) {
                            return ($s->session_date?->timestamp ?? 0).(string) ($s->start_time ?? '');
                        });
                    @endphp
                    <article class="group mb-5 last:mb-0 overflow-hidden rounded-2xl bg-gradient-to-b from-slate-50/90 to-white ring-1 ring-slate-200/80 shadow-sm transition hover:shadow-md hover:ring-slate-300/90">
                        <div class="flex flex-col gap-4 border-b border-slate-100/90 bg-white/60 px-5 py-4 sm:flex-row sm:items-start sm:justify-between sm:px-6">
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-primary/80">Year/Event</p>
                                <p class="mt-1 text-lg font-bold leading-snug text-slate-900">{{ $event?->name ?? '—' }}</p>
                                @if($event && ($event->start_date || $event->end_date))
                                    <p class="mt-1.5 flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @if($event->start_date){{ $event->start_date->format('M j, Y') }}@endif
                                            @if($event->start_date && $event->end_date)–@endif
                                            @if($event->end_date){{ $event->end_date->format('M j, Y') }}@endif
                                        </span>
                                    </p>
                                @endif
                            </div>
                            <div class="shrink-0 text-left sm:text-right">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Semester/program</p>
                                <p class="mt-1 font-semibold text-slate-800">{{ $program?->name ?? '—' }}</p>
                                @if($program?->type)
                                    <span class="mt-2 inline-flex rounded-full bg-info-light px-2.5 py-0.5 text-xs font-semibold text-info ring-1 ring-info/15">{{ $program->type }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="px-5 py-4 sm:px-6">
                            <p class="text-sm font-semibold text-slate-800">Sessions attended <span class="font-normal text-slate-500">({{ $attendedSessions->count() }})</span></p>
                            @if($attendedSessions->isEmpty())
                                <p class="mt-2 text-sm text-slate-500">No attendance recorded as present yet.</p>
                            @else
                                <ul class="mt-3 space-y-2">
                                    @foreach($attendedSessions as $sess)
                                        <li class="flex flex-wrap items-baseline gap-x-3 gap-y-1 rounded-xl bg-white/90 px-3 py-2.5 text-sm text-slate-700 ring-1 ring-slate-200/70">
                                            <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700" aria-hidden="true">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </span>
                                            <span class="font-medium text-slate-900">{{ $sess->title ?: 'Session' }}</span>
                                            @if($sess->session_date)
                                                <span class="text-slate-500">{{ $sess->session_date->format('M j, Y') }}</span>
                                            @endif
                                            @if($sess->start_time && $sess->end_time)
                                                <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600">{{ substr((string) $sess->start_time, 0, 5) }}–{{ substr((string) $sess->end_time, 0, 5) }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="mx-5 mb-5 rounded-xl border border-amber-200/70 bg-gradient-to-br from-amber-50/95 to-amber-50/50 px-4 py-3.5 sm:mx-6">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-amber-900/75">Semester/Program manager remarks</p>
                            @if($enrollment->manager_remarks)
                                <p class="mt-2 text-sm leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $enrollment->manager_remarks }}</p>
                            @else
                                <p class="mt-2 text-sm text-slate-600">No remarks have been added for you in this semester/program yet.</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-800">No semesters/programs yet</p>
                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">When a semester/program manager adds you with your registered account, your years/events, attendance, and remarks will show up here.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Formal print document --}}
    <div id="student-report-print-document" class="hidden print:block student-report-formal-doc">
        <div class="student-report-doc-header">
            @include('partials.formal-report-logo')
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
            <tr><td class="student-report-doc-label">Semesters/programs enrolled</td><td>{{ $enrollments->count() }}</td></tr>
        </table>

        <h2 class="student-report-doc-section">Semester/Program summary</h2>
        <table class="student-report-doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Year/Event</th>
                    <th>Semester/program</th>
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
                        <td colspan="6" class="text-center">No semesters/programs assigned yet.</td>
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

    <script>
        (function () {
            const runUrl = @json(route('student.code-run'));
            const btn = document.getElementById('code-run-submit');
            const spinner = document.getElementById('code-run-spinner');
            const label = document.getElementById('code-run-submit-label');
            const sourceEl = document.getElementById('code-run-source');
            const langEl = document.getElementById('code-run-language');
            const resultBox = document.getElementById('code-run-result');
            const statusEl = document.getElementById('code-run-status');
            const sectionsEl = document.getElementById('code-run-sections');
            const errEl = document.getElementById('code-run-error');

            function csrfToken() {
                const m = document.querySelector('meta[name="csrf-token"]');
                return m ? m.getAttribute('content') : '';
            }

            function setLoading(loading) {
                btn.disabled = loading;
                spinner.classList.toggle('hidden', !loading);
                label.textContent = loading ? 'Running…' : 'Run';
            }

            function showError(msg) {
                errEl.textContent = msg;
                errEl.classList.remove('hidden');
                resultBox.classList.add('hidden');
            }

            function hideError() {
                errEl.classList.add('hidden');
                errEl.textContent = '';
            }

            function appendSection(title, text, tone) {
                if (text == null || String(text).trim() === '') return;
                const wrap = document.createElement('div');
                const h = document.createElement('p');
                h.className = 'mb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-500';
                h.textContent = title;
                const pre = document.createElement('pre');
                pre.className = 'text-xs font-mono whitespace-pre-wrap break-words rounded-lg px-3 py-2.5 ring-1 ' + (tone === 'err'
                    ? 'bg-rose-950 text-rose-50 ring-rose-900/60'
                    : 'bg-slate-900 text-slate-100 ring-slate-700/90');
                pre.textContent = text;
                wrap.appendChild(h);
                wrap.appendChild(pre);
                sectionsEl.appendChild(wrap);
            }

            btn.addEventListener('click', async function () {
                hideError();
                const source_code = (sourceEl.value || '').trim();
                if (!source_code) {
                    showError('Please paste some code first.');
                    return;
                }

                setLoading(true);
                sectionsEl.innerHTML = '';

                try {
                    const res = await fetch(runUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            source_code,
                            language_id: parseInt(langEl.value, 10),
                            stdin: '',
                        }),
                    });
                    const data = await res.json().catch(function () { return {}; });

                    if (!res.ok || !data.ok) {
                        showError(data.error || ('Request failed (' + res.status + ')'));
                        setLoading(false);
                        return;
                    }

                    const st = data.status || {};
                    const desc = st.description || st.message || 'Finished';
                    const sid = st.id;
                    statusEl.textContent = desc;
                    statusEl.className = 'mb-4 inline-flex w-fit max-w-full items-center rounded-full px-3.5 py-1.5 text-sm font-semibold ring-1 ';
                    if (sid === 3) {
                        statusEl.className += 'bg-emerald-100 text-emerald-900 ring-emerald-200/90';
                    } else if (sid != null && sid > 3) {
                        statusEl.className += 'bg-rose-100 text-rose-900 ring-rose-200/90';
                    } else {
                        statusEl.className += 'bg-slate-100 text-slate-800 ring-slate-200/90';
                    }

                    if (data.message && String(data.message).trim() !== '') {
                        appendSection('Message', data.message, 'err');
                    }
                    appendSection('Output (stdout)', data.stdout, 'out');
                    appendSection('Errors (stderr)', data.stderr, 'err');
                    appendSection('Compiler output', data.compile_output, 'err');

                    const meta = [];
                    if (data.time != null && data.time !== '') meta.push('Time: ' + data.time + ' s');
                    if (data.memory != null && data.memory !== '') meta.push('Memory: ' + data.memory + ' KB');
                    if (meta.length) {
                        const p = document.createElement('p');
                        p.className = 'mt-1 text-xs font-medium text-slate-500';
                        p.textContent = meta.join(' · ');
                        sectionsEl.appendChild(p);
                    }

                    resultBox.classList.remove('hidden');
                } catch (e) {
                    showError('Something went wrong. Check your connection and try again.');
                } finally {
                    setLoading(false);
                }
            });
        })();
    </script>
@endsection

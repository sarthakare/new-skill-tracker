@extends('student.layouts.app')

@section('title', 'Dashboard')
@section('main_width_class', 'w-full')

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

        <section class="overflow-hidden rounded-2xl bg-white shadow-[0_4px_28px_-8px_rgba(15,23,42,0.1)] ring-1 ring-slate-200/90">
            <div class="border-b border-slate-100 bg-gradient-to-r from-primary/8 via-primary/4 to-transparent px-5 py-4 sm:px-6 sm:py-5">
                <h2 class="text-base font-bold text-slate-900 sm:text-lg">Subjects/programs & attendance</h2>
                <p class="mt-1 text-sm text-slate-500">Click a subject/program to open syllabus, attendance, assignments, and remarks. Each row is a separate enrollment.</p>
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
                        $expandThisEnrollment = $activeAssignment && $program && (int) $activeAssignment->programId() === (int) $program->id;
                    @endphp
                    <details class="group mb-4 last:mb-0 overflow-hidden rounded-2xl bg-gradient-to-b from-slate-50/90 to-white ring-1 ring-slate-200/80 shadow-sm open:shadow-md open:ring-slate-300/90" @if($expandThisEnrollment) open @endif>
                        <summary class="list-none cursor-pointer select-none border-b border-slate-100/90 bg-white/60 px-4 py-3.5 transition hover:bg-white/90 sm:px-5 sm:py-4 [&::-webkit-details-marker]:hidden">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 ring-1 ring-slate-200/80 transition group-open:bg-primary/10 group-open:text-primary group-open:ring-primary/25" aria-hidden="true">
                                    <svg class="h-4 w-4 shrink-0 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                                <div class="flex min-w-0 flex-1 flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-6">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-primary/80">Year/Semester/Event</p>
                                        <p class="mt-1 text-base font-bold leading-snug text-slate-900 sm:text-lg">{{ $event?->name ?? '—' }}</p>
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
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Subject/program</p>
                                        <p class="mt-1 font-semibold text-slate-800">{{ $program?->name ?? '—' }}</p>
                                        @if($program?->type)
                                            <span class="mt-2 inline-flex rounded-full bg-info-light px-2.5 py-0.5 text-xs font-semibold text-info ring-1 ring-info/15">{{ $program->type }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </summary>

                        <div class="border-t border-slate-100/80 bg-white/50">
                        @php
                            $syllabusTopics = $syllabusTopicsByProgram->get($program?->id, collect());
                            $assignmentLinks = collect();
                            foreach ($syllabusTopics as $syllabusTopic) {
                                foreach ($syllabusTopic->subtopics as $st) {
                                    foreach ($st->assignments as $asg) {
                                        $assignmentLinks->push([
                                            'assignment' => $asg,
                                            'topic' => $syllabusTopic,
                                            'subtopic' => $st,
                                        ]);
                                    }
                                }
                            }
                            $programSubmissions = $submissionsByProgramId->get($program?->id, collect());
                            $submittedAssignmentIds = $programSubmissions
                                ->pluck('syllabus_assignment_id')
                                ->filter()
                                ->map(fn ($id) => (int) $id)
                                ->unique();
                            $totalAssignments = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'assignment')->count();
                            $totalProblems = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'problem')->count();
                            $totalQuizzes = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'quiz')->count();
                            $attemptedAssignments = $assignmentLinks
                                ->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'assignment')
                                ->pluck('assignment.id')
                                ->map(fn ($id) => (int) $id)
                                ->filter(fn ($id) => $submittedAssignmentIds->contains($id))
                                ->count();
                            $attemptedProblems = $assignmentLinks
                                ->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'problem')
                                ->pluck('assignment.id')
                                ->map(fn ($id) => (int) $id)
                                ->filter(fn ($id) => $submittedAssignmentIds->contains($id))
                                ->count();
                            $attemptedQuizzes = $assignmentLinks
                                ->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'quiz')
                                ->pluck('assignment.id')
                                ->map(fn ($id) => (int) $id)
                                ->filter(fn ($id) => $submittedAssignmentIds->contains($id))
                                ->count();
                            $assignmentItems = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'assignment')->values();
                            $problemItems = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'problem')->values();
                            $quizItems = $assignmentLinks->filter(fn ($row) => ($row['assignment']->type ?? 'assignment') === 'quiz')->values();
                        @endphp

                        <div class="px-5 py-4 sm:px-6">
                            <p class="text-sm font-semibold text-slate-800">Stats</p>
                            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                <div class="rounded-xl bg-white px-3 py-2 ring-1 ring-slate-200/80">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Sessions attended</p>
                                    <p class="mt-1 text-lg font-bold tabular-nums text-slate-900">{{ $attendedSessions->count() }}</p>
                                </div>
                                <div class="rounded-xl bg-white px-3 py-2 ring-1 ring-slate-200/80">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Assignments</p>
                                    <p class="mt-1 text-lg font-bold tabular-nums text-slate-900">{{ $attemptedAssignments }}/{{ $totalAssignments }}</p>
                                </div>
                                <div class="rounded-xl bg-white px-3 py-2 ring-1 ring-slate-200/80">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Problems</p>
                                    <p class="mt-1 text-lg font-bold tabular-nums text-slate-900">{{ $attemptedProblems }}/{{ $totalProblems }}</p>
                                </div>
                                <div class="rounded-xl bg-white px-3 py-2 ring-1 ring-slate-200/80">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Quizzes</p>
                                    <p class="mt-1 text-lg font-bold tabular-nums text-slate-900">{{ $attemptedQuizzes }}/{{ $totalQuizzes }}</p>
                                </div>
                            </div>

                            @if($attendedSessions->isNotEmpty())
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

                        @if($syllabusTopics->isNotEmpty())
                            <div class="border-t border-slate-100/90 px-5 py-4 sm:px-6" @if($assignmentLinks->isNotEmpty()) id="program-{{ $program->id }}-assignments" @endif>
                                <p class="text-sm font-semibold text-slate-800">Syllabus</p>
                                <p class="mt-1 text-xs text-slate-500">Course outline for this subject/program. Use the type buttons to jump to grouped lists of assignments, problems, and quizzes with solved status.</p>
                                @if($assignmentLinks->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        @if($totalAssignments > 0)
                                            <a href="#program-{{ $program->id }}-assignment-items" class="inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-sm ring-1 ring-red-700/30 transition hover:bg-red-700">
                                                Assignments ({{ $totalAssignments }})
                                            </a>
                                        @endif
                                        @if($totalProblems > 0)
                                            <a href="#program-{{ $program->id }}-problem-items" class="inline-flex items-center gap-1 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-sm ring-1 ring-amber-700/30 transition hover:bg-amber-600">
                                                Problems ({{ $totalProblems }})
                                            </a>
                                        @endif
                                        @if($totalQuizzes > 0)
                                            <a href="#program-{{ $program->id }}-quiz-items" class="inline-flex items-center gap-1 rounded-md bg-sky-600 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-sm ring-1 ring-sky-700/30 transition hover:bg-sky-700">
                                                Quizzes ({{ $totalQuizzes }})
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                <ol class="mt-3 list-decimal space-y-4 pl-5 text-sm text-slate-800 marker:font-semibold">
                                    @foreach($syllabusTopics as $topic)
                                        <li class="pl-1">
                                            <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                                                <span class="font-medium text-slate-900">{{ $topic->title }}</span>
                                                @if($topic->scheduled_date || $topic->scheduled_time)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-200/90 px-2 py-0.5 text-xs font-medium text-slate-700">
                                                        <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        {{ trim(($topic->scheduled_date?->format('M j, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr((string) $topic->scheduled_time, 0, 5) : '')) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($topic->subtopics->isNotEmpty())
                                                <ul class="mt-2 list-none space-y-2 pl-0 text-sm text-slate-700">
                                                    @foreach($topic->subtopics as $subtopic)
                                                        <li class="flex w-full flex-wrap items-center gap-x-3 gap-y-2 rounded-lg bg-slate-50/80 px-2 py-1.5">
                                                            <div class="min-w-0 flex-1 pr-2 text-left">
                                                                <span class="text-slate-800">{{ $subtopic->title }}</span>
                                                            </div>
                                                            <div class="ml-auto flex min-w-0 shrink-0 flex-wrap items-center justify-end gap-2 sm:gap-2.5">
                                                                @if($subtopic->scheduled_date || $subtopic->scheduled_time)
                                                                    <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 whitespace-nowrap">
                                                                        {{ trim(($subtopic->scheduled_date?->format('M j, Y') ?? '') . ' ' . ($subtopic->scheduled_time ? substr((string) $subtopic->scheduled_time, 0, 5) : '')) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        @if($assignmentLinks->isNotEmpty())
                            <div class="border-t border-slate-100/90 px-5 py-4 sm:px-6 space-y-4">
                                @if($assignmentItems->isNotEmpty())
                                    <div id="program-{{ $program->id }}-assignment-items" class="rounded-xl border border-red-200/70 bg-red-50/40 p-3">
                                        <p class="text-xs font-bold uppercase tracking-wider text-red-700">Assignments ({{ $assignmentItems->count() }})</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach($assignmentItems as $row)
                                                @php $asg = $row['assignment']; $solved = $submittedAssignmentIds->contains((int) $asg->id); @endphp
                                                <li class="flex flex-wrap items-center gap-2 rounded-lg bg-white px-3 py-2 ring-1 ring-red-100/80">
                                                    <span class="font-medium text-slate-900">{{ $asg->title }}</span>
                                                    <span class="text-xs text-slate-500">{{ $row['topic']->title }} - {{ $row['subtopic']->title }}</span>
                                                    @if($solved)
                                                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Solved</span>
                                                    @endif
                                                    <a href="{{ route('student.dashboard', ['assignment' => $asg->id]) }}#student-run-code" class="ml-auto inline-flex items-center rounded-md bg-red-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-red-700 {{ $activeAssignment && (int) $activeAssignment->id === (int) $asg->id ? 'ring-2 ring-red-300 ring-offset-1' : '' }}">Open</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if($problemItems->isNotEmpty())
                                    <div id="program-{{ $program->id }}-problem-items" class="rounded-xl border border-amber-200/70 bg-amber-50/40 p-3">
                                        <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Problems ({{ $problemItems->count() }})</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach($problemItems as $row)
                                                @php $asg = $row['assignment']; $solved = $submittedAssignmentIds->contains((int) $asg->id); @endphp
                                                <li class="flex flex-wrap items-center gap-2 rounded-lg bg-white px-3 py-2 ring-1 ring-amber-100/80">
                                                    <span class="font-medium text-slate-900">{{ $asg->title }}</span>
                                                    <span class="text-xs text-slate-500">{{ $row['topic']->title }} - {{ $row['subtopic']->title }}</span>
                                                    @if($solved)
                                                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Solved</span>
                                                    @endif
                                                    <a href="{{ route('student.dashboard', ['assignment' => $asg->id]) }}#student-run-code" class="ml-auto inline-flex items-center rounded-md bg-amber-500 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-amber-600 {{ $activeAssignment && (int) $activeAssignment->id === (int) $asg->id ? 'ring-2 ring-amber-300 ring-offset-1' : '' }}">Open</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if($quizItems->isNotEmpty())
                                    <div id="program-{{ $program->id }}-quiz-items" class="rounded-xl border border-sky-200/70 bg-sky-50/40 p-3">
                                        <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Quizzes ({{ $quizItems->count() }})</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach($quizItems as $row)
                                                @php $asg = $row['assignment']; $solved = $submittedAssignmentIds->contains((int) $asg->id); @endphp
                                                <li class="flex flex-wrap items-center gap-2 rounded-lg bg-white px-3 py-2 ring-1 ring-sky-100/80">
                                                    <span class="font-medium text-slate-900">{{ $asg->title }}</span>
                                                    <span class="text-xs text-slate-500">{{ $row['topic']->title }} - {{ $row['subtopic']->title }}</span>
                                                    @if($solved)
                                                        <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Solved</span>
                                                    @endif
                                                    <a href="{{ route('student.dashboard', ['assignment' => $asg->id]) }}#student-run-code" class="ml-auto inline-flex items-center rounded-md bg-sky-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-sky-700 {{ $activeAssignment && (int) $activeAssignment->id === (int) $asg->id ? 'ring-2 ring-sky-300 ring-offset-1' : '' }}">Open</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($assignmentLinks->isNotEmpty() && $activeAssignment && $program && (int) $activeAssignment->programId() === (int) $program->id)
                            <div class="border-t border-slate-100/90 px-5 py-4 sm:px-6">
                                    <section id="student-run-code" class="mt-0 overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-[0_4px_28px_-8px_rgba(15,23,42,0.12)] scroll-mt-6">
                                        @php
                                            $activeType = $activeAssignment->type ?? 'assignment';
                                            $activeTypeLabel = $activeType === 'problem' ? 'Problem' : ($activeType === 'quiz' ? 'Quiz' : 'Assignment');
                                            $activeTypeLowerLabel = strtolower($activeTypeLabel);
                                        @endphp
                                        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/90 px-4 py-3 sm:px-5">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                </svg>
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <h2 class="text-sm font-bold text-slate-900 sm:text-base">Run code</h2>
                                                <p class="mt-0.5 text-xs text-slate-600">Languages match what your instructor allowed for this {{ $activeTypeLowerLabel }}.</p>
                                            </div>
                                        </div>
                                        <div class="space-y-4 p-4 sm:p-5">
                                            <div class="rounded-xl border border-primary/20 bg-primary/5 px-3 py-3 sm:px-4">
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] font-bold uppercase tracking-wider text-primary/90">{{ $activeTypeLabel }}</p>
                                                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $activeAssignment->title }}</p>
                                                        <p class="mt-1 text-xs text-slate-600">
                                                            @if($activeAssignment->languages_supported && count($activeAssignment->languages_supported) > 0)
                                                                <span>{{ count($codeRunnerLanguages) }} language{{ count($codeRunnerLanguages) === 1 ? '' : 's' }} available</span>
                                                            @endif
                                                        </p>
                                                        @if(filled($activeAssignment->description))
                                                            <div class="mt-3 border-t border-primary/15 pt-3 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap">{{ $activeAssignment->description }}</div>
                                                        @endif
                                                        <div id="assignment-submitted-banner" class="mt-3 rounded-xl border border-emerald-200/90 bg-emerald-50/90 px-3 py-2.5 text-xs font-medium text-emerald-900 sm:text-sm {{ $activeAssignmentSubmitted ? '' : 'hidden' }}">
                                                            <p id="assignment-submitted-message">
                                                                @if($activeAssignmentSubmitted && $activeAssignmentSubmission)
                                                                    Submitted on <span class="font-semibold tabular-nums">{{ $activeAssignmentSubmission->created_at->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</span>. Your saved code is shown below and cannot be changed.
                                                                @else
                                                                    You have submitted this {{ $activeTypeLowerLabel }}. It is final and cannot be changed.
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('student.dashboard') }}#program-{{ $program->id }}-assignments" class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 shadow-sm transition hover:bg-red-100">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        Close
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="hidden" id="code-run-assignment-id" value="{{ $activeAssignment->id }}">
                                            <div id="assignment-runner-config" class="hidden" data-submit-url="{{ $activeAssignmentSubmitted ? '' : route('student.assignments.submit', $activeAssignment) }}" data-already-submitted="{{ $activeAssignmentSubmitted ? '1' : '0' }}" data-item-label="{{ $activeTypeLabel }}" data-item-label-lower="{{ $activeTypeLowerLabel }}"></div>
                                            @php
                                                $runnerLangIds = array_column($codeRunnerLanguages, 'id');
                                                $defaultRunnerLang = in_array(71, $runnerLangIds, true) ? 71 : ($runnerLangIds[0] ?? 71);
                                                $selectedRunnerLang = $defaultRunnerLang;
                                                if ($activeAssignmentSubmission && $activeAssignmentSubmission->judge0_language_id !== null) {
                                                    $sid = (int) $activeAssignmentSubmission->judge0_language_id;
                                                    if (in_array($sid, $runnerLangIds, true)) {
                                                        $selectedRunnerLang = $sid;
                                                    }
                                                }
                                                $runnerSourceValue = $activeAssignmentSubmission
                                                    ? (string) ($activeAssignmentSubmission->source_code ?? '')
                                                    : (string) ($activeAssignment->starter_code ?? '');
                                            @endphp
                                            <div>
                                                <label for="code-run-language" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Language</label>
                                                <select id="code-run-language" @disabled($activeAssignmentSubmitted) class="w-full rounded-xl border-0 bg-slate-100/90 px-4 py-3 text-sm font-medium text-slate-800 shadow-inner ring-1 ring-slate-200/80 focus:outline-none focus:ring-2 focus:ring-primary/35 disabled:cursor-not-allowed disabled:opacity-60">
                                                    @foreach ($codeRunnerLanguages as $lang)
                                                        <option value="{{ $lang['id'] }}" @selected((int) $lang['id'] === (int) $selectedRunnerLang)>{{ $lang['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="code-run-source" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Your code</label>
                                                <textarea id="code-run-source" rows="12" @disabled($activeAssignmentSubmitted) class="w-full resize-y rounded-xl border-0 bg-slate-900 px-4 py-3 font-mono text-sm leading-relaxed text-slate-100 shadow-inner ring-1 ring-slate-700/80 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:cursor-not-allowed disabled:opacity-60" placeholder="Paste your code here…" required spellcheck="false">{{ $runnerSourceValue }}</textarea>
                                            </div>
                                            <div>
                                                <button type="button" id="code-run-submit" @disabled($activeAssignmentSubmitted) class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 transition hover:bg-primary-hover disabled:pointer-events-none disabled:opacity-55">
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
                                            @if(!$activeAssignmentSubmitted)
                                                <div id="assignment-submit-wrap" class="hidden">
                                                    <button type="button" id="assignment-submit-open" class="inline-flex items-center gap-2 rounded-xl border border-emerald-300/90 bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-emerald-700">
                                                        Submit {{ $activeTypeLowerLabel }}
                                                    </button>
                                                    <p class="mt-2 text-xs text-slate-500">Shown after your run is <span class="font-medium text-emerald-800">Accepted</span> by the judge.</p>
                                                </div>
                                            @endif
                                            <p id="code-run-error" class="hidden rounded-xl border border-red-200/90 bg-red-50/95 px-4 py-3 text-sm font-medium text-red-800 shadow-sm"></p>
                                        </div>
                                        <div id="submit-assignment-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="submit-assignment-modal-title">
                                            <div class="w-full max-w-md rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xl">
                                                <h3 id="submit-assignment-modal-title" class="text-base font-bold text-slate-900">Submit this {{ $activeTypeLowerLabel }}?</h3>
                                                <p class="mt-3 text-sm leading-relaxed text-slate-600">Are you sure you want to submit? Once submitted, your {{ $activeTypeLowerLabel }} cannot be changed later.</p>
                                                <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                                                    <button type="button" id="submit-assignment-cancel" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Cancel</button>
                                                    <button type="button" id="submit-assignment-confirm" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:pointer-events-none disabled:opacity-55">Yes, submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                            </div>
                        @endif

                        @php
                            $remarksSubmissions = $submissionsByProgramId->get($program->id, collect());
                            $assignmentRemarkRows = $assignmentRemarksByEnrollmentId->get($enrollment->id, collect());
                            $subsByAssignment = $remarksSubmissions->groupBy('syllabus_assignment_id')->map(fn ($g) => $g->sortByDesc('created_at')->first());
                        @endphp
                        <div class="mx-5 mb-5 rounded-xl border border-amber-200/70 bg-gradient-to-br from-amber-50/95 to-amber-50/50 px-4 py-3.5 sm:mx-6">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-amber-900/75">Subject/Program manager remarks</p>
                            @if($subsByAssignment->isNotEmpty())
                                <div class="mt-3 border-b border-amber-200/50 pb-3">
                                    <p class="text-xs font-semibold text-amber-900/90">Your submitted assignments</p>
                                    <ul class="mt-2 space-y-3 text-sm">
                                        @foreach($subsByAssignment as $aid => $sub)
                                            @php $note = $assignmentRemarkRows->get($aid)?->remarks; @endphp
                                            <li class="rounded-lg border border-amber-200/40 bg-white/60 px-3 py-2">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <span class="font-medium text-slate-900">{{ $sub->syllabusAssignment->title }}</span>
                                                    <span class="inline-flex rounded-md bg-emerald-100/90 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-900">Submitted</span>
                                                    <span class="text-xs text-slate-600 tabular-nums">{{ $sub->created_at->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</span>
                                                </div>
                                                @if(filled($note))
                                                    <p class="mt-2 text-sm leading-relaxed text-slate-800 whitespace-pre-wrap border-l-2 border-amber-400/80 pl-2">{{ $note }}</p>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @foreach($assignmentRemarkRows as $aid => $row)
                                @if(filled($row->remarks) && ! $subsByAssignment->has($aid))
                                    <div class="mt-3 rounded-lg border border-amber-200/50 bg-white/50 px-3 py-2">
                                        <p class="text-xs font-semibold text-amber-900/90">{{ $row->syllabusAssignment?->title ?? 'Assignment' }}</p>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $row->remarks }}</p>
                                    </div>
                                @endif
                            @endforeach
                            @if($enrollment->manager_remarks)
                                <p class="mt-3 text-sm leading-relaxed text-slate-800 whitespace-pre-wrap">{{ $enrollment->manager_remarks }}</p>
                            @elseif($subsByAssignment->isEmpty() && $assignmentRemarkRows->filter(fn ($r) => filled($r->remarks))->isEmpty())
                                <p class="mt-2 text-sm text-slate-600">No remarks have been added for you in this subject/program yet.</p>
                            @elseif(! $enrollment->manager_remarks && ($subsByAssignment->isNotEmpty() || $assignmentRemarkRows->filter(fn ($r) => filled($r->remarks))->isNotEmpty()))
                                <p class="mt-2 text-sm text-slate-600">No overall subject/program note from your instructor yet.</p>
                            @endif
                        </div>
                        </div>
                    </details>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-800">No subjects/programs yet</p>
                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">When a subject/program manager adds you with your registered account, your years/semesters/events, attendance, and remarks will show up here.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        (function () {
            const runUrl = @json(route('student.code-run'));
            const btn = document.getElementById('code-run-submit');
            const sourceEl = document.getElementById('code-run-source');
            if (!btn || !sourceEl) {
                return;
            }
            const spinner = document.getElementById('code-run-spinner');
            const label = document.getElementById('code-run-submit-label');
            const langEl = document.getElementById('code-run-language');
            const assignmentField = document.getElementById('code-run-assignment-id');
            const resultBox = document.getElementById('code-run-result');
            const statusEl = document.getElementById('code-run-status');
            const sectionsEl = document.getElementById('code-run-sections');
            const errEl = document.getElementById('code-run-error');
            const runnerConfig = document.getElementById('assignment-runner-config');
            const submitWrap = document.getElementById('assignment-submit-wrap');
            const submitModal = document.getElementById('submit-assignment-modal');
            const submitOpenBtn = document.getElementById('assignment-submit-open');
            const submitCancelBtn = document.getElementById('submit-assignment-cancel');
            const submitConfirmBtn = document.getElementById('submit-assignment-confirm');
            const submittedBanner = document.getElementById('assignment-submitted-banner');

            function submitUrl() {
                return (runnerConfig && runnerConfig.dataset.submitUrl) ? String(runnerConfig.dataset.submitUrl).trim() : '';
            }

            function alreadySubmittedFlag() {
                return runnerConfig && runnerConfig.dataset.alreadySubmitted === '1';
            }

            function isJudge0Accepted(sid, desc) {
                const n = typeof sid === 'number' ? sid : parseInt(sid, 10);
                if (!Number.isNaN(n) && n === 3) {
                    return true;
                }
                const d = String(desc || '').toLowerCase().trim();
                return d === 'accepted' || d.indexOf('accepted') === 0;
            }

            function setSubmitModalOpen(open) {
                if (!submitModal) {
                    return;
                }
                if (open) {
                    submitModal.classList.remove('hidden');
                    submitModal.classList.add('flex');
                } else {
                    submitModal.classList.add('hidden');
                    submitModal.classList.remove('flex');
                }
            }

            function updateSubmitWrapVisibility(sid, desc) {
                if (!submitWrap || !runnerConfig) {
                    return;
                }
                const url = submitUrl();
                if (url && !alreadySubmittedFlag() && isJudge0Accepted(sid, desc)) {
                    submitWrap.classList.remove('hidden');
                } else {
                    submitWrap.classList.add('hidden');
                }
            }

            function lockRunnerAfterSubmit() {
                if (runnerConfig) {
                    runnerConfig.dataset.alreadySubmitted = '1';
                    runnerConfig.dataset.submitUrl = '';
                }
                if (submitWrap) {
                    submitWrap.classList.add('hidden');
                }
                if (submittedBanner) {
                    submittedBanner.classList.remove('hidden');
                }
                sourceEl.disabled = true;
                langEl.disabled = true;
                btn.disabled = true;
                setSubmitModalOpen(false);
            }

            if (submitOpenBtn && submitModal) {
                submitOpenBtn.addEventListener('click', function () {
                    if (!submitUrl() || alreadySubmittedFlag()) {
                        return;
                    }
                    setSubmitModalOpen(true);
                });
            }
            if (submitCancelBtn) {
                submitCancelBtn.addEventListener('click', function () {
                    setSubmitModalOpen(false);
                });
            }
            if (submitModal) {
                submitModal.addEventListener('click', function (e) {
                    if (e.target === submitModal) {
                        setSubmitModalOpen(false);
                    }
                });
            }
            if (submitConfirmBtn) {
                submitConfirmBtn.addEventListener('click', async function () {
                    const url = submitUrl();
                    if (!url || alreadySubmittedFlag()) {
                        setSubmitModalOpen(false);
                        return;
                    }
                    submitConfirmBtn.disabled = true;
                    try {
                        const body = {
                            source_code: sourceEl ? sourceEl.value : '',
                            language_id: langEl ? parseInt(langEl.value, 10) : 0,
                        };
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify(body),
                        });
                        const data = await res.json().catch(function () { return {}; });
                        if (!res.ok || !data.ok) {
                            alert(data.error || data.message || ('Submit failed (' + res.status + ')'));
                            return;
                        }
                        const msgEl = document.getElementById('assignment-submitted-message');
                        if (msgEl && data.submitted_at_display) {
                            msgEl.textContent = '';
                            msgEl.appendChild(document.createTextNode('Submitted on '));
                            const span = document.createElement('span');
                            span.className = 'font-semibold tabular-nums';
                            span.textContent = data.submitted_at_display;
                            msgEl.appendChild(span);
                            msgEl.appendChild(document.createTextNode('. Your saved code is shown below and cannot be changed.'));
                        }
                        lockRunnerAfterSubmit();
                    } catch (e) {
                        alert('Something went wrong. Try again.');
                    } finally {
                        submitConfirmBtn.disabled = false;
                    }
                });
            }

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
                const sw = document.getElementById('assignment-submit-wrap');
                if (sw) {
                    sw.classList.add('hidden');
                }
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
                    const assignmentRaw = assignmentField && assignmentField.value ? assignmentField.value.trim() : '';
                    const assignmentId = assignmentRaw !== '' ? parseInt(assignmentRaw, 10) : null;
                    const payload = {
                        source_code,
                        language_id: parseInt(langEl.value, 10),
                        stdin: '',
                    };
                    if (assignmentId != null && !Number.isNaN(assignmentId)) {
                        payload.assignment_id = assignmentId;
                    }
                    const res = await fetch(runUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(payload),
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
                    updateSubmitWrapVisibility(sid, desc);
                } catch (e) {
                    showError('Something went wrong. Check your connection and try again.');
                } finally {
                    setLoading(false);
                }
            });
        })();
    </script>
@endsection

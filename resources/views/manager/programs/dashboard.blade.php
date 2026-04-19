@extends('manager.layouts.app')

@section('title', 'Subject/Program Dashboard')

@section('content')
@php
    $pct = function (int $done, int $total): int {
        if ($total <= 0) {
            return 0;
        }

        return (int) min(100, round(100 * $done / $total));
    };
    $topicPct = $pct($stats['topics_complete_count'], $stats['topics_count']);
    $subtopicPct = $pct($stats['subtopics_complete_count'], $stats['subtopics_count']);
@endphp

<div class="relative -mx-4 -mt-2 px-4 sm:-mx-6 sm:px-6">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-primary/[0.12] blur-3xl"></div>
        <div class="absolute -left-20 top-1/3 h-64 w-64 rounded-full bg-sky-400/[0.15] blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 h-48 w-48 rounded-full bg-emerald-400/[0.12] blur-3xl"></div>
    </div>
</div>

{{-- Hero --}}
<div class="relative mb-8 overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/80 to-indigo-50/40 shadow-[0_8px_30px_-12px_rgba(67,56,202,0.25)]">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%234338ca\' fill-opacity=\'0.04\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="relative px-5 py-6 sm:px-8 sm:py-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0 flex-1 space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusBadgeClass }}">
                        {{ $stats['status_label'] }}
                    </span>
                    @if($stats['program_type'] && $stats['program_type'] !== '—')
                        <span class="inline-flex items-center rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200/80 backdrop-blur-sm">
                            {{ $stats['program_type'] }}
                        </span>
                    @endif
                    <span class="inline-flex items-center rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200/80 backdrop-blur-sm">
                        {{ $stats['mode'] }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $program->name }}
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                        <span class="font-medium text-slate-700">Year/Semester/Event:</span>
                        {{ $stats['event_name'] }}
                        @if($eventWindow && ($eventWindow['start'] || $eventWindow['end']))
                            <span class="text-slate-400">·</span>
                            @if($eventWindow['start']){{ $eventWindow['start']->format('M j, Y') }}@endif
                            @if($eventWindow['start'] && $eventWindow['end']) – @endif
                            @if($eventWindow['end']){{ $eventWindow['end']->format('M j, Y') }}@endif
                        @endif
                    </p>
                </div>
                <dl class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-slate-200/80 backdrop-blur-sm">
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">College</dt>
                        <dd class="mt-0.5 truncate text-sm font-semibold text-slate-900" title="{{ $stats['college_name'] }}">{{ $stats['college_name'] }}</dd>
                    </div>
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-slate-200/80 backdrop-blur-sm">
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Duration</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-900">{{ $stats['duration_days'] }} days</dd>
                    </div>
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-slate-200/80 backdrop-blur-sm col-span-2 sm:col-span-1">
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Departments</dt>
                        <dd class="mt-0.5 line-clamp-2 text-sm font-semibold text-slate-900" title="{{ $stats['departments_label'] ?: '—' }}">{{ $stats['departments_label'] ?: '—' }}</dd>
                    </div>
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-slate-200/80 backdrop-blur-sm col-span-2 sm:col-span-1">
                        <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Your access</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-900">{{ $stats['manager_account_type'] }}</dd>
                    </div>
                </dl>
            </div>
            <div class="w-full shrink-0 lg:w-72">
                <div class="rounded-2xl bg-white/90 p-5 ring-1 ring-primary/15 shadow-lg shadow-primary/5 backdrop-blur-md">
                    <p class="text-xs font-semibold uppercase tracking-wider text-primary">Signed in as</p>
                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $stats['manager_name'] }}</p>
                    <p class="mt-3 text-xs leading-relaxed text-slate-500">
                        Use the quick links below to manage students, sessions, syllabus, and closure workflow for this subject/program.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
    @php
        $quick = [
            ['href' => route('manager.program.students.index', $program), 'label' => 'Students', 'sub' => $stats['students_count'].' enrolled', 'icon' => 'users'],
            ['href' => route('manager.program.sessions.index', $program), 'label' => 'Sessions', 'sub' => $stats['sessions_count'].' total', 'icon' => 'calendar'],
            ['href' => route('manager.program.syllabus.index', $program), 'label' => 'Syllabus', 'sub' => $stats['topics_count'].' topics', 'icon' => 'book'],
            ['href' => route('manager.program.remarks.index', $program), 'label' => 'Remarks', 'sub' => $stats['students_with_remarks_count'].' with notes', 'icon' => 'chat'],
            ['href' => route('manager.program.completion.report', $program), 'label' => 'Completion', 'sub' => 'Reports', 'icon' => 'chart'],
            ['href' => route('manager.program.completion.create', $program), 'label' => 'Closure', 'sub' => 'Request', 'icon' => 'check'],
        ];
    @endphp
    @foreach($quick as $item)
        <a href="{{ $item['href'] }}" class="group relative flex flex-col rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-md hover:shadow-primary/10">
            <span class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-primary/15 to-sky-500/10 text-primary ring-1 ring-primary/10 transition group-hover:from-primary/25 group-hover:to-sky-500/15">
                @if($item['icon'] === 'users')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                @elseif($item['icon'] === 'calendar')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                @elseif($item['icon'] === 'book')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                @elseif($item['icon'] === 'chat')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                @elseif($item['icon'] === 'chart')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                @else
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @endif
            </span>
            <span class="text-sm font-semibold text-slate-900">{{ $item['label'] }}</span>
            <span class="mt-0.5 text-xs text-slate-500">{{ $item['sub'] }}</span>
        </a>
    @endforeach
</div>

{{-- Stat grid --}}
<div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
    <div class="rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-slate-500">Students</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $stats['students_count'] }}</p>
    </div>
    <div class="rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-slate-500">Sessions</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $stats['sessions_count'] }}</p>
        <p class="mt-1 text-[11px] text-slate-500">{{ $stats['upcoming_sessions_count'] }} upcoming · {{ $stats['past_sessions_count'] }} past</p>
    </div>
    <div class="rounded-xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50/80 to-white p-4 shadow-sm">
        <p class="text-xs font-medium text-emerald-800/80">Attendance marks</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-950">{{ $stats['attendance_marks_count'] }}</p>
    </div>
    <div class="rounded-xl border border-sky-200/80 bg-gradient-to-br from-sky-50/80 to-white p-4 shadow-sm">
        <p class="text-xs font-medium text-sky-800/80">Feedback</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-sky-950">{{ $stats['feedback_count'] }}</p>
    </div>
    <div class="rounded-xl border border-violet-200/80 bg-gradient-to-br from-violet-50/80 to-white p-4 shadow-sm">
        <p class="text-xs font-medium text-violet-800/80">Assignments</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-violet-950">{{ $stats['assignments_count'] }}</p>
    </div>
    <div class="rounded-xl border border-amber-200/80 bg-gradient-to-br from-amber-50/80 to-white p-4 shadow-sm">
        <p class="text-xs font-medium text-amber-900/80">Closure pending</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-amber-950">{{ $stats['pending_completion'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
    {{-- Session spotlight --}}
    <div class="lg:col-span-5 space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">Sessions timeline</h2>
                <p class="mt-0.5 text-xs text-slate-500">Next class and recent activity</p>
            </div>
            <div class="p-5 space-y-4">
                @if($nextSession)
                    <div class="rounded-xl bg-gradient-to-br from-primary/8 via-white to-sky-50/50 p-4 ring-1 ring-primary/15">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-primary">Next session</p>
                        <p class="mt-2 text-lg font-bold text-slate-900">{{ $nextSession->title ?: 'Session' }}</p>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $nextSession->session_date?->format('l, M j, Y') ?? '—' }}
                            @if($nextSession->start_time || $nextSession->end_time)
                                <span class="text-slate-400">·</span>
                                @if($nextSession->start_time){{ \Illuminate\Support\Str::of($nextSession->start_time)->substr(0, 5) }}@endif
                                @if($nextSession->start_time && $nextSession->end_time)–@endif
                                @if($nextSession->end_time){{ \Illuminate\Support\Str::of($nextSession->end_time)->substr(0, 5) }}@endif
                            @endif
                        </p>
                        <a href="{{ route('manager.program.attendance.edit', [$program, $nextSession]) }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-hover">
                            Open attendance
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-4 text-center">
                        <p class="text-sm font-medium text-slate-700">No upcoming sessions</p>
                        <p class="mt-1 text-xs text-slate-500">Add dates on the Daily Report / Sessions page.</p>
                        <a href="{{ route('manager.program.sessions.index', $program) }}" class="mt-3 inline-flex text-sm font-semibold text-primary hover:underline">Go to sessions</a>
                    </div>
                @endif

                @if($lastSession)
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/40 p-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-200/60 text-slate-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Last session</p>
                            <p class="mt-0.5 font-semibold text-slate-900">{{ $lastSession->title ?: 'Session' }}</p>
                            <p class="text-xs text-slate-600">{{ $lastSession->session_date?->format('M j, Y') ?? '—' }}</p>
                            <a href="{{ route('manager.program.daily.report', [$program, $lastSession]) }}" class="mt-2 inline-flex text-xs font-semibold text-primary hover:underline">Daily report</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">Closure requests</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-lg bg-amber-50 py-3 ring-1 ring-amber-100">
                        <p class="text-2xl font-bold tabular-nums text-amber-900">{{ $stats['pending_completion'] }}</p>
                        <p class="text-[11px] font-medium text-amber-800/80">Pending</p>
                    </div>
                    <div class="rounded-lg bg-emerald-50 py-3 ring-1 ring-emerald-100">
                        <p class="text-2xl font-bold tabular-nums text-emerald-900">{{ $stats['approved_completion'] }}</p>
                        <p class="text-[11px] font-medium text-emerald-800/80">Approved</p>
                    </div>
                    <div class="rounded-lg bg-rose-50 py-3 ring-1 ring-rose-100">
                        <p class="text-2xl font-bold tabular-nums text-rose-900">{{ $stats['rejected_completion'] }}</p>
                        <p class="text-[11px] font-medium text-rose-800/80">Rejected</p>
                    </div>
                </div>
                @if($stats['pending_completion'] > 0)
                    <p class="mt-4 text-xs text-slate-600">College admin will review pending requests. You can submit or track status from <strong>Closure Request</strong>.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Syllabus + delivery --}}
    <div class="lg:col-span-7 space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="flex flex-col gap-1 border-b border-slate-100 bg-gradient-to-r from-indigo-50/50 to-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Syllabus progress</h2>
                    <p class="text-xs text-slate-500">Topics and subtopics marked complete</p>
                </div>
                <a href="{{ route('manager.program.syllabus.index', $program) }}" class="text-sm font-semibold text-primary hover:text-primary-hover">Manage syllabus →</a>
            </div>
            <div class="p-5 space-y-6">
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-700">Topics</span>
                        <span class="tabular-nums text-slate-600">{{ $stats['topics_complete_count'] }} / {{ $stats['topics_count'] }}</span>
                    </div>
                    <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-sky-500 transition-all" style="width: {{ $topicPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-700">Subtopics</span>
                        <span class="tabular-nums text-slate-600">{{ $stats['subtopics_complete_count'] }} / {{ $stats['subtopics_count'] }}</span>
                    </div>
                    <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500 transition-all" style="width: {{ $subtopicPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">Delivery &amp; oversight</h2>
                <p class="mt-0.5 text-xs text-slate-500">Who runs this subject/program and internal oversight</p>
            </div>
            <div class="divide-y divide-slate-100">
                <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm font-medium text-slate-500">Runs sessions (vendor / trainer)</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $stats['executor_label'] }}</span>
                </div>
                <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm font-medium text-slate-500">Internal oversight manager</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $stats['oversight_manager_name'] ?? '—' }}</span>
                </div>
                @if($eventWindow && $eventWindow['status'])
                    <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-sm font-medium text-slate-500">Year/Semester/Event status</span>
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-800">{{ $eventWindow['status'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

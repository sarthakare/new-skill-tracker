@extends('manager.layouts.app')

@section('title', 'Syllabus')
@section('title_suffix', '')

@section('content')
@php
    $topicCount = $topics->count();
    $topicsDone = $topics->where('is_complete', true)->count();
    $subtopicCount = $topics->sum(fn ($t) => $t->subtopics->count());
    $subtopicsDone = $topics->sum(fn ($t) => $t->subtopics->where('is_complete', true)->count());
    $assignmentCount = $topics->sum(fn ($t) => $t->subtopics->sum(fn ($s) => $s->assignments->count()));
    $topicPct = $topicCount > 0 ? (int) min(100, round(100 * $topicsDone / $topicCount)) : 0;
    $subPct = $subtopicCount > 0 ? (int) min(100, round(100 * $subtopicsDone / $subtopicCount)) : 0;
@endphp

<div class="relative -mx-4 -mt-2 px-4 sm:-mx-6 sm:px-6 print:hidden">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -right-20 -top-16 h-64 w-64 rounded-full bg-primary/[0.11] blur-3xl"></div>
        <div class="absolute left-1/4 top-40 h-56 w-56 -translate-x-1/2 rounded-full bg-sky-400/[0.12] blur-3xl"></div>
        <div class="absolute -bottom-8 right-1/3 h-40 w-40 rounded-full bg-violet-400/[0.10] blur-3xl"></div>
    </div>
</div>

{{-- Hero --}}
<div class="relative mb-5 overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-br from-white via-slate-50/90 to-indigo-50/50 shadow-[0_8px_30px_-12px_rgba(67,56,202,0.2)] print:hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%234338ca\' fill-opacity=\'0.035\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/svg%3E')] opacity-70"></div>
    <div class="relative flex flex-col gap-4 p-4 sm:flex-row sm:items-start sm:justify-between sm:p-5">
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2.5">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary/20 to-sky-500/15 text-primary ring-1 ring-primary/15">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Syllabus</h1>
                    <p class="mt-0.5 truncate text-sm font-medium text-slate-600 sm:text-base" title="{{ $program->name }}">{{ $program->name }}</p>
                </div>
            </div>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600">
                Build units and subtopics, schedule sessions, and track progress as you teach. Add coding assignments under any subtopic for Judge0-style delivery.
            </p>
            @if($topicCount > 0)
                <div class="mt-3 grid max-w-lg gap-2 sm:grid-cols-2">
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-600">
                            <span>Topics complete</span>
                            <span class="tabular-nums">{{ $topicsDone }}/{{ $topicCount }}</span>
                        </div>
                        <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-200/80">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary to-sky-500 transition-all" style="width: {{ $topicPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-600">
                            <span>Subtopics complete</span>
                            <span class="tabular-nums">{{ $subtopicsDone }}/{{ $subtopicCount }}</span>
                        </div>
                        <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-200/80">
                            <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500 transition-all" style="width: {{ $subPct }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="flex shrink-0 sm:items-start">
            <button type="button" onclick="if(location.hash){history.replaceState(null,'',location.pathname+location.search);}window.print();" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-primary/25 transition hover:bg-primary-hover sm:w-auto">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print formal syllabus
            </button>
        </div>
    </div>
</div>

{{-- Quick stats --}}
<div class="mb-4 grid grid-cols-2 gap-2 sm:grid-cols-4 print:hidden">
    <div class="rounded-lg border border-slate-200/90 bg-white p-3 shadow-sm">
        <p class="text-xs font-medium text-slate-500">Topics</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $topicCount }}</p>
    </div>
    <div class="rounded-lg border border-slate-200/90 bg-white p-3 shadow-sm">
        <p class="text-xs font-medium text-slate-500">Subtopics</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900">{{ $subtopicCount }}</p>
    </div>
    <div class="rounded-lg border border-emerald-200/70 bg-gradient-to-br from-emerald-50/90 to-white p-3 shadow-sm">
        <p class="text-xs font-medium text-emerald-800/80">Done</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-950">{{ $topicsDone + $subtopicsDone }}</p>
        <p class="mt-0.5 text-[11px] text-emerald-800/70">items marked complete</p>
    </div>
    <div class="rounded-lg border border-violet-200/70 bg-gradient-to-br from-violet-50/90 to-white p-3 shadow-sm">
        <p class="text-xs font-medium text-violet-800/80">Assignments</p>
        <p class="mt-1 text-2xl font-bold tabular-nums text-violet-950">{{ $assignmentCount }}</p>
    </div>
</div>

<div class="relative mb-5 overflow-hidden rounded-xl border border-slate-200/90 bg-white/90 shadow-sm ring-1 ring-slate-200/50 backdrop-blur-sm print:hidden">
    <div class="border-b border-slate-100 bg-gradient-to-r from-primary/[0.07] via-white to-sky-50/40 px-4 py-3">
        <h2 class="text-base font-semibold text-slate-900">Add topic or unit</h2>
        <p class="mt-0.5 text-xs text-slate-500">Create a new top-level section for your syllabus</p>
    </div>
    <div class="p-4">
        <form action="{{ route('manager.program.syllabus.topics.store', $program) }}" method="POST" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-end">
            @csrf
            <div class="min-w-0 flex-1 sm:min-w-[240px]">
                <label class="mb-1 block text-sm font-medium text-slate-700">Topic or unit name</label>
                <input type="text" name="title" class="w-full rounded-input border border-slate-300 focus:border-primary focus:ring-2 focus:ring-primary" placeholder="e.g. Unit 1 — Introduction to Python" required>
            </div>
            <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-md shadow-primary/20 transition hover:bg-primary-hover">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add topic
            </button>
        </form>
    </div>
</div>

{{-- Printable syllabus - formal document for college submission --}}
<div id="syllabus-print-document" class="hidden print:block syllabus-formal-doc">
    <div class="syllabus-doc-header">
        @include('partials.formal-report-logo')
        <h1 class="syllabus-doc-title">SYLLABUS</h1>
        @if($program->college)
            <p class="syllabus-doc-subtitle">Submitted to {{ $program->college->name }}</p>
        @endif
        <p class="syllabus-doc-date">Date: {{ now()->format('d F Y') }}</p>
    </div>

    <table class="syllabus-doc-info-table">
        <tr><td class="syllabus-doc-label">Subject/program name</td><td>{{ $program->name }}</td></tr>
        @if($program->event)
            <tr><td class="syllabus-doc-label">Year/Semester/Event</td><td>{{ $program->event->name }}</td></tr>
        @endif
        @if($program->type)
            <tr><td class="syllabus-doc-label">Type</td><td>{{ $program->type }}</td></tr>
        @endif
        @if($program->departmentsLabel() !== '')
            <tr><td class="syllabus-doc-label">Departments</td><td>{{ $program->departmentsLabel() }}</td></tr>
        @endif
        @if($program->duration_days)
            <tr><td class="syllabus-doc-label">Duration</td><td>{{ $program->duration_days }} days</td></tr>
        @endif
        @if($program->mode)
            <tr><td class="syllabus-doc-label">Mode</td><td>{{ $program->mode }}</td></tr>
        @endif
        <tr><td class="syllabus-doc-label">Trainer</td><td>{{ $program->executorLabel() }}</td></tr>
    </table>

    <h2 class="syllabus-doc-section">Course Syllabus</h2>
    <div class="syllabus-doc-content">
        @forelse($topics as $topic)
            <div class="syllabus-topic-item">
                <p class="syllabus-topic-title">
                    {{ $loop->iteration }}. {{ $topic->title }}
                    @if($topic->scheduled_date || $topic->scheduled_time)
                        <span class="syllabus-scheduled">({{ trim(($topic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '')) }})</span>
                    @endif
                </p>
                @if($topic->subtopics->isNotEmpty())
                    <ul class="syllabus-subtopic-list">
                        @foreach($topic->subtopics as $subtopic)
                            <li>
                                {{ $subtopic->title }}
                                @if($subtopic->scheduled_date || $subtopic->scheduled_time)
                                    <span class="syllabus-scheduled">({{ trim(($subtopic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($subtopic->scheduled_time ? substr($subtopic->scheduled_time, 0, 5) : '')) }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @empty
            <p class="syllabus-empty">No topics or units defined.</p>
        @endforelse
    </div>
</div>

@forelse($topics as $topic)
    <div class="topic-card group relative mb-3 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-sm transition-shadow print:hidden hover:shadow-md" id="topic-{{ $topic->id }}">
        <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-primary via-primary/70 to-sky-500 opacity-90"></div>
        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50/90 via-white to-indigo-50/30 px-4 py-3">
            <div class="syllabus-schedule-block mb-2">
                <div class="flex flex-wrap items-center gap-2 w-full min-w-0">
                    <form action="{{ route('manager.program.syllabus.topics.toggle-complete', [$program, $topic]) }}" method="POST" class="inline shrink-0">
                        @csrf
                        <button type="submit" class="p-0 text-inherit no-underline hover:opacity-80" title="{{ $topic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                            @if($topic->is_complete)
                                <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            @else
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </button>
                    </form>
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <span class="topic-title-display {{ $topic->is_complete ? 'line-through text-slate-500' : '' }} font-medium text-slate-800 min-w-0 break-words">{{ $topic->title }}</span>
                        <form action="{{ route('manager.program.syllabus.topics.update', [$program, $topic]) }}" method="POST" class="topic-edit-form hidden flex-1 min-w-0 max-w-xl items-center">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $topic->title }}" required maxlength="255" data-original-title="{{ $topic->title }}" class="topic-title-input w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary font-medium text-slate-800 py-1 px-2 text-base bg-white">
                        </form>
                    </div>
                    @if($topic->scheduled_date || $topic->scheduled_time)
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-white px-2.5 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200/80 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ trim(($topic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '')) }}
                        </span>
                    @endif
                    <div class="topic-actions inline-flex items-center gap-0.5 shrink-0 rounded-lg bg-white/80 p-0.5 ring-1 ring-slate-200/70">
                        <button type="button" class="topic-edit-btn rounded-md p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800" title="Edit topic or unit name">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <form action="{{ route('manager.program.syllabus.topics.destroy', [$program, $topic]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this topic/unit and all its subtopics?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md p-1.5 text-slate-500 transition hover:bg-red-50 hover:text-red-600" title="Delete topic or unit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                        <button type="button" class="syllabus-schedule-toggle rounded-md p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800" title="Schedule date and time" aria-label="Schedule date and time">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                    </div>
                </div>
                <div class="syllabus-schedule-panel hidden mt-2 rounded-lg border border-slate-200/80 bg-slate-50/50 p-2">
                    <form action="{{ route('manager.program.syllabus.topics.schedule', [$program, $topic]) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <label class="text-sm text-slate-500">Date</label>
                        <input type="date" name="scheduled_date" class="w-[150px] rounded-input border border-slate-300 focus:border-primary focus:ring-2 focus:ring-primary" value="{{ $topic->scheduled_date?->format('Y-m-d') }}">
                        <label class="text-sm text-slate-500">Time</label>
                        <input type="time" name="scheduled_time" class="w-[125px] rounded-input border border-slate-300 focus:border-primary focus:ring-2 focus:ring-primary" value="{{ $topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '' }}">
                        <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-primary/30 hover:bg-primary/5">Set</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="px-4 pb-3 pt-0.5">
            <ul class="space-y-1.5">
                @forelse($topic->subtopics as $subtopic)
                    <li class="syllabus-schedule-block rounded-lg border border-slate-100 bg-slate-50/40 transition hover:border-slate-200/90 hover:bg-slate-50/70">
                        <div class="flex min-w-0 w-full flex-nowrap items-center gap-2 overflow-x-auto overscroll-x-contain px-2.5 py-2 sm:gap-2 sm:px-3">
                            <form action="{{ route('manager.program.syllabus.subtopics.toggle-complete', [$program, $subtopic]) }}" method="POST" class="inline shrink-0">
                                @csrf
                                <button type="submit" class="p-0 text-inherit no-underline hover:opacity-80" title="{{ $subtopic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                                    @if($subtopic->is_complete)
                                        <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                </button>
                            </form>
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="subtopic-title-display {{ $subtopic->is_complete ? 'line-through text-slate-500' : '' }} text-sm text-slate-700 min-w-0 break-words">{{ $subtopic->title }}</span>
                                <form action="{{ route('manager.program.syllabus.subtopics.update', [$program, $subtopic]) }}" method="POST" class="subtopic-edit-form hidden flex-1 min-w-0 max-w-xl items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="title" value="{{ $subtopic->title }}" required maxlength="255" data-original-title="{{ $subtopic->title }}" class="subtopic-title-input w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary text-sm text-slate-700 py-1 px-2 bg-white">
                                </form>
                            </div>
                            @if($subtopic->scheduled_date || $subtopic->scheduled_time)
                                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-white px-2 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200/80">
                                    <svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ trim(($subtopic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($subtopic->scheduled_time ? substr($subtopic->scheduled_time, 0, 5) : '')) }}
                                </span>
                            @endif
                            <div class="subtopic-actions inline-flex shrink-0 flex-nowrap items-center gap-1.5 rounded-md bg-white/90 py-0.5 pl-0.5 pr-1 ring-1 ring-slate-200/60 sm:gap-2 sm:pr-1.5">
                                <a href="{{ route('manager.program.syllabus.assignments.create', [$program, $subtopic]) }}" class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap rounded-md px-2.5 py-1.5 text-xs font-semibold text-primary ring-1 ring-primary/20 transition hover:bg-primary/10" title="Create coding assignment for this subtopic">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Assignment
                                </a>
                                <button type="button" class="subtopic-edit-btn inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-500 transition hover:bg-slate-100 hover:text-slate-800" title="Edit"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <form action="{{ route('manager.program.syllabus.subtopics.destroy', [$program, $subtopic]) }}" method="POST" class="inline shrink-0" onsubmit="return confirm('Delete this subtopic?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition hover:bg-red-50 hover:text-red-600" title="Delete"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                                <button type="button" class="syllabus-schedule-toggle inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-500 transition hover:bg-slate-100 hover:text-slate-800" title="Schedule date and time" aria-label="Schedule date and time">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
                            </div>
                        </div>
                        <div class="syllabus-schedule-panel hidden border-t border-slate-100/80 px-2.5 pb-2 pt-1.5 sm:px-3">
                            <form action="{{ route('manager.program.syllabus.subtopics.schedule', [$program, $subtopic]) }}" method="POST" class="flex flex-wrap items-center gap-2 rounded-md bg-white/70 p-1.5 ring-1 ring-slate-200/60">
                                @csrf
                                <label class="text-sm text-slate-500">Date</label>
                                <input type="date" name="scheduled_date" class="w-[150px] rounded-input border border-slate-300 text-sm focus:border-primary focus:ring-2 focus:ring-primary" value="{{ $subtopic->scheduled_date?->format('Y-m-d') }}">
                                <label class="text-sm text-slate-500">Time</label>
                                <input type="time" name="scheduled_time" class="w-[125px] rounded-input border border-slate-300 text-sm focus:border-primary focus:ring-2 focus:ring-primary" value="{{ $subtopic->scheduled_time ? substr($subtopic->scheduled_time, 0, 5) : '' }}">
                                <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-primary/30 hover:bg-primary/5">Set</button>
                            </form>
                        </div>
                        @if($subtopic->assignments->isNotEmpty())
                            <ul class="mx-2 mb-2 space-y-1 border-l-2 border-primary/30 pl-2 print:hidden sm:mx-2.5 sm:pl-2.5">
                                @foreach($subtopic->assignments as $syllabusAssignment)
                                    @php
                                        $rangeLabel = '';
                                        if ($syllabusAssignment->starts_on && $syllabusAssignment->ends_on) {
                                            $rangeLabel = $syllabusAssignment->starts_on->format('M j, Y').' – '.$syllabusAssignment->ends_on->format('M j, Y');
                                        } elseif ($syllabusAssignment->starts_on) {
                                            $rangeLabel = 'From '.$syllabusAssignment->starts_on->format('M j, Y');
                                        } elseif ($syllabusAssignment->ends_on) {
                                            $rangeLabel = 'Until '.$syllabusAssignment->ends_on->format('M j, Y');
                                        }
                                    @endphp
                                    <li class="overflow-hidden rounded-md bg-white/80 text-sm ring-1 ring-slate-100">
                                        <div class="flex min-w-0 flex-nowrap items-center gap-1.5 overflow-x-auto py-1.5 px-1.5">
                                            <span class="inline-flex shrink-0 items-center rounded-md border border-red-500 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-red-700">Assignment</span>
                                            <span class="min-w-0 flex-1 font-medium text-slate-800">{{ $syllabusAssignment->title }}</span>
                                            @if($rangeLabel !== '')
                                                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-white px-2 py-0.5 text-[11px] font-medium text-slate-600 ring-1 ring-slate-200/80" title="Availability">
                                                    <svg class="h-3 w-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    {{ $rangeLabel }}
                                                </span>
                                            @endif
                                            <div class="inline-flex shrink-0 flex-nowrap items-center gap-1">
                                                <a href="{{ route('manager.program.syllabus.assignments.edit', [$program, $syllabusAssignment]) }}" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-primary transition hover:bg-primary/10" title="Edit assignment" aria-label="Edit assignment">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                                <form action="{{ route('manager.program.syllabus.assignments.destroy', [$program, $syllabusAssignment]) }}" method="POST" class="inline shrink-0" onsubmit="return confirm('Delete this assignment? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition hover:bg-red-50 hover:text-red-600" title="Delete assignment" aria-label="Delete assignment">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="rounded-lg border border-dashed border-slate-200 bg-slate-50/50 py-4 text-center text-sm text-slate-500">No subtopics yet. Add one below.</li>
                @endforelse
            </ul>
            <form action="{{ route('manager.program.syllabus.subtopics.store', [$program, $topic]) }}" method="POST" class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                @csrf
                <input type="text" name="title" class="min-w-[200px] flex-1 rounded-input border border-slate-300 focus:border-primary focus:ring-2 focus:ring-primary sm:min-w-[320px]" placeholder="New subtopic title…" required>
                <button type="submit" class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-primary/25 bg-primary/5 px-3 py-2 text-sm font-semibold text-primary transition hover:bg-primary/10">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add subtopic
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="relative overflow-hidden rounded-xl border border-slate-200/90 bg-gradient-to-br from-slate-50/80 to-white print:hidden">
        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-primary/[0.06] blur-2xl"></div>
        <div class="relative p-8 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary/15 to-sky-500/10 text-primary ring-1 ring-primary/15">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </span>
            <p class="mt-3 text-base font-semibold text-slate-800">No syllabus topics yet</p>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Use <strong class="text-slate-700">Add topic or unit</strong> above to create your first section.</p>
        </div>
    </div>
@endforelse

<style>
    /* Formal document styles - print only */
    .syllabus-formal-doc {
        font-family: 'Times New Roman', Times, serif;
        max-width: 100%;
        padding: 24px;
        margin: 0;
        color: #1e293b;
        border: 1px solid #1e293b;
    }
    .syllabus-doc-header {
        text-align: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #1e293b;
    }
    .syllabus-doc-title {
        font-size: 22pt;
        font-weight: 700;
        letter-spacing: 0.1em;
        margin: 0 0 8px 0;
    }
    .syllabus-doc-subtitle {
        font-size: 11pt;
        margin: 0 0 4px 0;
    }
    .syllabus-doc-date {
        font-size: 10pt;
        margin: 0;
    }
    .syllabus-doc-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
        font-size: 11pt;
    }
    .syllabus-doc-info-table td {
        padding: 6px 12px;
        border: 1px solid #64748b;
        vertical-align: top;
    }
    .syllabus-doc-label {
        width: 140px;
        font-weight: 600;
        background: #f8fafc;
    }
    .syllabus-doc-section {
        font-size: 14pt;
        font-weight: 700;
        margin: 0 0 16px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #94a3b8;
    }
    .syllabus-doc-content {
        font-size: 11pt;
        line-height: 1.5;
    }
    .syllabus-topic-item {
        margin-bottom: 16px;
    }
    .syllabus-topic-title {
        font-weight: 600;
        margin: 0 0 6px 0;
    }
    .syllabus-scheduled {
        font-weight: 400;
        font-size: 10pt;
        color: #64748b;
    }
    .syllabus-subtopic-list {
        margin: 0 0 0 24px;
        padding: 0;
        list-style-type: disc;
    }
    .syllabus-subtopic-list li {
        margin-bottom: 4px;
    }
    .syllabus-empty {
        font-style: italic;
        color: #64748b;
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
        #syllabus-print-document {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
            min-height: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }
        .syllabus-formal-doc, .syllabus-doc-content, .syllabus-topic-item {
            overflow: visible !important;
        }
        .syllabus-topic-item {
            page-break-inside: auto;
        }
        /* margin: 5mm hides browser header/footer (title, URL); @bottom-center adds our page number */
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

@push('scripts')
<script>
function finishTopicTitleEdit(card, opts) {
    const saveIfChanged = opts && opts.saveIfChanged;
    const form = card.querySelector('.topic-edit-form');
    const input = form && form.querySelector('.topic-title-input');
    const display = card.querySelector('.topic-title-display');
    const actions = card.querySelector('.topic-actions');
    if (!form || !input) return;
    const original = input.getAttribute('data-original-title') || '';
    const trimmed = input.value.trim();
    if (saveIfChanged && trimmed !== '' && trimmed !== original) {
        form.requestSubmit();
        return;
    }
    input.value = original;
    display?.classList.remove('hidden');
    actions?.classList.remove('hidden');
    form.classList.add('hidden');
}

function finishSubtopicTitleEdit(li, opts) {
    const saveIfChanged = opts && opts.saveIfChanged;
    const form = li.querySelector('.subtopic-edit-form');
    const input = form && form.querySelector('.subtopic-title-input');
    const display = li.querySelector('.subtopic-title-display');
    const actions = li.querySelector('.subtopic-actions');
    if (!form || !input) return;
    const original = input.getAttribute('data-original-title') || '';
    const trimmed = input.value.trim();
    if (saveIfChanged && trimmed !== '' && trimmed !== original) {
        form.requestSubmit();
        return;
    }
    input.value = original;
    display?.classList.remove('hidden');
    actions?.classList.remove('hidden');
    form.classList.add('hidden');
}

document.querySelectorAll('.topic-edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.topic-card');
        if (!card) return;
        const display = card.querySelector('.topic-title-display');
        const actions = card.querySelector('.topic-actions');
        const form = card.querySelector('.topic-edit-form');
        const input = form && form.querySelector('.topic-title-input');
        if (display) display.classList.add('hidden');
        if (actions) actions.classList.add('hidden');
        if (form) form.classList.remove('hidden');
        if (input) {
            input.value = input.getAttribute('data-original-title') || input.value;
            input.focus();
            input.select();
        }
    });
});

document.querySelectorAll('.topic-title-input').forEach(input => {
    const card = input.closest('.topic-card');
    if (!card) return;
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            finishTopicTitleEdit(card, { saveIfChanged: false });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (!input.value.trim()) return;
            card.querySelector('.topic-edit-form').requestSubmit();
        }
    });
    input.addEventListener('blur', function() {
        setTimeout(() => {
            const form = card.querySelector('.topic-edit-form');
            if (!form || form.classList.contains('hidden')) return;
            finishTopicTitleEdit(card, { saveIfChanged: true });
        }, 0);
    });
});

document.querySelectorAll('.subtopic-edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const li = this.closest('li');
        if (!li) return;
        li.querySelector('.subtopic-title-display')?.classList.add('hidden');
        li.querySelector('.subtopic-actions')?.classList.add('hidden');
        const form = li.querySelector('.subtopic-edit-form');
        const input = form && form.querySelector('.subtopic-title-input');
        if (form) form.classList.remove('hidden');
        if (input) {
            input.value = input.getAttribute('data-original-title') || input.value;
            input.focus();
            input.select();
        }
    });
});

document.querySelectorAll('.subtopic-title-input').forEach(input => {
    const li = input.closest('li');
    if (!li) return;
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            finishSubtopicTitleEdit(li, { saveIfChanged: false });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (!input.value.trim()) return;
            li.querySelector('.subtopic-edit-form').requestSubmit();
        }
    });
    input.addEventListener('blur', function() {
        setTimeout(() => {
            const form = li.querySelector('.subtopic-edit-form');
            if (!form || form.classList.contains('hidden')) return;
            finishSubtopicTitleEdit(li, { saveIfChanged: true });
        }, 0);
    });
});

document.querySelectorAll('.syllabus-schedule-toggle').forEach(btn => {
    btn.addEventListener('click', function() {
        const block = this.closest('.syllabus-schedule-block');
        if (!block) return;
        const panel = block.querySelector('.syllabus-schedule-panel');
        if (!panel) return;
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            panel.querySelector('input[type="date"]')?.focus();
        }
    });
});
</script>
@endpush
@endsection

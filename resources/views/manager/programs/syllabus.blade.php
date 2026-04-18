@extends('manager.layouts.app')

@section('title', 'Syllabus')
@section('title_suffix', '')

@section('content')
<div class="mb-6 flex flex-wrap items-start justify-between gap-4 print:hidden">
    <div>
        <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
            <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </span>
            Syllabus - {{ $program->name }}
        </h1>
        <p class="mt-2 text-slate-600">Add syllabus topics or units and subtopics. Mark each as complete as you teach it. Under each subtopic you can add coding assignments for Judge0-style delivery.</p>
    </div>
    <div class="print:hidden">
        <button type="button" onclick="if(location.hash){history.replaceState(null,'',location.pathname+location.search);}window.print();" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2h-6a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print
        </button>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6 print:hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add topic/unit name</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.syllabus.topics.store', $program) }}" method="POST" class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Topic or unit name</label>
                <input type="text" name="title" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" placeholder="e.g. Unit 1 — Introduction to Python" required>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Add topic/unit name</button>
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
        <tr><td class="syllabus-doc-label">Semester/program name</td><td>{{ $program->name }}</td></tr>
        @if($program->event)
            <tr><td class="syllabus-doc-label">Year/Event</td><td>{{ $program->event->name }}</td></tr>
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
    <div class="topic-card bg-white rounded-card border border-border shadow-card overflow-hidden mb-4 print:hidden" id="topic-{{ $topic->id }}">
        <div class="px-5 py-4 border-b border-border bg-slate-50/80">
            <div class="syllabus-schedule-block mb-3">
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
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ trim(($topic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '')) }}
                        </span>
                    @endif
                    <div class="topic-actions inline-flex items-center gap-1 shrink-0">
                        <button type="button" class="topic-edit-btn p-1.5 rounded text-slate-500 hover:bg-slate-200 hover:text-slate-700" title="Edit topic or unit name">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <form action="{{ route('manager.program.syllabus.topics.destroy', [$program, $topic]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this topic/unit and all its subtopics?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded text-slate-500 hover:bg-red-100 hover:text-red-600" title="Delete topic or unit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                        <button type="button" class="syllabus-schedule-toggle p-1.5 rounded text-slate-500 hover:bg-slate-200 hover:text-slate-700" title="Schedule date and time" aria-label="Schedule date and time">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                    </div>
                </div>
                <div class="syllabus-schedule-panel hidden mt-2">
                    <form action="{{ route('manager.program.syllabus.topics.schedule', [$program, $topic]) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <label class="text-sm text-slate-500">Date</label>
                        <input type="date" name="scheduled_date" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[150px]" value="{{ $topic->scheduled_date?->format('Y-m-d') }}">
                        <label class="text-sm text-slate-500">Time</label>
                        <input type="time" name="scheduled_time" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[125px]" value="{{ $topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '' }}">
                        <button type="submit" class="px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 border border-border hover:bg-slate-50">Set</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-5 pt-0">
            <ul class="space-y-0">
                @forelse($topic->subtopics as $subtopic)
                    <li class="border-b border-border last:border-0 syllabus-schedule-block">
                        <div class="flex flex-wrap items-center gap-2 py-2 pl-4 min-w-0 w-full">
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
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ trim(($subtopic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($subtopic->scheduled_time ? substr($subtopic->scheduled_time, 0, 5) : '')) }}
                                </span>
                            @endif
                            @if($subtopic->assignments->isNotEmpty())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-primary/15 text-primary shrink-0" title="Coding assignments on this subtopic">
                                    {{ $subtopic->assignments->count() }} assignment{{ $subtopic->assignments->count() === 1 ? '' : 's' }}
                                </span>
                            @endif
                            <div class="subtopic-actions inline-flex items-center gap-0.5 shrink-0 flex-wrap justify-end">
                                <a href="{{ route('manager.program.syllabus.assignments.create', [$program, $subtopic]) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium text-primary border border-primary/25 hover:bg-primary/10" title="Create coding assignment for this subtopic">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Assignment
                                </a>
                                <button type="button" class="subtopic-edit-btn p-1 rounded text-slate-400 hover:text-slate-600" title="Edit"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <form action="{{ route('manager.program.syllabus.subtopics.destroy', [$program, $subtopic]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subtopic?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-red-600" title="Delete"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                                <button type="button" class="syllabus-schedule-toggle p-1 rounded text-slate-400 hover:text-slate-600" title="Schedule date and time" aria-label="Schedule date and time">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
                            </div>
                        </div>
                        <div class="syllabus-schedule-panel hidden pb-2 pl-4">
                            <form action="{{ route('manager.program.syllabus.subtopics.schedule', [$program, $subtopic]) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                @csrf
                                <label class="text-sm text-slate-500">Date</label>
                                <input type="date" name="scheduled_date" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[150px] text-sm" value="{{ $subtopic->scheduled_date?->format('Y-m-d') }}">
                                <label class="text-sm text-slate-500">Time</label>
                                <input type="time" name="scheduled_time" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[125px] text-sm" value="{{ $subtopic->scheduled_time ? substr($subtopic->scheduled_time, 0, 5) : '' }}">
                                <button type="submit" class="px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 border border-border hover:bg-slate-50">Set</button>
                            </form>
                        </div>
                        @if($subtopic->assignments->isNotEmpty())
                            <ul class="mt-1 mb-1 ml-4 border-l-2 border-primary/20 pl-3 space-y-1.5 print:hidden">
                                @foreach($subtopic->assignments as $syllabusAssignment)
                                    <li class="flex flex-wrap items-center gap-x-2 gap-y-1 py-0.5 text-sm">
                                        <span class="text-slate-800">{{ $syllabusAssignment->title }}</span>
                                        <span class="text-xs text-slate-500 capitalize">({{ $syllabusAssignment->difficulty }})</span>
                                        <a href="{{ route('manager.program.syllabus.assignments.edit', [$program, $syllabusAssignment]) }}" class="text-xs font-semibold text-primary hover:text-primary-hover hover:underline">Edit</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="py-2 pl-4 text-sm text-slate-500">No subtopics yet. Add one below.</li>
                @endforelse
            </ul>
            <form action="{{ route('manager.program.syllabus.subtopics.store', [$program, $topic]) }}" method="POST" class="mt-4 pt-4 border-t border-border flex flex-wrap items-center gap-2">
                @csrf
                <input type="text" name="title" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary min-w-[200px] sm:min-w-[320px] flex-1" placeholder="Add subtopic..." required>
                <button type="submit" class="shrink-0 px-4 py-2 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">Add Subtopic</button>
            </form>
        </div>
    </div>
@empty
    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden print:hidden">
        <div class="p-12 text-center text-slate-500">
            <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <p class="mt-2">No topics or units yet. Add your first topic or unit above.</p>
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
        const panel = block && block.querySelector('.syllabus-schedule-panel');
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

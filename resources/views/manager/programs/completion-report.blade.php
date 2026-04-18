@extends('manager.layouts.app')

@section('title', 'Completion Report')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </span>
        Completion Report - {{ $program->name }}
    </h1>
    <div class="flex gap-2">
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
        <p class="text-sm text-slate-600 mb-4"><strong>Status:</strong> {{ $program->status }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Topics</p>
                <p class="text-xl font-semibold text-slate-800">{{ $completedTopics }}/{{ $totalTopics }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Subtopics</p>
                <p class="text-xl font-semibold text-slate-800">{{ $completedSubtopics }}/{{ $totalSubtopics }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Students</p>
                <p class="text-xl font-semibold text-slate-800">{{ $program->students->count() }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sessions</p>
                <p class="text-xl font-semibold text-slate-800">{{ $program->sessions->count() }}</p>
            </div>
        </div>

        @if($latestCompletion)
        <div class="mb-6 rounded-lg border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">Latest Completion Request</h3>
            <p class="text-sm text-slate-600"><strong>Status:</strong> {{ $latestCompletion->status }}</p>
            <p class="text-sm text-slate-600"><strong>Submitted:</strong> {{ $latestCompletion->created_at->format('F d, Y H:i') }}</p>
            @if($latestCompletion->notes)
                <p class="text-sm text-slate-600 mt-2"><strong>Notes:</strong> {{ $latestCompletion->notes }}</p>
            @endif
        </div>
        @endif

        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Syllabus Completion</h3>
        <div class="overflow-x-auto">
            <table class="w-full border border-border">
                <thead>
                    <tr class="bg-slate-100 border-b border-border">
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Topic</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Subtopics</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topics as $topic)
                        <tr class="border-b border-border odd:bg-slate-50/50">
                            <td class="px-4 py-2 text-sm">{{ $topic->title }}</td>
                            <td class="px-4 py-2 text-sm">
                                @foreach($topic->subtopics as $sub)
                                    <span class="{{ $sub->is_complete ? 'text-success' : 'text-slate-500' }}">{{ $sub->title }}</span>@if(!$loop->last), @endif
                                @endforeach
                                @if($topic->subtopics->isEmpty())—@endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $topic->is_complete ? 'bg-success/20 text-success' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $topic->is_complete ? 'Complete' : 'Incomplete' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">No syllabus topics.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Formal print document --}}
<div id="completion-report-print-document" class="hidden print:block completion-report-formal-doc">
    <div class="completion-report-doc-header">
        @include('partials.formal-report-logo')
        <h1 class="completion-report-doc-title">COMPLETION REPORT</h1>
        @if($program->college)
            <p class="completion-report-doc-subtitle">Submitted to {{ $program->college->name }}</p>
        @endif
        <p class="completion-report-doc-date">Date: {{ now()->format('d F Y') }}</p>
    </div>

    <table class="completion-report-doc-info-table">
        <tr><td class="completion-report-doc-label">Program Name</td><td>{{ $program->name }}</td></tr>
        @if($program->event)
            <tr><td class="completion-report-doc-label">Year/Event</td><td>{{ $program->event->name }}</td></tr>
        @endif
        @if($program->type)
            <tr><td class="completion-report-doc-label">Type</td><td>{{ $program->type }}</td></tr>
        @endif
        @if($program->departmentsLabel() !== '')
            <tr><td class="completion-report-doc-label">Departments</td><td>{{ $program->departmentsLabel() }}</td></tr>
        @endif
        <tr><td class="completion-report-doc-label">Status</td><td>{{ $program->status }}</td></tr>
        <tr><td class="completion-report-doc-label">Trainer</td><td>{{ $program->executorLabel() }}</td></tr>
        <tr><td class="completion-report-doc-label">Topics Completed</td><td>{{ $completedTopics }}/{{ $totalTopics }}</td></tr>
        <tr><td class="completion-report-doc-label">Subtopics Completed</td><td>{{ $completedSubtopics }}/{{ $totalSubtopics }}</td></tr>
        <tr><td class="completion-report-doc-label">Students</td><td>{{ $program->students->count() }}</td></tr>
        <tr><td class="completion-report-doc-label">Sessions</td><td>{{ $program->sessions->count() }}</td></tr>
    </table>

    @if($latestCompletion)
    <h2 class="completion-report-doc-section">Latest Closure Request</h2>
    <table class="completion-report-doc-info-table">
        <tr><td class="completion-report-doc-label">Status</td><td>{{ $latestCompletion->status }}</td></tr>
        <tr><td class="completion-report-doc-label">Submitted</td><td>{{ $latestCompletion->created_at->format('d F Y H:i') }}</td></tr>
        @if($latestCompletion->notes)
            <tr><td class="completion-report-doc-label">Notes</td><td>{{ $latestCompletion->notes }}</td></tr>
        @endif
    </table>
    @endif

    <h2 class="completion-report-doc-section">Syllabus Completion</h2>
    <table class="completion-report-doc-table">
        <thead>
            <tr>
                <th>Topic</th>
                <th>Subtopics</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topics as $topic)
                <tr>
                    <td>{{ $topic->title }}</td>
                    <td>
                        @foreach($topic->subtopics as $sub)
                            {{ $sub->title }}@if(!$loop->last), @endif
                        @endforeach
                        @if($topic->subtopics->isEmpty())—@endif
                    </td>
                    <td>{{ $topic->is_complete ? 'Complete' : 'Incomplete' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No syllabus topics.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .completion-report-formal-doc {
        font-family: 'Times New Roman', Times, serif;
        max-width: 100%;
        padding: 24px;
        margin: 0;
        color: #1e293b;
        border: 1px solid #1e293b;
    }
    .completion-report-doc-header {
        text-align: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #1e293b;
    }
    .completion-report-doc-title {
        font-size: 22pt;
        font-weight: 700;
        letter-spacing: 0.1em;
        margin: 0 0 8px 0;
    }
    .completion-report-doc-subtitle {
        font-size: 11pt;
        margin: 0 0 4px 0;
    }
    .completion-report-doc-date {
        font-size: 10pt;
        margin: 0;
    }
    .completion-report-doc-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
        font-size: 11pt;
    }
    .completion-report-doc-info-table td {
        padding: 6px 12px;
        border: 1px solid #64748b;
        vertical-align: top;
    }
    .completion-report-doc-label {
        width: 160px;
        font-weight: 600;
        background: #f8fafc;
    }
    .completion-report-doc-section {
        font-size: 14pt;
        font-weight: 700;
        margin: 0 0 16px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #94a3b8;
    }
    .completion-report-doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11pt;
    }
    .completion-report-doc-table th,
    .completion-report-doc-table td {
        padding: 8px 12px;
        border: 1px solid #64748b;
        text-align: left;
    }
    .completion-report-doc-table th {
        font-weight: 600;
        background: #f8fafc;
    }
    .completion-report-doc-table tbody tr:nth-child(even) {
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
        #completion-report-print-document {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
            min-height: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }
        .completion-report-formal-doc {
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

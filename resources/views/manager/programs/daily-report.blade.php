@extends('manager.layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </span>
        Daily Report
    </h1>
    <div class="flex gap-2">
        <a href="{{ route('manager.program.sessions.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back to Daily Report</a>
        <a href="{{ route('manager.program.attendance.edit', [$program, $session]) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Edit Syllabus Taught</a>
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

        <div class="mb-6">
            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-2">Syllabus Taught</h3>
            @if($taughtTopics->isNotEmpty())
                <ul class="list-disc list-inside space-y-1 text-sm text-slate-700">
                    @foreach($taughtTopics as $topic)
                        <li>
                            <strong>{{ $topic->title }}</strong>
                            @if($topic->subtopics->isNotEmpty())
                                <ul class="ml-4 mt-1 list-disc list-inside space-y-0.5">
                                    @foreach($topic->subtopics as $sub)
                                        <li>{{ $sub->title }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-slate-500 italic">No syllabus recorded for this session. <a href="{{ route('manager.program.attendance.edit', [$program, $session]) }}" class="text-primary hover:underline">Edit attendance</a> to add syllabus taught.</p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Students</p>
                <p class="text-xl font-semibold text-slate-800">{{ $totalCount }}</p>
            </div>
            <div class="rounded-lg border border-success/30 bg-success/10 p-3">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Students Present</p>
                <p class="text-xl font-semibold text-success">{{ $presentCount }}</p>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Optional: Add your photo with students (geo-tagged)</label>
            <input type="file" id="daily-report-photo-input" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            <div id="daily-report-photo-preview" class="mt-3 min-h-[120px] rounded-lg border-2 border-dashed border-slate-300 bg-slate-50/50 flex items-center justify-center p-4 overflow-hidden">
                <p class="text-sm text-slate-500 text-center">No image selected. Select an image to include in the report.</p>
            </div>
        </div>
    </div>
</div>

{{-- Formal print document --}}
<div id="daily-report-print-document" class="hidden print:block daily-report-formal-doc">
    <div class="daily-report-doc-header">
        <h1 class="daily-report-doc-title">DAILY REPORT</h1>
        @if($program->college)
            <p class="daily-report-doc-subtitle">Submitted to {{ $program->college->name }}</p>
        @endif
        <p class="daily-report-doc-date">Date: {{ $session->session_date->format('d F Y') }}</p>
    </div>

    <table class="daily-report-doc-info-table">
        <tr><td class="daily-report-doc-label">Program Name</td><td>{{ $program->name }}</td></tr>
        @if($program->event)
            <tr><td class="daily-report-doc-label">Event</td><td>{{ $program->event->name }}</td></tr>
        @endif
        <tr><td class="daily-report-doc-label">Session</td><td>{{ $session->title }}</td></tr>
        <tr><td class="daily-report-doc-label">Date</td><td>{{ $session->session_date->format('d F Y') }}</td></tr>
        <tr><td class="daily-report-doc-label">Time</td><td>{{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</td></tr>
        @if($program->department)
            <tr><td class="daily-report-doc-label">Department</td><td>{{ $program->department }}</td></tr>
        @endif
        <tr><td class="daily-report-doc-label">Trainer</td><td>{{ $program->executorLabel() }}</td></tr>
        <tr><td class="daily-report-doc-label">Total Students</td><td>{{ $totalCount }}</td></tr>
        <tr><td class="daily-report-doc-label">Students Present</td><td>{{ $presentCount }}</td></tr>
    </table>

    <h2 class="daily-report-doc-section">Syllabus Taught</h2>
    <div class="daily-report-doc-content">
        @if($taughtTopics->isNotEmpty())
            @foreach($taughtTopics as $topic)
                <div class="daily-report-topic-item">
                    <p class="daily-report-topic-title">{{ $topic->title }}</p>
                    @if($topic->subtopics->isNotEmpty())
                        <ul class="daily-report-subtopic-list">
                            @foreach($topic->subtopics as $sub)
                                <li>{{ $sub->title }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @else
            <p class="daily-report-empty">No syllabus recorded for this session.</p>
        @endif
    </div>

    <div id="daily-report-photo-section" class="daily-report-photo-section daily-report-photo-empty">
        <div id="daily-report-print-photo" class="daily-report-photo-placeholder">
            <p class="daily-report-photo-default-text">Optional: Add your photo with students (geo-tagged) here.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var input = document.getElementById('daily-report-photo-input');
    var preview = document.getElementById('daily-report-photo-preview');
    var printPhoto = document.getElementById('daily-report-print-photo');
    var photoSection = document.getElementById('daily-report-photo-section');
    if (!input || !preview || !printPhoto || !photoSection) return;

    function hidePhotoSection() {
        photoSection.classList.add('daily-report-photo-empty');
        preview.innerHTML = '<p class="text-sm text-slate-500 text-center">No image selected. Select an image to include in the report.</p>';
        printPhoto.innerHTML = '<p class="daily-report-photo-default-text">Optional: Add your photo with students (geo-tagged) here.</p>';
    }

    function showPhotoSection(imgDataUrl) {
        photoSection.classList.remove('daily-report-photo-empty');
        preview.innerHTML = '<img src="' + imgDataUrl + '" alt="Photo with students" class="max-w-full max-h-[300px] object-contain">';
        printPhoto.innerHTML = '<img src="' + imgDataUrl + '" alt="Photo with students" class="max-w-full max-h-[400px] object-contain" style="max-height: 400px;">';
    }

    input.addEventListener('change', function() {
        var file = this.files[0];
        if (!file || !file.type.startsWith('image/')) {
            hidePhotoSection();
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            showPhotoSection(e.target.result);
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endpush

<style>
    .daily-report-formal-doc {
        font-family: 'Times New Roman', Times, serif;
        max-width: 100%;
        padding: 24px;
        margin: 0;
        color: #1e293b;
        border: 1px solid #1e293b;
    }
    .daily-report-doc-header {
        text-align: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #1e293b;
    }
    .daily-report-doc-title {
        font-size: 22pt;
        font-weight: 700;
        letter-spacing: 0.1em;
        margin: 0 0 8px 0;
    }
    .daily-report-doc-subtitle {
        font-size: 11pt;
        margin: 0 0 4px 0;
    }
    .daily-report-doc-date {
        font-size: 10pt;
        margin: 0;
    }
    .daily-report-doc-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
        font-size: 11pt;
    }
    .daily-report-doc-info-table td {
        padding: 6px 12px;
        border: 1px solid #64748b;
        vertical-align: top;
    }
    .daily-report-doc-label {
        width: 140px;
        font-weight: 600;
        background: #f8fafc;
    }
    .daily-report-doc-section {
        font-size: 14pt;
        font-weight: 700;
        margin: 0 0 16px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #94a3b8;
    }
    .daily-report-doc-content {
        font-size: 11pt;
        line-height: 1.5;
    }
    .daily-report-topic-item {
        margin-bottom: 16px;
    }
    .daily-report-topic-title {
        font-weight: 600;
        margin: 0 0 6px 0;
    }
    .daily-report-subtopic-list {
        margin: 0 0 0 24px;
        padding: 0;
        list-style-type: disc;
    }
    .daily-report-subtopic-list li {
        margin-bottom: 4px;
    }
    .daily-report-empty {
        font-style: italic;
        color: #64748b;
    }
    .daily-report-photo-section {
        margin-top: 32px;
        page-break-before: always;
        padding-top: 24px;
        border-top: 1px dashed #94a3b8;
    }
    .daily-report-photo-section.daily-report-photo-empty {
        display: none !important;
    }
    .daily-report-photo-placeholder {
        min-height: 200px;
        border: 2px dashed #94a3b8;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    .daily-report-photo-placeholder p,
    .daily-report-photo-default-text {
        font-size: 11pt;
        color: #64748b;
        text-align: center;
        margin: 0;
    }
    .daily-report-photo-placeholder img {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
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
        #daily-report-print-document {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
            min-height: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }
        .daily-report-formal-doc {
            overflow: visible !important;
        }
        .daily-report-topic-item {
            page-break-inside: auto;
        }
        .daily-report-photo-section.daily-report-photo-empty {
            display: none !important;
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

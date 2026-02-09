@extends('manager.layouts.app')

@section('title', 'Attendance Report')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Attendance Report</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('manager.program.sessions.index', $program) }}" class="btn btn-outline-secondary">Back to Sessions</a>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <div class="card printable-report">
        <div class="card-body">
            <h5 class="card-title">{{ $program->name }}</h5>
            <p class="mb-1"><strong>Session:</strong> {{ $session->title }}</p>
            <p class="mb-1"><strong>Date:</strong> {{ $session->session_date->format('F d, Y') }}</p>
            <p class="mb-3"><strong>Time:</strong> {{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}</p>

            <div class="row mb-4 summary-row">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body py-2">
                            <small class="text-muted">Total Students</small>
                            <div class="fs-5">{{ $students->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10">
                        <div class="card-body py-2">
                            <small class="text-muted">Present</small>
                            <div class="fs-5 text-success">{{ $presentCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger bg-opacity-10">
                        <div class="card-body py-2">
                            <small class="text-muted">Absent</small>
                            <div class="fs-5 text-danger">{{ $absentCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
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
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->student_identifier ?? '—' }}</td>
                                <td>{{ $student->department }}</td>
                                <td>
                                    <span class="badge {{ $status === 'Present' ? 'bg-success' : 'bg-danger' }}">{{ $status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No students enrolled.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .sidebar,
            .navbar,
            .btn,
            .alert,
            nav {
                display: none !important;
            }

            body {
                font-size: 10px;
            }

            h1, h2, h3, h4, h5 {
                margin: 0 0 0.25rem 0;
            }

            .d-flex.justify-content-between.mb-4 {
                margin-bottom: 0.5rem !important;
            }

            .printable-report {
                border: 1px solid #dee2e6;
                box-shadow: none !important;
                margin: 0;
            }

            .printable-report .card-body {
                padding: 0.35rem 0.5rem;
            }

            .printable-report p {
                margin-bottom: 0.2rem;
            }

            .row.mb-4 {
                margin-bottom: 0.5rem !important;
            }

            .row.mb-4 .card {
                margin-bottom: 0;
            }

            .summary-row {
                display: flex !important;
                gap: 4px;
            }

            .summary-row > [class^="col-"],
            .summary-row > [class*=" col-"] {
                flex: 1 1 0;
                max-width: 33%;
            }

            table.table {
                font-size: 9px;
            }

            table.table th,
            table.table td {
                padding: 0.18rem 0.3rem;
            }

            @page {
                margin: 8mm;
            }
        }
    </style>
@endsection

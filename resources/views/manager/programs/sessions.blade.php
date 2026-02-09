@extends('manager.layouts.app')

@section('title', 'Program Sessions')

@section('content')
    <h2 class="mb-4">Sessions - {{ $program->name }}</h2>

    <div class="card mb-4">
        <div class="card-header">Add Session</div>
        <div class="card-body">
            <form action="{{ route('manager.program.sessions.store', $program) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="session_date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Session List</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->session_date->format('Y-m-d') }}</td>
                                <td>
                                    {{ $session->start_time ?? '—' }} - {{ $session->end_time ?? '—' }}
                                </td>
                                <td>{{ $session->status }}</td>
                                <td class="text-end">
                                    <a href="{{ route('manager.program.attendance.edit', [$program, $session]) }}" class="btn btn-sm btn-outline-primary">
                                        Attendance
                                    </a>
                                    <a href="{{ route('manager.program.attendance.report', [$program, $session]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                        Generate Report
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No sessions created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

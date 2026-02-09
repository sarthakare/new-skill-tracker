@extends('manager.layouts.app')

@section('title', 'Program Dashboard')

@section('content')
    <h2 class="mb-4">{{ $program->name }} Dashboard</h2>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Status</div>
                    <div class="fs-5">{{ $stats['status'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Students</div>
                    <div class="fs-5">{{ $stats['students_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Sessions</div>
                    <div class="fs-5">{{ $stats['sessions_count'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Program Details</h5>
            <p class="mb-1"><strong>Event:</strong> {{ $stats['event_name'] }}</p>
            <p class="mb-1"><strong>Manager:</strong> {{ $stats['manager_name'] }}</p>
            <p class="mb-0"><strong>Pending Completion Requests:</strong> {{ $stats['pending_completion'] }}</p>
        </div>
    </div>
@endsection

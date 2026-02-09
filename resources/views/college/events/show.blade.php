@extends('college.layouts.app')

@section('title', 'Event Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-event"></i> {{ $event->name }}</h1>
    <div>
        <a href="{{ route('college.events.programs.create', $event) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Program
        </a>
        <a href="{{ route('college.events.edit', $event) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('college.events.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

{{-- Event Dashboard Summary --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 opacity-75">Programs</h6>
                <h2 class="mb-0">{{ $event->programs->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-{{ $event->status === 'Active' ? 'success' : ($event->status === 'Completed' ? 'secondary' : 'warning') }}">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 opacity-75">Status</h6>
                <h5 class="mb-0">{{ $event->status }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Owner</h6>
                <h5 class="mb-0">College</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Period</h6>
                <h6 class="mb-0">{{ $event->start_date->format('M d, Y') }} – {{ $event->end_date->format('M d, Y') }}</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Event Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Name:</th>
                        <td>{{ $event->name }}</td>
                    </tr>
                    @if($event->description)
                    <tr>
                        <th>Description:</th>
                        <td>{{ $event->description }}</td>
                    </tr>
                    @endif
                    @if($event->target_audience)
                    <tr>
                        <th>Target Audience:</th>
                        <td>{{ $event->target_audience }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Start Date:</th>
                        <td>{{ $event->start_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date:</th>
                        <td>{{ $event->end_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($event->status === 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($event->status === 'Archived')
                                <span class="badge bg-dark">Archived</span>
                            @elseif($event->status === 'Completed')
                                <span class="badge bg-secondary">Completed</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Programs</h5>
                <a href="{{ route('college.events.programs.create', $event) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Program
                </a>
            </div>
            <div class="card-body">
                @if($event->programs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Department</th>
                                    <th>Duration</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                    <th>Run by</th>
                                    <th>Program Manager</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->programs as $program)
                                    <tr>
                                        <td>{{ $program->name }}</td>
                                        <td>@if($program->type)<span class="badge bg-info">{{ $program->type }}</span>@else<span class="text-muted">—</span>@endif</td>
                                        <td>{{ $program->department }}</td>
                                        <td>{{ $program->duration_days }} Days</td>
                                        <td>{{ $program->mode }}</td>
                                        <td><span class="badge bg-{{ $program->status === 'Manager_Assigned' ? 'info' : ($program->status === 'Completed' ? 'success' : 'secondary') }}">{{ str_replace('_', ' ', $program->status) }}</span></td>
                                        <td>{{ $program->executorLabel() }}</td>
                                        <td>{{ $program->oversightManager?->name ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('college.events.programs.show', [$event, $program]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No programs yet. <a href="{{ route('college.events.programs.create', $event) }}">Add a program</a> and assign who runs it (Vendor or Independent Trainer) and an Internal Program Manager.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

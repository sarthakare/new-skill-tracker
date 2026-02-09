@extends('vendor.layouts.app')

@section('title', 'Event Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-speedometer2"></i> Event Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Event Name</h6>
                        <h4 class="mb-0">{{ $stats['event_name'] }}</h4>
                    </div>
                    <i class="bi bi-calendar-event" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Event Type</h6>
                        <h4 class="mb-0">{{ $stats['event_type'] }}</h4>
                    </div>
                    <i class="bi bi-tag" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Start Date</h6>
                        <h5 class="mb-0">{{ $stats['start_date'] }}</h5>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">End Date</h6>
                        <h5 class="mb-0">{{ $stats['end_date'] }}</h5>
                    </div>
                    <i class="bi bi-calendar-x" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Event Status</h6>
                        <h5 class="mb-0">
                            @if($stats['event_status'] === 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($stats['event_status'] === 'Completed')
                                <span class="badge bg-secondary">Completed</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </h5>
                    </div>
                    <i class="bi bi-info-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Event Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Event Name:</th>
                        <td><strong>{{ $event->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Event Type:</th>
                        <td><span class="badge bg-info">{{ $event->type }}</span></td>
                    </tr>
                    <tr>
                        <th>College:</th>
                        <td>{{ $event->college->name }}</td>
                    </tr>
                    <tr>
                        <th>Start Date:</th>
                        <td>{{ $event->start_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date:</th>
                        <td>{{ $event->end_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($event->status === 'Active')
                                <span class="badge bg-success">Active</span>
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

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Enabled Modules</h5>
            </div>
            <div class="card-body">
                @if($event->modules->where('is_enabled', true)->count() > 0)
                    <div class="row">
                        @foreach($event->modules->where('is_enabled', true) as $module)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> {{ $module->module_name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No modules are currently enabled for this event.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-shop"></i> Vendor Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Vendor Name:</th>
                        <td><strong>{{ $credential->vendor->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Vendor Type:</th>
                        <td><span class="badge bg-info">{{ $credential->vendor->type }}</span></td>
                    </tr>
                    <tr>
                        <th>Contact Email:</th>
                        <td>{{ $credential->vendor->contact_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Contact Phone:</th>
                        <td>{{ $credential->vendor->contact_phone ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Quick Stats</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success"></i> 
                        <strong>{{ $stats['modules_enabled'] }}</strong> of <strong>{{ $stats['total_modules'] }}</strong> modules enabled
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-calendar text-primary"></i> 
                        Event Duration: {{ $event->start_date->diffInDays($event->end_date) + 1 }} days
                    </li>
                    <li>
                        <i class="bi bi-building text-info"></i> 
                        College: {{ $event->college->name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

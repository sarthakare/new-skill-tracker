@extends('college.layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-shop"></i> {{ $vendor->name }}</h1>
    <div>
        <a href="{{ route('college.vendors.edit', $vendor) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('college.vendors.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Vendor Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Name:</th>
                        <td>{{ $vendor->name }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><span class="badge bg-info">{{ $vendor->type }}</span></td>
                    </tr>
                    <tr>
                        <th>Contact Email:</th>
                        <td>{{ $vendor->contact_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Contact Phone:</th>
                        <td>{{ $vendor->contact_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{ $vendor->address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assigned Events</h5>
            </div>
            <div class="card-body">
                @if($vendor->events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendor->events as $event)
                                    <tr>
                                        <td>{{ $event->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $event->type }}</span></td>
                                        <td>
                                            @if($event->status === 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($event->status === 'Completed')
                                                <span class="badge bg-secondary">Completed</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('college.vendors.remove-from-event', [$vendor, $event]) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to remove this vendor from the event?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No events assigned.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assign to Event</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('college.vendors.assign-event', $vendor) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="event_id" class="form-label">Event <span class="text-danger">*</span></label>
                        <select class="form-select @error('event_id') is-invalid @enderror" 
                                id="event_id" 
                                name="event_id" 
                                required>
                            <option value="">Select Event</option>
                            @foreach(\App\Models\Event::where('college_id', Auth::user()->college_id)->where('status', '!=', 'Draft')->get() as $event)
                                @if(!$vendor->events->contains($event))
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('event_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Assign to Event</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

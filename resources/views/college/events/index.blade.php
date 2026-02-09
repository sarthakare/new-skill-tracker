@extends('college.layouts.app')

@section('title', 'Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-event"></i> Events</h1>
    <a href="{{ route('college.events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Event
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->start_date->format('M d, Y') }}</td>
                            <td>{{ $event->end_date->format('M d, Y') }}</td>
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
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('college.events.show', $event) }}" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('college.events.edit', $event) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($event->status !== 'Archived')
                                        <form action="{{ route('college.events.toggle-status', $event) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @php
                                                $buttonClass = $event->status === 'Active' ? 'secondary' : 'success';
                                                $buttonTitle = $event->status === 'Completed' ? 'Archive' : ($event->status === 'Active' ? 'Complete' : 'Activate');
                                                $buttonIcon = $event->status === 'Completed' ? 'archive' : ($event->status === 'Active' ? 'check2-all' : 'play-circle');
                                            @endphp
                                            <button type="submit" class="btn btn-sm btn-{{ $buttonClass }}" title="{{ $buttonTitle }}">
                                                <i class="bi bi-{{ $buttonIcon }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('college.events.destroy', $event) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection

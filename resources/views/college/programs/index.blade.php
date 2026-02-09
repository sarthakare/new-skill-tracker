@extends('college.layouts.app')

@section('title', 'Programs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Programs for {{ $event->name }}</h2>
            <p class="text-muted mb-0">Manage programs and manager assignments.</p>
        </div>
        <a href="{{ route('college.events.programs.create', $event) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Program
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Department</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Run by</th>
                            <th>Program Manager</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $program)
                            <tr>
                                <td>{{ $program->name }}</td>
                                <td>@if($program->type)<span class="badge bg-info">{{ $program->type }}</span>@else<span class="text-muted">—</span>@endif</td>
                                <td>{{ $program->department }}</td>
                                <td>{{ $program->duration_days }} days</td>
                                <td><span class="badge bg-secondary">{{ $program->status }}</span></td>
                                <td>{{ $program->executorLabel() }}</td>
                                <td>{{ $program->oversightManager?->name ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('college.events.programs.show', [$event, $program]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('college.events.programs.edit', [$event, $program]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('college.events.programs.destroy', [$event, $program]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this program?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No programs created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $programs->links() }}
    </div>
@endsection

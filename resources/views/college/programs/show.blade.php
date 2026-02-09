@extends('college.layouts.app')

@section('title', 'Program Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $program->name }}</h2>
            <p class="text-muted mb-0">Event: {{ $event->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('college.events.programs.edit', [$event, $program]) }}" class="btn btn-outline-primary">Edit Program</a>
            <a href="{{ route('college.events.programs.index', $event) }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Type</div>
                    <div class="fs-5">@if($program->type)<span class="badge bg-info">{{ $program->type }}</span>@else—@endif</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Run by</div>
                    <div class="fs-5">{{ $program->executorLabel() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Program Manager</div>
                    <div class="fs-5">{{ $program->oversightManager?->name ?? '—' }}</div>
                    @if($program->oversightManager)
                        <small class="text-muted">{{ $program->oversightManager->department }}</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted">Students</div>
                    <div class="fs-5">{{ $program->students->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Manager Credentials</div>
        <div class="card-body">
            @if(!empty($generatedCredentials))
                <div class="alert alert-warning">
                    <strong>New credentials generated:</strong> Copy these now; they are shown once.
                </div>
                <ul class="list-group mb-3">
                    @foreach($generatedCredentials as $generated)
                        <li class="list-group-item">
                            <div><strong>Username:</strong> {{ $generated['username'] }}</div>
                            <div><strong>Password:</strong> {{ $generated['password'] }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($credentials as $credential)
                            <tr>
                                <td>{{ $credential->username }}</td>
                                <td>{{ $credential->status }}</td>
                                <td>{{ $credential->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No credentials found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Completion Requests</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($program->completionRequests as $request)
                            <tr>
                                <td>{{ $request->status }}</td>
                                <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                <td>{{ $request->notes ?? '—' }}</td>
                                <td class="text-end">
                                    @if($request->status === 'pending')
                                        <form action="{{ route('college.programs.approve-completion', [$event, $program]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Reviewed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No completion requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

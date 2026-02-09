@extends('college.layouts.app')

@section('title', 'Program Completions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clipboard-check"></i> Program Completions</h1>
    <a href="{{ route('college.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($requests->isEmpty())
            <p class="text-muted mb-0">No completion requests yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Program</th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Documents</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->program->event->name ?? '—' }}</td>
                                <td>{{ $request->program->name ?? '—' }}</td>
                                <td>{{ $request->requestedBy?->managerLabel() ?? '—' }}</td>
                                <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $request->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($request->notes ?? '—', 80) }}</td>
                                <td>
                                    @if(!empty($request->attachments))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($request->attachments as $path)
                                                <li>
                                                    <a href="{{ Storage::url($path) }}" target="_blank">
                                                        {{ basename($path) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($request->program && $request->program->event)
                                        <a href="{{ route('college.events.programs.show', [$request->program->event, $request->program]) }}"
                                           class="btn btn-sm btn-primary">
                                            Review
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection


@extends('college.layouts.app')

@section('title', 'Independent Trainers')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Independent Trainers</h2>
        <a href="{{ route('college.independent-trainers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Trainer
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Expertise</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trainers as $trainer)
                            <tr>
                                <td>{{ $trainer->name }}</td>
                                <td>{{ $trainer->expertise ?? '—' }}</td>
                                <td>{{ $trainer->email ?? '—' }}</td>
                                <td>{{ $trainer->phone ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('college.independent-trainers.edit', $trainer) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('college.independent-trainers.destroy', $trainer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this trainer?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No trainers added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $trainers->links() }}
    </div>
@endsection

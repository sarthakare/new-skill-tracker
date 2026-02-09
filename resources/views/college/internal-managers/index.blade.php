@extends('college.layouts.app')

@section('title', 'Internal Managers')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Internal Managers</h2>
        <a href="{{ route('college.internal-managers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Manager
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($managers as $manager)
                            <tr>
                                <td>{{ $manager->name }}</td>
                                <td>{{ $manager->department }}</td>
                                <td>{{ $manager->email ?? '—' }}</td>
                                <td>{{ $manager->phone ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('college.internal-managers.edit', $manager) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('college.internal-managers.destroy', $manager) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this manager?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No internal managers added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $managers->links() }}
    </div>
@endsection

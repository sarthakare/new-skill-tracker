@extends('college.layouts.app')

@section('title', 'Event Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Event Users - {{ $event->name }}</h1>
    <a href="{{ route('college.events.show', $event) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Event
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Assigned Users</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eventUsers as $eventUser)
                                <tr>
                                    <td>{{ $eventUser->user->name }}</td>
                                    <td>{{ $eventUser->user->email }}</td>
                                    <td><span class="badge bg-info">{{ $eventUser->role }}</span></td>
                                    <td>
                                        <form action="{{ route('college.events.users.destroy', [$event, $eventUser]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to remove this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No users assigned to this event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $eventUsers->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assign User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('college.events.users.store', $event) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" 
                                id="user_id" 
                                name="user_id" 
                                required>
                            <option value="">Select User</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role" 
                                required>
                            <option value="">Select Role</option>
                            <option value="Event Admin" {{ old('role') === 'Event Admin' ? 'selected' : '' }}>Event Admin</option>
                            <option value="Trainer" {{ old('role') === 'Trainer' ? 'selected' : '' }}>Trainer</option>
                            <option value="Judge" {{ old('role') === 'Judge' ? 'selected' : '' }}>Judge</option>
                            <option value="Coordinator" {{ old('role') === 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                            <option value="Participant" {{ old('role') === 'Participant' ? 'selected' : '' }}>Participant</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Assign User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

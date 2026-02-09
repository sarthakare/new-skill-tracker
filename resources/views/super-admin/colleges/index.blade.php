@extends('super-admin.layouts.app')

@section('title', 'Colleges')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> Colleges</h1>
    <a href="{{ route('super-admin.colleges.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create College with Admin
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
                        <th>Code</th>
                        <th>Contact Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colleges as $college)
                        <tr>
                            <td>{{ $college->id }}</td>
                            <td>{{ $college->name }}</td>
                            <td><span class="badge bg-secondary">{{ $college->code }}</span></td>
                            <td>{{ $college->contact_email }}</td>
                            <td>
                                @if($college->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('super-admin.colleges.show', $college) }}" 
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.colleges.edit', $college) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('super-admin.colleges.toggle-status', $college) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm {{ $college->status === 'active' ? 'btn-secondary' : 'btn-success' }}"
                                                title="{{ $college->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $college->status === 'active' ? 'x-circle' : 'check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('super-admin.colleges.destroy', $college) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this college?');">
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
                            <td colspan="6" class="text-center">No colleges found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $colleges->links() }}
        </div>
    </div>
</div>
@endsection

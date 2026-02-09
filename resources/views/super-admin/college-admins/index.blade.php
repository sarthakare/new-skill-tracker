@extends('super-admin.layouts.app')

@section('title', 'College Admins')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> College Admins</h1>
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
                        <th>Email</th>
                        <th>College</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collegeAdmins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @if($admin->college)
                                    <span class="badge bg-info">{{ $admin->college->name }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('super-admin.college-admins.show', $admin) }}" 
                                   class="btn btn-sm btn-info" title="View Credentials">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No college admin credentials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $collegeAdmins->links() }}
        </div>
    </div>
</div>
@endsection

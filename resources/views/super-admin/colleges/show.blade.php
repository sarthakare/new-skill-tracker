@extends('super-admin.layouts.app')

@section('title', 'View College')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> View College</h1>
    <div>
        <a href="{{ route('super-admin.colleges.edit', $college) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('super-admin.colleges.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $college->id }}</dd>

            <dt class="col-sm-3">Name:</dt>
            <dd class="col-sm-9">{{ $college->name }}</dd>

            <dt class="col-sm-3">Code:</dt>
            <dd class="col-sm-9"><span class="badge bg-secondary">{{ $college->code }}</span></dd>

            <dt class="col-sm-3">Contact Email:</dt>
            <dd class="col-sm-9">{{ $college->contact_email }}</dd>

            <dt class="col-sm-3">College Admin:</dt>
            <dd class="col-sm-9">
                @php $admin = $college->collegeAdmins()->first(); @endphp
                @if($admin)
                    <a href="{{ route('super-admin.college-admins.show', $admin) }}">{{ $admin->name }}</a>
                    <span class="text-muted">({{ $admin->email }})</span>
                @else
                    <span class="text-muted">No admin assigned</span>
                @endif
            </dd>

            <dt class="col-sm-3">Status:</dt>
            <dd class="col-sm-9">
                @if($college->status === 'active')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </dd>

            <dt class="col-sm-3">Created At:</dt>
            <dd class="col-sm-9">{{ $college->created_at->format('M d, Y H:i') }}</dd>

            <dt class="col-sm-3">Updated At:</dt>
            <dd class="col-sm-9">{{ $college->updated_at->format('M d, Y H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection

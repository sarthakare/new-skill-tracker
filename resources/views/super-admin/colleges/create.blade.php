@extends('super-admin.layouts.app')

@section('title', 'Create College with Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-building"></i> Create College with Admin</h1>
    <a href="{{ route('super-admin.colleges.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('super-admin.colleges.store') }}" method="POST">
            @csrf

            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-building"></i> College Details</h5>

            <div class="mb-3">
                <label for="name" class="form-label">College Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">College Code <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('code') is-invalid @enderror" 
                       id="code" 
                       name="code" 
                       value="{{ old('code') }}" 
                       required>
                <small class="form-text text-muted">Unique code for the college (e.g., ABC123)</small>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                <input type="email" 
                       class="form-control @error('contact_email') is-invalid @enderror" 
                       id="contact_email" 
                       name="contact_email" 
                       value="{{ old('contact_email') }}" 
                       required>
                @error('contact_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person-badge"></i> College Admin Credentials</h5>
            <p class="text-muted small">Create the admin account that will manage this college.</p>

            <div class="mb-3">
                <label for="admin_name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('admin_name') is-invalid @enderror" 
                       id="admin_name" 
                       name="admin_name" 
                       value="{{ old('admin_name') }}" 
                       required>
                @error('admin_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                <input type="email" 
                       class="form-control @error('admin_email') is-invalid @enderror" 
                       id="admin_email" 
                       name="admin_email" 
                       value="{{ old('admin_email') }}" 
                       required>
                <small class="form-text text-muted">Used for login</small>
                @error('admin_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="admin_password" class="form-label">Admin Password <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control @error('admin_password') is-invalid @enderror" 
                       id="admin_password" 
                       name="admin_password" 
                       required>
                <small class="form-text text-muted">Minimum 8 characters. Save this securely after creation.</small>
                @error('admin_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="admin_password_confirmation" class="form-label">Confirm Admin Password <span class="text-danger">*</span></label>
                <input type="password" 
                       class="form-control" 
                       id="admin_password_confirmation" 
                       name="admin_password_confirmation" 
                       required>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('super-admin.colleges.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create College & Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

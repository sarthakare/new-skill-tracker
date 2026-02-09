@extends('college.layouts.app')

@section('title', 'Create Vendor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-shop"></i> Create Vendor</h1>
    <a href="{{ route('college.vendors.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('college.vendors.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
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
                <label for="type" class="form-label">Vendor Type <span class="text-danger">*</span></label>
                <select class="form-select @error('type') is-invalid @enderror" 
                        id="type" 
                        name="type" 
                        required>
                    <option value="">Select Type</option>
                    <option value="Training" {{ old('type') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Certification" {{ old('type') === 'Certification' ? 'selected' : '' }}>Certification</option>
                    <option value="Logistics" {{ old('type') === 'Logistics' ? 'selected' : '' }}>Logistics</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" 
                       class="form-control @error('contact_email') is-invalid @enderror" 
                       id="contact_email" 
                       name="contact_email" 
                       value="{{ old('contact_email') }}">
                @error('contact_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input type="text" 
                       class="form-control @error('contact_phone') is-invalid @enderror" 
                       id="contact_phone" 
                       name="contact_phone" 
                       value="{{ old('contact_phone') }}">
                @error('contact_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" 
                          id="address" 
                          name="address" 
                          rows="3">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('college.vendors.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection

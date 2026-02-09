@extends('super-admin.layouts.app')

@section('title', 'College Admin Credentials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> College Admin Credentials</h1>
    <a href="{{ route('super-admin.college-admins.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@if(session('college_admin_created') && $generatedPassword)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle"></i> Important:</strong> Please save these credentials now. The password will not be shown again.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="card border-warning mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="bi bi-key"></i> Login Credentials</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="email-display" value="{{ $collegeAdmin->email }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('email-display')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Password:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="password-display" value="{{ $generatedPassword }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('password-display')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle"></i> <strong>Note:</strong> These credentials are shown only once. Please save them securely.
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">College Admin Details</h5>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $collegeAdmin->id }}</dd>

            <dt class="col-sm-3">Name:</dt>
            <dd class="col-sm-9">{{ $collegeAdmin->name }}</dd>

            <dt class="col-sm-3">Email:</dt>
            <dd class="col-sm-9">{{ $collegeAdmin->email }}</dd>

            <dt class="col-sm-3">Role:</dt>
            <dd class="col-sm-9"><span class="badge bg-primary">{{ $collegeAdmin->role }}</span></dd>

            <dt class="col-sm-3">College:</dt>
            <dd class="col-sm-9">
                @if($collegeAdmin->college)
                    <span class="badge bg-info">{{ $collegeAdmin->college->name }} ({{ $collegeAdmin->college->code }})</span>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </dd>

            <dt class="col-sm-3">Created At:</dt>
            <dd class="col-sm-9">{{ $collegeAdmin->created_at->format('M d, Y H:i') }}</dd>

            <dt class="col-sm-3">Updated At:</dt>
            <dd class="col-sm-9">{{ $collegeAdmin->updated_at->format('M d, Y H:i') }}</dd>
        </dl>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(element.value).then(function() {
        // Show a temporary success message
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endpush
@endsection

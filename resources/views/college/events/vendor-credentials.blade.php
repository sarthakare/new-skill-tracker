@extends('college.layouts.app')

@section('title', 'Vendor Credentials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-key"></i> Vendor Credentials - {{ $event->name }}</h1>
    <a href="{{ route('college.events.show', $event) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Event
    </a>
</div>

@if(session('generated_credentials') && !empty(session('generated_credentials')))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Important:</strong> These credentials are shown only once. Please save them immediately!
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-key"></i> Newly Generated Credentials</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('generated_credentials') as $cred)
                            <tr>
                                <td>
                                    <strong>{{ $cred['vendor']->name }}</strong><br>
                                    <small class="text-muted">{{ $cred['vendor']->type }}</small>
                                </td>
                                <td>
                                    <code id="username-{{ $cred['credential']->id }}">{{ $cred['username'] }}</code>
                                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                                            onclick="copyToClipboard('username-{{ $cred['credential']->id }}', this)">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </td>
                                <td>
                                    <code id="password-{{ $cred['credential']->id }}">{{ $cred['password'] }}</code>
                                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                                            onclick="copyToClipboard('password-{{ $cred['credential']->id }}', this)">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" 
                                            onclick="copyCredentials({{ $cred['credential']->id }}, '{{ $cred['username'] }}', '{{ $cred['password'] }}')">
                                        <i class="bi bi-clipboard-check"></i> Copy Both
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Vendor Credentials for This Event</h5>
    </div>
    <div class="card-body">
        @if($credentials->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>Type</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credentials as $credential)
                            <tr>
                                <td><strong>{{ $credential->vendor->name }}</strong></td>
                                <td><span class="badge bg-info">{{ $credential->vendor->type }}</span></td>
                                <td><code>{{ $credential->username }}</code></td>
                                <td>
                                    @if($credential->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $credential->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i>
                <strong>Note:</strong> Passwords are hashed and cannot be retrieved. If a vendor forgets their password, 
                you can regenerate credentials by removing and re-adding the vendor to the event.
            </div>
        @else
            <p class="text-muted">No vendor credentials found for this event.</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(elementId, button) {
        const element = document.getElementById(elementId);
        const text = element.textContent;
        
        navigator.clipboard.writeText(text).then(function() {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i> Copied!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        });
    }
    
    function copyCredentials(id, username, password) {
        const text = `Username: ${username}\nPassword: ${password}`;
        
        navigator.clipboard.writeText(text).then(function() {
            alert('Credentials copied to clipboard!');
        });
    }
</script>
@endpush
@endsection

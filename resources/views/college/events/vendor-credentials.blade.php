@extends('college.layouts.app')

@section('title', 'Vendor Credentials')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
        </span>
        Vendor Credentials - {{ $event->name }}
    </h1>
    <a href="{{ route('college.events.show', $event) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Event
    </a>
</div>

@if(session('generated_credentials') && !empty(session('generated_credentials')))
    <div class="mb-4 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span><strong>Important:</strong> These credentials are shown only once. Please save them immediately!</span>
    </div>

    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
        <div class="px-5 py-4 bg-primary/10 border-b border-border">
            <h2 class="text-lg font-semibold text-slate-800">Newly Generated Credentials</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                    <thead>
                        <tr class="bg-slate-100 border-b border-border">
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Vendor Name</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Username</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Password</th>
                            <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('generated_credentials') as $cred)
                            <tr class="border-b border-border">
                                <td class="px-5 py-3"><strong>{{ $cred['vendor']->name }}</strong><br><span class="text-sm text-slate-500">{{ $cred['vendor']->type }}</span></td>
                                <td class="px-5 py-3"><code id="username-{{ $cred['credential']->id }}" class="text-sm bg-slate-100 px-2 py-1 rounded">{{ $cred['username'] }}</code>
                                    <button type="button" class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium border border-slate-300 hover:bg-slate-50" onclick="copyToClipboard('username-{{ $cred['credential']->id }}', this)">Copy</button></td>
                                <td class="px-5 py-3"><code id="password-{{ $cred['credential']->id }}" class="text-sm bg-slate-100 px-2 py-1 rounded">{{ $cred['password'] }}</code>
                                    <button type="button" class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium border border-slate-300 hover:bg-slate-50" onclick="copyToClipboard('password-{{ $cred['credential']->id }}', this)">Copy</button></td>
                                <td class="px-5 py-3 text-right"><button type="button" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-white bg-primary hover:bg-primary-hover" onclick="copyCredentials({{ $cred['credential']->id }}, '{{ addslashes($cred['username']) }}', '{{ addslashes($cred['password']) }}')">Copy Both</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">All Vendor Credentials for This Event</h2>
    </div>
    <div class="p-5">
        @if($credentials->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-100 border-b border-border">
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Vendor Name</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Type</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Username</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credentials as $credential)
                            <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $credential->vendor->name }}</td>
                                <td class="px-5 py-3"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $credential->vendor->type }}</span></td>
                                <td class="px-5 py-3"><code class="text-sm bg-slate-100 px-2 py-0.5 rounded">{{ $credential->username }}</code></td>
                                <td class="px-5 py-3">@if($credential->status === 'active')<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>@else<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">Inactive</span>@endif</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $credential->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 p-4 rounded-lg bg-info-light border border-info/30 text-slate-700 text-sm">
                <strong>Note:</strong> Passwords are hashed and cannot be retrieved. If a vendor forgets their password, you can regenerate credentials by removing and re-adding the vendor to the event.
            </div>
        @else
            <p class="text-slate-500">No vendor credentials found for this event.</p>
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

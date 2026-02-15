@extends('super-admin.layouts.app')

@section('title', 'College Admin Credentials')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </span>
        College Admin Credentials
    </h1>
    <a href="{{ route('super-admin.college-admins.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

@if(session('college_admin_created') && $generatedPassword)
<div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 flex items-center gap-2">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
    <strong>Important:</strong> Please save these credentials now. The password will not be shown again.
</div>

<div class="bg-amber-50 rounded-card border border-amber-200 shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-amber-200 bg-amber-100/80">
        <h2 class="text-lg font-semibold text-slate-800">Login Credentials</h2>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email:</label>
                <div class="flex gap-2">
                    <input type="text" id="email-display" value="{{ $collegeAdmin->email }}" readonly class="flex-1 rounded-input border border-slate-300 bg-slate-50 text-slate-800">
                    <button type="button" onclick="copyToClipboard('email-display', this)" class="shrink-0 inline-flex items-center gap-1 px-3 py-2 rounded-button text-sm font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Copy
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password:</label>
                <div class="flex gap-2">
                    <input type="text" id="password-display" value="{{ $generatedPassword }}" readonly class="flex-1 rounded-input border border-slate-300 bg-slate-50 text-slate-800">
                    <button type="button" onclick="copyToClipboard('password-display', this)" class="shrink-0 inline-flex items-center gap-1 px-3 py-2 rounded-button text-sm font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Copy
                    </button>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-info bg-info/10 px-4 py-3 text-sm text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <strong>Note:</strong> These credentials are shown only once. Please save them securely.
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">College Admin Details</h2>
    </div>
    <div class="p-5">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
            <dt class="font-medium text-slate-500">ID:</dt><dd class="text-slate-800">{{ $collegeAdmin->id }}</dd>
            <dt class="font-medium text-slate-500">Name:</dt><dd class="text-slate-800">{{ $collegeAdmin->name }}</dd>
            <dt class="font-medium text-slate-500">Email:</dt><dd class="text-slate-800">{{ $collegeAdmin->email }}</dd>
            <dt class="font-medium text-slate-500">Role:</dt><dd><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary/20 text-primary">{{ $collegeAdmin->role }}</span></dd>
            <dt class="font-medium text-slate-500">College:</dt>
            <dd>@if($collegeAdmin->college)<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $collegeAdmin->college->name }} ({{ $collegeAdmin->college->code }})</span>@else<span class="text-slate-500">N/A</span>@endif</dd>
            <dt class="font-medium text-slate-500">Created At:</dt><dd class="text-slate-800">{{ $collegeAdmin->created_at->format('M d, Y H:i') }}</dd>
            <dt class="font-medium text-slate-500">Updated At:</dt><dd class="text-slate-800">{{ $collegeAdmin->updated_at->format('M d, Y H:i') }}</dd>
        </dl>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(elementId, btn) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(element.value).then(function() {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Copied!';
        btn.classList.remove('border-border');
        btn.classList.add('bg-success-light', 'text-success', 'border-success/30');
        setTimeout(function() {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-success-light', 'text-success', 'border-success/30');
            btn.classList.add('border-border');
        }, 2000);
    });
}
</script>
@endpush
@endsection

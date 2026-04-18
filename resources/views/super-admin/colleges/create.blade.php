@extends('super-admin.layouts.app')

@section('title', 'Create College with Admin')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        Create College with Admin
    </h1>
    <a href="{{ route('super-admin.colleges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('super-admin.colleges.store') }}" method="POST" class="space-y-4">
            @csrf
            <h3 class="text-lg font-semibold text-slate-800 border-b border-border pb-2 mb-4">College Details</h3>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">College Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-input border @error('name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">College Code <span class="text-red-500">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" required class="w-full rounded-input border @error('code') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="mt-1 text-xs text-slate-500">Unique code for the college (e.g., ABC123)</p>
                @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-1">Contact Email <span class="text-red-500">*</span></label>
                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" required class="w-full rounded-input border @error('contact_email') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('contact_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" required class="w-full rounded-input border @error('status') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <h3 class="text-lg font-semibold text-slate-800 border-b border-border pb-2 mt-6 mb-4">College Admin Credentials</h3>
            <p class="text-sm text-slate-500 -mt-2 mb-2">Create the admin account that will manage this college.</p>
            <div>
                <label for="admin_name" class="block text-sm font-medium text-slate-700 mb-1">Admin Name <span class="text-red-500">*</span></label>
                <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required class="w-full rounded-input border @error('admin_name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('admin_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_email" class="block text-sm font-medium text-slate-700 mb-1">Admin Email <span class="text-red-500">*</span></label>
                <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required class="w-full rounded-input border @error('admin_email') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="mt-1 text-xs text-slate-500">Used for login</p>
                @error('admin_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_password" class="block text-sm font-medium text-slate-700 mb-1">Admin Password <span class="text-red-500">*</span></label>
                <x-password-input id="admin_password" name="admin_password" required class="w-full rounded-input border @error('admin_password') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary" />
                <p class="mt-1 text-xs text-slate-500">Minimum 8 characters. Save this securely after creation.</p>
                @error('admin_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Admin Password <span class="text-red-500">*</span></label>
                <x-password-input id="admin_password_confirmation" name="admin_password_confirmation" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
            </div>
            <div class="flex flex-wrap gap-2 justify-end pt-2">
                <a href="{{ route('super-admin.colleges.index') }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Create College & Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

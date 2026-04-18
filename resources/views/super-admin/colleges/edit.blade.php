@extends('super-admin.layouts.app')

@section('title', 'Edit College with Admin')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit College
    </h1>
    <a href="{{ route('super-admin.colleges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('super-admin.colleges.update', $college) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <h3 class="text-lg font-semibold text-slate-800 border-b border-border pb-2 mb-4">College Details</h3>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">College Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $college->name) }}" required class="w-full rounded-input border @error('name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">College Code <span class="text-red-500">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code', $college->code) }}" required class="w-full rounded-input border @error('code') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="mt-1 text-xs text-slate-500">Unique code for the college (e.g., ABC123)</p>
                @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-1">Contact Email <span class="text-red-500">*</span></label>
                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $college->contact_email) }}" required class="w-full rounded-input border @error('contact_email') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('contact_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" required class="w-full rounded-input border @error('status') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="active" {{ old('status', $college->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $college->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <h3 class="text-lg font-semibold text-slate-800 border-b border-border pb-2 mt-6 mb-4">College Admin Credentials</h3>
            <p class="text-sm text-slate-500 -mt-2 mb-2">
                @if($collegeAdmin)
                    Update the primary college admin account. Leave password fields blank to keep the current password.
                @else
                    No college admin is linked yet. Set credentials below to create the admin account.
                @endif
            </p>
            <div>
                <label for="admin_name" class="block text-sm font-medium text-slate-700 mb-1">Admin Name <span class="text-red-500">*</span></label>
                <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name', $collegeAdmin->name ?? '') }}" required class="w-full rounded-input border @error('admin_name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('admin_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_email" class="block text-sm font-medium text-slate-700 mb-1">Admin Email <span class="text-red-500">*</span></label>
                <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $collegeAdmin->email ?? '') }}" required class="w-full rounded-input border @error('admin_email') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                <p class="mt-1 text-xs text-slate-500">Used for login</p>
                @error('admin_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_password" class="block text-sm font-medium text-slate-700 mb-1">Admin Password @if(!$collegeAdmin)<span class="text-red-500">*</span>@endif</label>
                @if($collegeAdmin)
                    <x-password-input id="admin_password" name="admin_password" autocomplete="new-password" class="w-full rounded-input border @error('admin_password') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary" />
                @else
                    <x-password-input id="admin_password" name="admin_password" required autocomplete="new-password" class="w-full rounded-input border @error('admin_password') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary" />
                @endif
                <p class="mt-1 text-xs text-slate-500">
                    @if($collegeAdmin)
                        Minimum 8 characters. Leave blank to keep the current password.
                    @else
                        Minimum 8 characters. Required when creating the first admin for this college.
                    @endif
                </p>
                @error('admin_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="admin_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Admin Password @if(!$collegeAdmin)<span class="text-red-500">*</span>@endif</label>
                @if($collegeAdmin)
                    <x-password-input id="admin_password_confirmation" name="admin_password_confirmation" autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                @else
                    <x-password-input id="admin_password_confirmation" name="admin_password_confirmation" required autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                @endif
            </div>
            <div class="flex flex-wrap gap-2 justify-end pt-2">
                <a href="{{ route('super-admin.colleges.index') }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Update College &amp; Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

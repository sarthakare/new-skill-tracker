@extends('super-admin.layouts.app')

@section('title', 'View College')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        View College
    </h1>
    <div class="flex gap-2">
        <a href="{{ route('super-admin.colleges.edit', $college) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            Edit
        </a>
        <a href="{{ route('super-admin.colleges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back
        </a>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
            <dt class="font-medium text-slate-500">ID:</dt><dd class="text-slate-800">{{ $college->id }}</dd>
            <dt class="font-medium text-slate-500">Name:</dt><dd class="text-slate-800">{{ $college->name }}</dd>
            <dt class="font-medium text-slate-500">Code:</dt><dd><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">{{ $college->code }}</span></dd>
            <dt class="font-medium text-slate-500">Contact Email:</dt><dd class="text-slate-800">{{ $college->contact_email }}</dd>
            <dt class="font-medium text-slate-500">College Admin:</dt>
            <dd class="text-slate-800">
                @php $admin = $college->collegeAdmins()->first(); @endphp
                @if($admin)
                    <a href="{{ route('super-admin.college-admins.show', $admin) }}" class="text-primary font-medium hover:underline">{{ $admin->name }}</a>
                    <span class="text-slate-500">({{ $admin->email }})</span>
                @else
                    <span class="text-slate-500">No admin assigned</span>
                @endif
            </dd>
            <dt class="font-medium text-slate-500">Status:</dt>
            <dd>@if($college->status === 'active')<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>@else<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">Inactive</span>@endif</dd>
            <dt class="font-medium text-slate-500">Created At:</dt><dd class="text-slate-800">{{ $college->created_at->format('M d, Y H:i') }}</dd>
            <dt class="font-medium text-slate-500">Updated At:</dt><dd class="text-slate-800">{{ $college->updated_at->format('M d, Y H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection

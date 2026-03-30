@extends('college.layouts.app')

@section('title', 'Create Vendor')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        Create Vendor
    </h1>
    <a href="{{ route('college.vendors.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Vendor details</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('college.vendors.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Vendor Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-input border @error('name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Vendor Type <span class="text-red-500">*</span></label>
                <select id="type" name="type" required class="w-full rounded-input border @error('type') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Select Type</option>
                    <option value="Training" {{ old('type') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Certification" {{ old('type') === 'Certification' ? 'selected' : '' }}>Certification</option>
                    <option value="Logistics" {{ old('type') === 'Logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="Other" {{ old('type') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-1">Contact Email</label>
                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" class="w-full rounded-input border @error('contact_email') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('contact_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-1">Contact Phone</label>
                <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full rounded-input border @error('contact_phone') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('contact_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                <textarea id="address" name="address" rows="3" class="w-full rounded-input border @error('address') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-2 justify-end pt-2">
                <a href="{{ route('college.vendors.index') }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Create Vendor</button>
            </div>
        </form>
    </div>
</div>
@endsection

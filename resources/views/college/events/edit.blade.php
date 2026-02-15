@extends('college.layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit Event
    </h1>
    <a href="{{ route('college.events.show', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Event details</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('college.events.update', $event) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Event Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $event->name) }}" required class="w-full rounded-input border @error('name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-input border @error('description') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description', $event->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required class="w-full rounded-input border @error('start_date') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" required class="w-full rounded-input border @error('end_date') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="target_audience" class="block text-sm font-medium text-slate-700 mb-1">Target Audience</label>
                <input type="text" id="target_audience" name="target_audience" value="{{ old('target_audience', $event->target_audience) }}" class="w-full rounded-input border @error('target_audience') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('target_audience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" required class="w-full rounded-input border @error('status') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="Draft" {{ old('status', $event->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Active" {{ old('status', $event->status) === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Completed" {{ old('status', $event->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Archived" {{ old('status', $event->status) === 'Archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap gap-2 justify-end pt-2">
                <a href="{{ route('college.events.show', $event) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection

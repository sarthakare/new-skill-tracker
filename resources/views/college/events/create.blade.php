@extends('college.layouts.app')

@section('title', 'Create Year/Semester/Event')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        </span>
        Create Year/Semester/Event
    </h1>
    <a href="{{ route('college.events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Year/Semester/Event details</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('college.events.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Year/Semester/Event <span class="text-red-500">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g., Placement Drive 2026"
                       required
                       class="w-full rounded-input border @error('name') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea id="description"
                          name="description"
                          rows="3"
                          placeholder="e.g., Pre-placement training & hiring activities"
                          class="w-full rounded-input border @error('description') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date"
                           id="start_date"
                           name="start_date"
                           value="{{ old('start_date') }}"
                           required
                           class="w-full rounded-input border @error('start_date') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="date"
                           id="end_date"
                           name="end_date"
                           value="{{ old('end_date') }}"
                           min="{{ old('start_date') }}"
                           required
                           class="w-full rounded-input border @error('end_date') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="target_audience" class="block text-sm font-medium text-slate-700 mb-1">Target Audience</label>
                <input type="text"
                       id="target_audience"
                       name="target_audience"
                       value="{{ old('target_audience') }}"
                       placeholder="e.g., Final Year Students"
                       class="w-full rounded-input border @error('target_audience') border-red-500 @else border-slate-300 @enderror focus:ring-2 focus:ring-primary focus:border-primary">
                @error('target_audience')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-lg border border-info bg-info/10 px-4 py-3 text-sm text-slate-700 flex items-start gap-2">
                <svg class="w-5 h-5 shrink-0 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Year/Semester/Event will be created with status <strong>DRAFT</strong>. After saving, add subjects/programs under this year/semester/event and assign Vendor, Professors/Trainers, or Internal Manager to each subject/program.</span>
            </div>

            <div class="flex flex-wrap gap-2 justify-end pt-2">
                <a href="{{ route('college.events.index') }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        if (!startDateInput || !endDateInput) {
            return;
        }

        function syncEndDateMin() {
            var startDate = startDateInput.value;
            endDateInput.min = startDate || '';

            if (startDate && endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        }

        startDateInput.addEventListener('change', syncEndDateMin);
        syncEndDateMin();
    })();
</script>
@endpush

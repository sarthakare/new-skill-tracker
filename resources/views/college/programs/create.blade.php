@extends('college.layouts.app')

@section('title', 'Add Program')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </span>
        Add Program to {{ $event->name }}
    </h1>
    <a href="{{ route('college.events.programs.index', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back
    </a>
</div>

@php
    $missingManagers = collect();
    if ($vendors->isEmpty()) $missingManagers->push(['Vendors', route('college.vendors.create')]);
    if ($independents->isEmpty()) $missingManagers->push(['Independent Trainers', route('college.independent-trainers.create')]);
    if ($internals->isEmpty()) $missingManagers->push(['Internal Managers', route('college.internal-managers.create')]);
@endphp
@if($missingManagers->isNotEmpty())
<div class="mb-6 rounded-lg border border-info bg-info/10 px-4 py-3 text-sm text-slate-700 flex items-center gap-2">
    <svg class="w-5 h-5 shrink-0 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <span>Create managers first: @foreach($missingManagers as $i => $m)<a href="{{ $m[1] }}" class="font-medium text-primary hover:underline">{{ $m[0] }}</a>@if(!$loop->last), @endif @endforeach</span>
</div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('college.events.programs.store', $event) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Program Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., IT Training" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">-- Select Type --</option>
                    <option value="Training" {{ old('type') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Hackathon" {{ old('type') === 'Hackathon' ? 'selected' : '' }}>Hackathon</option>
                    <option value="Seminar" {{ old('type') === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g., CSE / IT or All" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Days) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" placeholder="e.g., 10" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mode <span class="text-red-500">*</span></label>
                <select name="mode" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="On-Campus" {{ old('mode') === 'On-Campus' ? 'selected' : '' }}>On-Campus</option>
                    <option value="Online" {{ old('mode') === 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Hybrid" {{ old('mode') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>

            <div class="border-t border-border pt-4 mt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-1">Who runs the program?</h3>
                <p class="text-sm text-slate-500 mb-3">Assign a Vendor or Independent Trainer who will deliver/run the program.</p>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Executor type <span class="text-red-500">*</span></label>
                    <select name="manager_type" id="manager_type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select who runs the program --</option>
                        <option value="Vendor" {{ old('manager_type') === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="Independent" {{ old('manager_type') === 'Independent' ? 'selected' : '' }}>Independent Trainer</option>
                    </select>
                </div>
                <div class="executor-select hidden mt-3" data-type="Vendor">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vendor <span class="text-red-500">*</span></label>
                    <select name="vendor_manager_id" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_manager_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="executor-select hidden mt-3" data-type="Independent">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Independent Trainer <span class="text-red-500">*</span></label>
                    <select name="independent_manager_id" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Trainer --</option>
                        @foreach($independents as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('independent_manager_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-border pt-4 mt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-1">Assign Program Manager (Internal)</h3>
                <p class="text-sm text-slate-500 mb-3">Internal manager will manage students, mark attendance, assign points, and generate reports.</p>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Internal Manager <span class="text-red-500">*</span></label>
                    <select name="internal_manager_id" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Internal Manager --</option>
                        @foreach($internals as $manager)
                            <option value="{{ $manager->id }}" {{ old('internal_manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->department }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save</button>
                <a href="{{ route('college.events.programs.index', $event) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
            </div>
            </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const managerType = document.getElementById('manager_type');
    const executorSelects = document.querySelectorAll('.executor-select');

    function toggleExecutorSelects() {
        executorSelects.forEach(select => {
            if (select.dataset.type === managerType.value) {
                select.classList.remove('hidden');
                select.querySelector('select').required = true;
            } else {
                select.classList.add('hidden');
                select.querySelector('select').required = false;
                select.querySelector('select').value = '';
            }
        });
    }

    managerType.addEventListener('change', toggleExecutorSelects);
    toggleExecutorSelects();
</script>
@endpush

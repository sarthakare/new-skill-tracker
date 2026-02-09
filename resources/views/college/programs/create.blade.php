@extends('college.layouts.app')

@section('title', 'Add Program')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-collection"></i> Add Program to {{ $event->name }}</h1>
    <a href="{{ route('college.events.programs.index', $event) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@php
    $missingManagers = collect();
    if ($vendors->isEmpty()) $missingManagers->push(['Vendors', route('college.vendors.create')]);
    if ($independents->isEmpty()) $missingManagers->push(['Independent Trainers', route('college.independent-trainers.create')]);
    if ($internals->isEmpty()) $missingManagers->push(['Internal Managers', route('college.internal-managers.create')]);
@endphp
@if($missingManagers->isNotEmpty())
<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle"></i> Create managers first: 
    @foreach($missingManagers as $i => $m)
        <a href="{{ $m[1] }}">{{ $m[0] }}</a>@if(!$loop->last), @endif
    @endforeach
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('college.events.programs.store', $event) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Program Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., IT Training" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option value="Training" {{ old('type') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Hackathon" {{ old('type') === 'Hackathon' ? 'selected' : '' }}>Hackathon</option>
                    <option value="Seminar" {{ old('type') === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="e.g., CSE / IT or All" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days') }}" min="1" placeholder="e.g., 10" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mode <span class="text-danger">*</span></label>
                <select name="mode" class="form-select" required>
                    <option value="On-Campus" {{ old('mode') === 'On-Campus' ? 'selected' : '' }}>On-Campus</option>
                    <option value="Online" {{ old('mode') === 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Hybrid" {{ old('mode') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>

            <h6 class="border-bottom pb-2 mt-4 mb-3">Who runs the program?</h6>
            <p class="text-muted small">Assign a Vendor or Independent Trainer who will deliver/run the program.</p>
            <div class="mb-3">
                <label class="form-label">Executor type <span class="text-danger">*</span></label>
                <select name="manager_type" id="manager_type" class="form-select" required>
                    <option value="">-- Select who runs the program --</option>
                    <option value="Vendor" {{ old('manager_type') === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                    <option value="Independent" {{ old('manager_type') === 'Independent' ? 'selected' : '' }}>Independent Trainer</option>
                </select>
            </div>
            <div class="mb-3 executor-select d-none" data-type="Vendor">
                <label class="form-label">Vendor <span class="text-danger">*</span></label>
                <select name="vendor_manager_id" class="form-select">
                    <option value="">-- Select Vendor --</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_manager_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 executor-select d-none" data-type="Independent">
                <label class="form-label">Independent Trainer <span class="text-danger">*</span></label>
                <select name="independent_manager_id" class="form-select">
                    <option value="">-- Select Trainer --</option>
                    @foreach($independents as $trainer)
                        <option value="{{ $trainer->id }}" {{ old('independent_manager_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                    @endforeach
                </select>
            </div>

            <h6 class="border-bottom pb-2 mt-4 mb-3">Assign Program Manager (Internal)</h6>
            <p class="text-muted small">Internal manager will manage students, mark attendance, assign points, and generate reports.</p>
            <div class="mb-3">
                <label class="form-label">Internal Manager <span class="text-danger">*</span></label>
                <select name="internal_manager_id" class="form-select" required>
                    <option value="">-- Select Internal Manager --</option>
                    @foreach($internals as $manager)
                        <option value="{{ $manager->id }}" {{ old('internal_manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->department }})</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('college.events.programs.index', $event) }}" class="btn btn-outline-secondary">Cancel</a>
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
                select.classList.remove('d-none');
                select.querySelector('select').required = true;
            } else {
                select.classList.add('d-none');
                select.querySelector('select').required = false;
                select.querySelector('select').value = '';
            }
        });
    }

    managerType.addEventListener('change', toggleExecutorSelects);
    toggleExecutorSelects();
</script>
@endpush

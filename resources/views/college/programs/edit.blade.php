@extends('college.layouts.app')

@section('title', 'Edit Program')

@section('content')
    <h2 class="mb-4">Edit Program - {{ $program->name }}</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('college.events.programs.update', [$event, $program]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Program Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        <option value="Training" {{ old('type', $program->type) === 'Training' ? 'selected' : '' }}>Training</option>
                        <option value="Hackathon" {{ old('type', $program->type) === 'Hackathon' ? 'selected' : '' }}>Hackathon</option>
                        <option value="Seminar" {{ old('type', $program->type) === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $program->department) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration (Days)</label>
                    <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $program->duration_days) }}" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mode</label>
                    <select name="mode" class="form-select" required>
                        @foreach(['On-Campus','Online','Hybrid'] as $mode)
                            <option value="{{ $mode }}" {{ old('mode', $program->mode) === $mode ? 'selected' : '' }}>{{ $mode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['Draft','Manager_Assigned','Registration_Open','In_Progress','Completed','Approved'] as $status)
                            <option value="{{ $status }}" {{ old('status', $program->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <h6 class="border-bottom pb-2 mt-4 mb-3">Who runs the program?</h6>
                <div class="mb-3">
                    <label class="form-label">Executor type <span class="text-danger">*</span></label>
                    <select name="manager_type" id="manager_type" class="form-select" required>
                        <option value="">-- Select who runs the program --</option>
                        <option value="Vendor" {{ old('manager_type', in_array($program->manager_type, ['Vendor','Independent']) ? $program->manager_type : 'Vendor') === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="Independent" {{ old('manager_type', $program->manager_type) === 'Independent' ? 'selected' : '' }}>Independent Trainer</option>
                    </select>
                </div>
                <div class="mb-3 executor-select d-none" data-type="Vendor">
                    <label class="form-label">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_manager_id" class="form-select">
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_manager_id', $program->manager_type === 'Vendor' ? $program->manager_id : null) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 executor-select d-none" data-type="Independent">
                    <label class="form-label">Independent Trainer <span class="text-danger">*</span></label>
                    <select name="independent_manager_id" class="form-select">
                        <option value="">-- Select Trainer --</option>
                        @foreach($independents as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('independent_manager_id', $program->manager_type === 'Independent' ? $program->manager_id : null) == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <h6 class="border-bottom pb-2 mt-4 mb-3">Assign Program Manager (Internal)</h6>
                <p class="text-muted small">Internal manager manages students, attendance, points, and reports.</p>
                <div class="mb-3">
                    <label class="form-label">Internal Manager <span class="text-danger">*</span></label>
                    <select name="internal_manager_id" class="form-select" required>
                        <option value="">-- Select Internal Manager --</option>
                        @foreach($internals as $manager)
                            <option value="{{ $manager->id }}" {{ old('internal_manager_id', $program->internal_manager_id ?? ($program->manager_type === 'Internal' ? $program->manager_id : null)) == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->department }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
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

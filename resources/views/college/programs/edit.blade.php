@extends('college.layouts.app')

@section('title', 'Edit Program')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit Program - {{ $program->name }}
    </h1>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('college.events.programs.update', [$event, $program]) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Program Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $program->name) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">-- Select Type --</option>
                    <option value="Training" {{ old('type', $program->type) === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Hackathon" {{ old('type', $program->type) === 'Hackathon' ? 'selected' : '' }}>Hackathon</option>
                    <option value="Seminar" {{ old('type', $program->type) === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                <input type="text" name="department" value="{{ old('department', $program->department) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Days) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_days" value="{{ old('duration_days', $program->duration_days) }}" min="1" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mode <span class="text-red-500">*</span></label>
                <select name="mode" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    @foreach(['On-Campus','Online','Hybrid'] as $mode)
                        <option value="{{ $mode }}" {{ old('mode', $program->mode) === $mode ? 'selected' : '' }}>{{ $mode }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    @foreach(['Draft','Manager_Assigned','Registration_Open','In_Progress','Completed','Approved'] as $status)
                        <option value="{{ $status }}" {{ old('status', $program->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="border-t border-border pt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Who runs the program?</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Executor type <span class="text-red-500">*</span></label>
                    <select name="manager_type" id="manager_type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select who runs the program --</option>
                        <option value="Vendor" {{ old('manager_type', in_array($program->manager_type, ['Vendor','Independent']) ? $program->manager_type : 'Vendor') === 'Vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="Independent" {{ old('manager_type', $program->manager_type) === 'Independent' ? 'selected' : '' }}>Independent Trainer</option>
                    </select>
                </div>
                <div class="executor-select hidden mt-3" data-type="Vendor">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vendor <span class="text-red-500">*</span></label>
                    <select name="vendor_manager_id" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_manager_id', $program->manager_type === 'Vendor' ? $program->manager_id : null) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="executor-select hidden mt-3" data-type="Independent">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Independent Trainer <span class="text-red-500">*</span></label>
                    <select name="independent_manager_id" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Trainer --</option>
                        @foreach($independents as $trainer)
                            <option value="{{ $trainer->id }}" {{ old('independent_manager_id', $program->manager_type === 'Independent' ? $program->manager_id : null) == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="border-t border-border pt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Assign Program Manager (Internal)</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Internal Manager <span class="text-red-500">*</span></label>
                    <select name="internal_manager_id" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select Internal Manager --</option>
                        @foreach($internals as $manager)
                            <option value="{{ $manager->id }}" {{ old('internal_manager_id', $program->internal_manager_id ?? ($program->manager_type === 'Internal' ? $program->manager_id : null)) == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->department }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Update</button>
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

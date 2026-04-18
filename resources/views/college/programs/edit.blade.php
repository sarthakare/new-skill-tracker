@extends('college.layouts.app')

@section('title', 'Edit Semester/program')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit Semester/program - {{ $program->name }}
    </h1>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('college.events.programs.update', [$event, $program]) }}" method="POST" class="space-y-4" id="program-edit-form">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Semester/program name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $program->name) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">-- Select Type --</option>
                    <option value="Training" {{ old('type', $program->type) === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Hackathon" {{ old('type', $program->type) === 'Hackathon' ? 'selected' : '' }}>Hackathon</option>
                    <option value="Seminar" {{ old('type', $program->type) === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="Other" {{ old('type', $program->type) === 'Other' ? 'selected' : '' }}>Other</option>
                    <option value="Subject" {{ old('type', $program->type) === 'Subject' ? 'selected' : '' }}>Subject</option>
                </select>
            </div>
            <div class="border border-border rounded-lg p-4 bg-slate-50/50">
                <h3 class="text-sm font-semibold text-slate-800 mb-1">Departments <span class="text-red-500">*</span></h3>
                <p class="text-sm text-slate-500 mb-3">Select one or more departments this semester/program applies to.</p>
                @if($departments->isEmpty())
                    <p class="text-sm text-amber-800">No departments defined. <a href="{{ route('college.departments.create') }}" class="font-medium text-primary hover:underline">Add departments</a> first.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto pr-1">
                        @foreach($departments as $d)
                            <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm cursor-pointer hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="checkbox" name="department_ids[]" value="{{ $d->id }}" class="rounded border-slate-300 text-primary focus:ring-primary shrink-0"
                                    @checked(in_array($d->id, array_map('intval', (array) old('department_ids', $program->departments->pluck('id')->all())), true))>
                                <span>{{ $d->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('department_ids')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('department_ids.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                @endif
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
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Who runs the semester/program?</h3>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Executor type <span class="text-red-500">*</span></label>
                    <select name="manager_type" id="manager_type" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">-- Select who runs the semester/program --</option>
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
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Assign Semester/Program manager (internal)</h3>
                <p class="text-sm text-slate-500 mb-3">Optional. An internal manager can manage students, mark attendance, assign points, and generate reports.</p>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Internal Manager</label>
                    <select name="internal_manager_id" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">— None —</option>
                        @foreach($internals as $manager)
                            <option value="{{ $manager->id }}" {{ old('internal_manager_id', $program->internal_manager_id ?? ($program->manager_type === 'Internal' ? $program->manager_id : null)) == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->department?->name ?? '—' }})</option>
                        @endforeach
                    </select>
                    @error('internal_manager_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" id="program-edit-submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Update</button>
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

    const programEditForm = document.getElementById('program-edit-form');
    const programEditSubmit = document.getElementById('program-edit-submit');
    if (programEditForm && programEditSubmit) {
        programEditForm.addEventListener('submit', () => {
            programEditSubmit.disabled = true;
            programEditSubmit.classList.add('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
        });
    }
</script>
@endpush

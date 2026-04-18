@extends('manager.layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </span>
        Students - {{ $program->name }}
    </h1>
</div>

@php($createStudentFormOpen = $errors->any() && old('mode') === 'manual')

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add registered student</h2>
        <p class="text-sm text-slate-500 mt-1">Choose a student who already has an account at your college.</p>
    </div>
    <div class="p-5">
        @if($registeredStudents->isEmpty())
            <p class="text-sm text-slate-600">No registered students left to add (everyone may already be in this program, or there are no student accounts yet). Use <strong>Create student</strong> below, or ask your college admin to register students first.</p>
        @else
            <form action="{{ route('manager.program.students.store', $program) }}" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-4">
                @csrf
                <input type="hidden" name="mode" value="user">
                <div class="flex-1 min-w-0">
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1">Student</label>
                    <select id="user_id" name="user_id" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white">
                        <option value="">Select student…</option>
                        @foreach($registeredStudents as $u)
                            <option value="{{ $u->id }}">@if(filled($u->roll_number)){{ $u->roll_number }} — @endif{{ $u->name }} — {{ $u->email }}@if($u->department) ({{ $u->department->name }})@endif</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover shrink-0">Add to program</button>
            </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Create student &amp; add to program</h2>
            <p class="text-sm text-slate-500 mt-1">Same fields as college admin: roll number, name, email, department, optional mobile, and a password you assign. The form stays hidden until you open it.</p>
        </div>
        @if(!$departments->isEmpty())
            <button type="button"
                    id="toggle-create-student-form"
                    aria-expanded="{{ $createStudentFormOpen ? 'true' : 'false' }}"
                    aria-controls="create-student-panel"
                    class="inline-flex shrink-0 items-center justify-center gap-2 px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span id="toggle-create-student-label">{{ $createStudentFormOpen ? 'Hide form' : 'Create student' }}</span>
            </button>
        @endif
    </div>
    <div id="create-student-panel"
         data-initial-open="{{ $createStudentFormOpen ? '1' : '0' }}"
         class="grid transition-[grid-template-rows] duration-300 ease-out {{ $createStudentFormOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
        <div class="min-h-0 overflow-hidden">
            <div class="px-5 pb-5 pt-0 border-t border-border">
                @if($departments->isEmpty())
                    <p class="text-sm text-amber-800 pt-5">No departments are set up for this college yet. Ask your college admin to add departments first.</p>
                @else
                    <form action="{{ route('manager.program.students.store', $program) }}" method="POST" class="space-y-4 max-w-xl pt-5">
                        @csrf
                        <input type="hidden" name="mode" value="manual">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="manager_roll_number" class="block text-sm font-medium text-slate-700 mb-1">Roll number <span class="text-red-500">*</span></label>
                                <input type="text" id="manager_roll_number" name="roll_number" value="{{ old('roll_number') }}" required autocomplete="off" placeholder="As on university ID" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('roll_number') border-red-500 @enderror">
                                @error('roll_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="manager_student_name" class="block text-sm font-medium text-slate-700 mb-1">Full name <span class="text-red-500">*</span></label>
                                <input type="text" id="manager_student_name" name="name" value="{{ old('name') }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="manager_student_email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="manager_student_email" name="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="manager_department_id" class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                                <select id="manager_department_id" name="department_id" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                                    <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>Select department</option>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}" {{ (string) old('department_id') === (string) $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="manager_student_mobile" class="block text-sm font-medium text-slate-700 mb-1">Mobile <span class="text-slate-400 font-normal">(optional)</span></label>
                                <input type="tel" id="manager_student_mobile" name="mobile" value="{{ old('mobile') }}" inputmode="tel" placeholder="Optional" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                                @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="manager_student_password" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <x-password-input id="manager_student_password" name="password" required autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror" />
                                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <p class="mt-1 text-xs text-slate-500">At least 8 characters. Give this to the student for sign-in.</p>
                            </div>
                            <div>
                                <label for="manager_student_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm password <span class="text-red-500">*</span></label>
                                <x-password-input id="manager_student_password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Create &amp; add student</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Student list</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px]">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Roll no.</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Email</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Phone</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Source</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm text-slate-900 font-medium">{{ $student->displayRollNumber() ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $student->displayName() }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->email ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->mobile ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->departmentLabel() ?: '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">
                            @if($student->isLinkedToUser())
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-info-light text-info">Registered</span>
                            @else
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700">Manual</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->status }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('manager.program.students.edit', [$program, $student]) }}" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Edit</a>
                                <form action="{{ route('manager.program.students.destroy', [$program, $student]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-red-600 border border-red-200 hover:bg-red-50">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-5 py-12 text-center text-slate-500">No students added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('toggle-create-student-form');
    var panel = document.getElementById('create-student-panel');
    var label = document.getElementById('toggle-create-student-label');
    if (!btn || !panel || !label) return;

    function setOpen(open) {
        if (open) {
            panel.classList.remove('grid-rows-[0fr]');
            panel.classList.add('grid-rows-[1fr]');
        } else {
            panel.classList.remove('grid-rows-[1fr]');
            panel.classList.add('grid-rows-[0fr]');
        }
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        label.textContent = open ? 'Hide form' : 'Create student';
    }

    var initial = panel.getAttribute('data-initial-open') === '1';
    setOpen(initial);

    btn.addEventListener('click', function () {
        var open = !panel.classList.contains('grid-rows-[1fr]');
        setOpen(open);
    });
})();
</script>
@endpush
@endsection

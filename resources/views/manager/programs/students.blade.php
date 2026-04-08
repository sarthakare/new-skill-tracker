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

@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add registered student</h2>
        <p class="text-sm text-slate-500 mt-1">Choose a student who already has an account at your college.</p>
    </div>
    <div class="p-5">
        @if($registeredStudents->isEmpty())
            <p class="text-sm text-slate-600">No registered students left to add (everyone may already be in this program, or there are no student accounts yet). Use <strong>Add manually</strong> below, or ask your college admin to register students first.</p>
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
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add manually</h2>
        <p class="text-sm text-slate-500 mt-1">For participants without a login — name, email, phone, and department are stored for this program only.</p>
    </div>
    <div class="p-5">
        @if($departments->isEmpty())
            <p class="text-sm text-amber-800">No departments are set up for this college yet. Ask your college admin to add departments first.</p>
        @else
            <form action="{{ route('manager.program.students.store', $program) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="mode" value="manual">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Roll no. (optional)</label>
                        <input type="text" name="student_identifier" value="{{ old('student_identifier') }}" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" placeholder="University roll number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="student_name" value="{{ old('student_name') }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('student_name') border-red-500 @enderror">
                        @error('student_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" required inputmode="tel" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                        @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department_id" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                            <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>Select department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ (string) old('department_id') === (string) $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Add student</button>
            </form>
        @endif
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
@endsection

@extends('manager.layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit student - {{ $program->name }}
    </h1>
    @if($student->isLinkedToUser())
        <p class="mt-2 text-sm text-slate-600">This row is linked to a registered student account. Changes here apply to this program only (they do not change the college login profile).</p>
    @endif
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Student details</h2>
    </div>
    <div class="p-5">
        @if($departments->isEmpty())
            <p class="text-sm text-amber-800">Departments are not configured for this college. Contact your college admin before editing.</p>
        @else
        <form action="{{ route('manager.program.students.update', [$program, $student]) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Roll no. (optional)</label>
                    <p class="text-xs text-slate-500 mb-1">For linked accounts, attendance uses the roll number from the student’s college profile when set.</p>
                    <input type="text" name="student_identifier" value="{{ old('student_identifier', $student->student_identifier) }}" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Manual entry only; optional for registered students">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="student_name" value="{{ old('student_name', $student->student_name) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('student_name') border-red-500 @enderror">
                    @error('student_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile" value="{{ old('mobile', $student->mobile) }}" required inputmode="tel" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                    @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                    <select name="department_id" required class="w-full max-w-xl rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                        <option value="" disabled {{ old('department_id', $student->department_id) ? '' : 'selected' }}>Select department</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" {{ (string) old('department_id', $student->department_id) === (string) $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Remarks for this student</label>
                    <p class="text-xs text-slate-500 mb-2">Visible to the student on their dashboard for this program.</p>
                    <textarea name="manager_remarks" rows="4" placeholder="Optional feedback, notes, or recognition…" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('manager_remarks') border-red-500 @enderror">{{ old('manager_remarks', $student->manager_remarks) }}</textarea>
                    @error('manager_remarks')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save changes</button>
                <a href="{{ route('manager.program.students.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

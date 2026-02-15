@extends('manager.layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit Student - {{ $program->name }}
    </h1>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Student Details</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.students.update', [$program, $student]) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Student Name</label>
                    <input type="text" name="student_name" value="{{ old('student_name', $student->student_name) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Student ID (Optional)</label>
                    <input type="text" name="student_identifier" value="{{ old('student_identifier', $student->student_identifier) }}" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department', $student->department) }}" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save Changes</button>
                <a href="{{ route('manager.program.students.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


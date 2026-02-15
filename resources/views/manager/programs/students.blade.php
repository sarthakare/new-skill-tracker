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

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add Student</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.students.store', $program) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Student Name</label>
                    <input type="text" name="student_name" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Student ID (Optional)</label>
                    <input type="text" name="student_identifier" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                    <input type="text" name="department" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Add Student</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Student List</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Student ID</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $student->student_name }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->student_identifier ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->department }}</td>
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
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">No students added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

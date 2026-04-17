@extends('college.layouts.app')

@section('title', 'Students')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
        </span>
        Students
    </h1>
    <a href="{{ route('college.students.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add student
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">All students</h2>
        <span class="text-sm text-slate-600">{{ $students->total() }} {{ Str::plural('student', $students->total()) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[880px]">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Roll no.</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Mobile</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Email</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Registered</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm text-slate-900 font-medium">{{ $student->roll_number ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $student->name }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->department?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->mobile ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->email }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $student->created_at?->timezone(config('app.timezone'))->format('M j, Y') ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('college.students.edit', $student) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-hover hover:underline">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-500">No student accounts yet. <a href="{{ route('college.students.create') }}" class="font-medium text-primary hover:underline">Add a student</a> or they can register from the student sign-up page.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="px-5 py-4 border-t border-border flex justify-center">{{ $students->links() }}</div>
    @endif
</div>
@endsection

@extends('college.layouts.app')

@section('title', 'Departments')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        Departments
    </h1>
    <a href="{{ route('college.departments.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        Add department
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">Departments for your college</h2>
        <span class="text-sm text-slate-600">{{ $departments->total() }} {{ Str::plural('department', $departments->total()) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[480px]">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Students</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $d)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $d->name }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $d->students_count }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('college.departments.edit', $d) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                <form action="{{ route('college.departments.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Delete this department? Students linked to it will have their department cleared.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-12 text-center text-slate-500">No departments yet. Add departments so students can choose one when they register.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($departments->hasPages())
        <div class="px-5 py-4 border-t border-border flex justify-center">{{ $departments->links() }}</div>
    @endif
</div>
@endsection

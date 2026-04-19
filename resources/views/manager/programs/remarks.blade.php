@extends('manager.layouts.app')

@section('title', 'Remarks')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m-4 4h10M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        Remarks - {{ $program->name }}
    </h1>
    <p class="mt-2 text-sm text-slate-600">
        Remarks are visible to students on their dashboard for this subject/program.
    </p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Student remarks</h2>
    </div>

    @if($students->isEmpty())
        <div class="p-5">
            <p class="text-sm text-slate-600">No students have been added to this subject/program yet.</p>
        </div>
    @else
        <div class="p-5">
            <form action="{{ route('manager.program.remarks.update', $program) }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-100 border-b border-border">
                                <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Student</th>
                                <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                                <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                                <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3 w-[380px]">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors align-top">
                                    <td class="px-5 py-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $student->displayRollNumber() ?? '—' }}</div>
                                        <div class="text-sm font-medium text-slate-800">{{ $student->displayName() }}</div>
                                        <div class="text-sm text-slate-600">{{ $student->email ?? '—' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-slate-600">{{ $student->departmentLabel() ?: '—' }}</td>
                                    <td class="px-5 py-3 text-sm text-slate-600">{{ $student->status }}</td>
                                    <td class="px-5 py-3">
                                        <textarea
                                            name="remarks[{{ $student->id }}]"
                                            rows="4"
                                            placeholder="Optional feedback, notes, or recognition..."
                                            class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('remarks.' . $student->id) border-red-500 @enderror"
                                        >{{ old('remarks.' . $student->id, $student->manager_remarks) }}</textarea>
                                        @error('remarks.' . $student->id)
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap gap-2 justify-end pt-4">
                    <a href="{{ route('manager.program.students.index', $program) }}"
                       class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
                        Back to Students
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                        Save Remarks
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection


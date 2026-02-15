@extends('manager.layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
        </span>
        Attendance - {{ $session->title }}
    </h1>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('manager.program.attendance.store', [$program, $session]) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Attendance Method</label>
                <select name="method" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="Manual">Manual</option>
                    <option value="QR">QR</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-100 border-b border-border">
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3 w-12">Present</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Student Name</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Student ID</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php $record = $attendance[$student->id] ?? null; @endphp
                            <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5">
                                <td class="px-5 py-3">
                                    <input type="checkbox" name="attendance[]" value="{{ $student->id }}" {{ $record && $record->status === 'present' ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary">
                                </td>
                                <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $student->student_name }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $student->student_identifier ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $student->department }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">No students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save Attendance</button>
                <a href="{{ route('manager.program.sessions.index', $program) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

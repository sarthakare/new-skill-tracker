@extends('manager.layouts.app')

@section('title', 'Program Completion')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </span>
        Completion Request - {{ $program->name }}
    </h1>
</div>

@if($existing)
    <div class="mb-6 rounded-lg border border-info bg-info/10 px-4 py-3 text-sm text-slate-700">
        Latest request status: <strong>{{ $existing->status }}</strong>
    </div>
@endif

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="p-5">
        <form action="{{ route('manager.program.completion.store', $program) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                <textarea name="notes" rows="4" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('notes') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Attachments (Attendance, Reports, Certificates)</label>
                <input type="file" name="attachments[]" multiple class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Submit Completion Request</button>
            </div>
        </form>
    </div>
</div>
@endsection

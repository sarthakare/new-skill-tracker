@extends('college.layouts.app')

@section('title', 'Subject/Program completions')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
        </span>
        Subject/Program completions
    </h1>
    <a href="{{ route('college.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Dashboard
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Completion Requests</h2>
    </div>
    <div class="p-5">
        @if($requests->isEmpty())
            <p class="text-slate-500">No completion requests yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-100 border-b border-border">
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Year/Semester/Event</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Subject/program</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Requested By</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Requested At</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Notes</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Documents</th>
                            <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                                <td class="px-5 py-3 text-sm text-slate-700">{{ $request->program->event->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $request->program->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $request->requestedBy?->managerLabel() ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $request->created_at->format('Y-m-d') }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $request->status === 'pending' ? 'bg-warning-light text-warning' : 'bg-success-light text-success' }}">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($request->notes ?? '—', 80) }}</td>
                                <td class="px-5 py-3 text-sm">
                                    @if(!empty($request->attachments))
                                        <ul class="space-y-1">
                                            @foreach($request->attachments as $path)
                                                <li><a href="{{ Storage::url($path) }}" target="_blank" class="text-primary hover:underline">{{ basename($path) }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    @if($request->program && $request->program->event)
                                        <a href="{{ route('college.events.programs.show', [$request->program->event, $request->program]) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-button text-sm font-medium text-white bg-primary hover:bg-primary-hover transition-colors">Review</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection


@extends('college.layouts.app')

@section('title', 'Subject/program details')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">{{ $program->name }}</h1>
        <p class="text-slate-500 text-sm mt-0.5">Year/Semester/Event: {{ $event->name }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('college.events.programs.edit', [$event, $program]) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-primary border border-primary/30 hover:bg-primary/10">Edit Subject/program</a>
        <a href="{{ route('college.events.programs.index', $event) }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back</a>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-card border border-border shadow-card p-4 col-span-2 lg:col-span-4">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Departments</p>
        <div class="mt-2 flex flex-wrap gap-2">
            @forelse($program->departments as $d)
                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200/80">{{ $d->name }}</span>
            @empty
                <span class="text-sm font-medium text-slate-800">{{ $program->departmentsLabel() ?: '—' }}</span>
            @endforelse
        </div>
    </div>
    <div class="bg-white rounded-card border border-border shadow-card p-4">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Type</p>
        <p class="text-lg font-semibold text-slate-800 mt-0.5">@if($program->type)<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $program->type }}</span>@else—@endif</p>
    </div>
    <div class="bg-white rounded-card border border-border shadow-card p-4">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Run by</p>
        <p class="text-lg font-semibold text-slate-800 mt-0.5">{{ $program->executorLabel() }}</p>
    </div>
    <div class="bg-white rounded-card border border-border shadow-card p-4">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Subject/Program manager</p>
        <p class="text-lg font-semibold text-slate-800 mt-0.5">{{ $program->oversightManager?->name ?? '—' }}</p>
        @if($program->oversightManager)<p class="text-xs text-slate-500 mt-0.5">{{ $program->oversightManager->department?->name ?? '—' }}</p>@endif
    </div>
    <div class="bg-white rounded-card border border-border shadow-card p-4">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Students</p>
        <p class="text-lg font-semibold text-slate-800 mt-0.5">{{ $program->students->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-lg font-semibold text-slate-800">Manager credentials</h2>
        <p class="text-sm text-slate-600">
            Subject/Program ID:
            <span class="font-mono font-semibold text-slate-900 tabular-nums">{{ $program->id }}</span>
        </p>
    </div>
    <div class="p-5">
        @if(!empty($highlightNewCredentials))
            <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <strong>New manager login generated.</strong> Username and password are stored below for this subject/program. Share them only with the subject/program manager.
            </div>
        @endif
        <p class="text-xs text-slate-500 mb-4">Managers sign in with the <strong>username</strong> (login ID) and <strong>password</strong> below. Passwords are encrypted in the database and shown here for college admins.</p>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="bg-slate-100 border-b border-border">
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Credential ID</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Username</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Password</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Status</th>
                        <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($credentials as $credential)
                        <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5">
                            <td class="px-4 py-3 text-sm font-mono tabular-nums text-slate-800">{{ $credential->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium font-mono text-slate-900">{{ $credential->username }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-slate-800">
                                @if($credential->status === 'active' && filled($credential->last_plain_password))
                                    {{ $credential->last_plain_password }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $credential->status }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $credential->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No credentials found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Completion Requests</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Status</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Requested At</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Notes</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($program->completionRequests as $request)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5">
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $request->status }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $request->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $request->notes ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($request->status === 'pending')
                                <form action="{{ route('college.programs.approve-completion', [$event, $program]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-button text-sm font-medium text-white bg-success hover:opacity-90">Approve</button>
                                </form>
                            @else
                                <span class="text-slate-500 text-sm">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No completion requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

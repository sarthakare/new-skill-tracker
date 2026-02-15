@extends('manager.layouts.app')

@section('title', 'Syllabus')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </span>
        Syllabus - {{ $program->name }}
    </h1>
    <p class="mt-2 text-slate-600">Add syllabus topics and subtopics. Mark each as complete as you teach it.</p>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Add Topic</h2>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.syllabus.topics.store', $program) }}" method="POST" class="flex flex-wrap items-end gap-3">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Topic Name</label>
                <input type="text" name="title" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" placeholder="e.g. Introduction to Python" required>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Add Topic</button>
        </form>
    </div>
</div>

@forelse($topics as $topic)
    <div class="topic-card bg-white rounded-card border border-border shadow-card overflow-hidden mb-4" id="topic-{{ $topic->id }}">
        <div class="px-5 py-4 border-b border-border bg-slate-50/80">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('manager.program.syllabus.topics.toggle-complete', [$program, $topic]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-0 text-inherit no-underline hover:opacity-80" title="{{ $topic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                            @if($topic->is_complete)
                                <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            @else
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </button>
                    </form>
                    <span class="topic-title-display {{ $topic->is_complete ? 'line-through text-slate-500' : '' }} font-medium text-slate-800">{{ $topic->title }}</span>
                    <form action="{{ route('manager.program.syllabus.topics.update', [$program, $topic]) }}" method="POST" class="topic-edit-form hidden inline-flex items-center gap-2" style="max-width: 320px;">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary flex-1 min-w-0" value="{{ $topic->title }}" required>
                        <button type="submit" class="shrink-0 px-3 py-1.5 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">Save</button>
                        <button type="button" class="topic-edit-cancel shrink-0 px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 border border-border hover:bg-slate-50">Cancel</button>
                    </form>
                    @if($topic->scheduled_date || $topic->scheduled_time)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ trim(($topic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '')) }}
                        </span>
                    @endif
                    <div class="topic-actions inline-flex items-center gap-1">
                        <button type="button" class="topic-edit-btn p-1.5 rounded text-slate-500 hover:bg-slate-200 hover:text-slate-700" title="Edit topic">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <form action="{{ route('manager.program.syllabus.topics.destroy', [$program, $topic]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this topic and all its subtopics?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded text-slate-500 hover:bg-red-100 hover:text-red-600" title="Delete topic">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <form action="{{ route('manager.program.syllabus.subtopics.store', [$program, $topic]) }}" method="POST" class="flex flex-wrap items-center gap-2">
                    @csrf
                    <input type="text" name="title" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary min-w-[200px] sm:min-w-[320px]" placeholder="Add subtopic..." required>
                    <button type="submit" class="shrink-0 px-4 py-2 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">Add Subtopic</button>
                </form>
            </div>
            <form action="{{ route('manager.program.syllabus.topics.schedule', [$program, $topic]) }}" method="POST" class="flex flex-wrap items-center gap-2">
                @csrf
                <label class="text-sm text-slate-500">Scheduled:</label>
                <input type="date" name="scheduled_date" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[150px]" value="{{ $topic->scheduled_date?->format('Y-m-d') }}">
                <input type="time" name="scheduled_time" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary w-[100px]" value="{{ $topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '' }}">
                <button type="submit" class="px-3 py-1.5 rounded-button text-sm font-medium text-slate-700 border border-border hover:bg-slate-50">Set</button>
            </form>
        </div>
        <div class="p-5 pt-0">
            <ul class="space-y-0">
                @forelse($topic->subtopics as $subtopic)
                    <li class="flex items-center justify-between py-2 pl-4 border-b border-border last:border-0">
                        <div class="flex flex-wrap items-center gap-2 min-w-0">
                            <form action="{{ route('manager.program.syllabus.subtopics.toggle-complete', [$program, $subtopic]) }}" method="POST" class="inline shrink-0">
                                @csrf
                                <button type="submit" class="p-0 text-inherit no-underline hover:opacity-80" title="{{ $subtopic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                                    @if($subtopic->is_complete)
                                        <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                </button>
                            </form>
                            <span class="subtopic-title-display {{ $subtopic->is_complete ? 'line-through text-slate-500' : '' }} text-sm text-slate-700">{{ $subtopic->title }}</span>
                            <form action="{{ route('manager.program.syllabus.subtopics.update', [$program, $subtopic]) }}" method="POST" class="subtopic-edit-form hidden inline-flex items-center gap-2 min-w-0">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" class="rounded-input border border-slate-300 focus:ring-2 focus:ring-primary min-w-[180px] flex-1" value="{{ $subtopic->title }}" required>
                                <button type="submit" class="shrink-0 px-2 py-1 rounded-button text-sm font-medium text-primary border border-primary/30 hover:bg-primary/10">Save</button>
                                <button type="button" class="subtopic-edit-cancel shrink-0 px-2 py-1 rounded-button text-sm font-medium text-slate-600 border border-border hover:bg-slate-50">Cancel</button>
                            </form>
                            <div class="subtopic-actions inline-flex items-center gap-0.5">
                                <button type="button" class="subtopic-edit-btn p-1 rounded text-slate-400 hover:text-slate-600" title="Edit"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                <form action="{{ route('manager.program.syllabus.subtopics.destroy', [$program, $subtopic]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subtopic?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-red-600" title="Delete"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-2 pl-4 text-sm text-slate-500">No subtopics yet. Add one above.</li>
                @endforelse
            </ul>
        </div>
    </div>
@empty
    <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
        <div class="p-12 text-center text-slate-500">
            <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            <p class="mt-2">No topics yet. Add your first topic above.</p>
        </div>
    </div>
@endforelse

@push('scripts')
<script>
document.querySelectorAll('.topic-edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.topic-card');
        if (!card) return;
        const display = card.querySelector('.topic-title-display');
        const actions = card.querySelector('.topic-actions');
        const form = card.querySelector('.topic-edit-form');
        const input = form && form.querySelector('input');
        if (display) display.classList.add('hidden');
        if (actions) actions.classList.add('hidden');
        if (form) form.classList.remove('hidden');
        if (input) input.focus();
    });
});
document.querySelectorAll('.topic-edit-cancel').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.topic-card');
        if (!card) return;
        card.querySelector('.topic-title-display')?.classList.remove('hidden');
        card.querySelector('.topic-actions')?.classList.remove('hidden');
        card.querySelector('.topic-edit-form')?.classList.add('hidden');
    });
});
document.querySelectorAll('.subtopic-edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const li = this.closest('li');
        if (!li) return;
        li.querySelector('.subtopic-title-display')?.classList.add('hidden');
        li.querySelector('.subtopic-actions')?.classList.add('hidden');
        const form = li.querySelector('.subtopic-edit-form');
        if (form) form.classList.remove('hidden');
        const input = form && form.querySelector('input');
        if (input) input.focus();
    });
});
document.querySelectorAll('.subtopic-edit-cancel').forEach(btn => {
    btn.addEventListener('click', function() {
        const li = this.closest('li');
        if (!li) return;
        li.querySelector('.subtopic-title-display')?.classList.remove('hidden');
        li.querySelector('.subtopic-actions')?.classList.remove('hidden');
        li.querySelector('.subtopic-edit-form')?.classList.add('hidden');
    });
});
</script>
@endpush
@endsection

@extends('manager.layouts.app')

@section('title', 'Remarks')

@section('content')
<div class="relative -mx-4 -mt-2 mb-6 px-4 sm:-mx-6 sm:px-6">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -right-16 -top-12 h-56 w-56 rounded-full bg-violet-400/[0.12] blur-3xl"></div>
        <div class="absolute left-0 top-24 h-40 w-40 rounded-full bg-primary/[0.08] blur-2xl"></div>
    </div>
</div>

<div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <div class="flex items-center gap-2">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary/15 to-violet-500/10 text-primary ring-1 ring-primary/15">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m-4 4h10M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            <h1 class="truncate text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Remarks</h1>
        </div>
        <p class="mt-1.5 max-w-2xl text-xs leading-relaxed text-slate-600 sm:text-sm">
            Submissions appear above each note; use <span class="font-medium text-slate-700">View submission</span> for code. Text below is shown on the student dashboard.
        </p>
        <p class="mt-1 text-[11px] text-slate-500">{{ $program->name }}</p>
    </div>
    <a href="{{ route('manager.program.students.index', $program) }}"
       class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-primary/25 hover:bg-slate-50 sm:text-sm">
        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Students
    </a>
</div>

@if(session('success'))
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-200/90 bg-emerald-50/90 px-3 py-2 text-sm text-emerald-900">
        <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-red-200/90 bg-red-50/90 px-3 py-2 text-sm text-red-900">
        <svg class="h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-[0_4px_24px_-8px_rgba(15,23,42,0.12)] ring-1 ring-slate-200/60">
    @if($students->isEmpty())
        <div class="px-4 py-10 text-center sm:px-6">
            <p class="text-sm text-slate-600">No students in this subject/program yet.</p>
            <a href="{{ route('manager.program.students.index', $program) }}" class="mt-3 inline-flex text-sm font-semibold text-primary hover:underline">Add students</a>
        </div>
    @else
        <form action="{{ route('manager.program.remarks.update', $program) }}" method="POST" id="remarks-form">
            @csrf

            <div class="sticky top-0 z-20 flex flex-col gap-3 border-b border-slate-100 bg-white/95 px-4 py-3 backdrop-blur supports-[backdrop-filter]:bg-white/80 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div class="relative min-w-0 flex-1 sm:max-w-md">
                    <label for="remarks-student-search" class="sr-only">Search students</label>
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input
                        type="search"
                        id="remarks-student-search"
                        autocomplete="off"
                        placeholder="Search by name, roll, email, department…"
                        class="w-full rounded-xl border-0 bg-slate-100/90 py-2 pl-9 pr-3 text-sm text-slate-900 shadow-inner ring-1 ring-slate-200/80 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/35"
                    />
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 sm:justify-end">
                    <p id="remarks-count-label" class="text-xs font-medium tabular-nums text-slate-500"></p>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-primary-hover sm:text-sm">
                        <svg class="h-4 w-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save remarks
                    </button>
                </div>
            </div>

            <div class="space-y-3 p-3 sm:p-4">
                <p id="remarks-no-matches" class="hidden rounded-xl border border-dashed border-slate-200 bg-slate-50/80 py-8 text-center text-sm text-slate-500">No students match your search.</p>
                @foreach($students as $student)
                    @php
                        $searchBlob = strtolower(implode(' ', array_filter([
                            (string) ($student->displayRollNumber() ?? ''),
                            (string) ($student->displayName() ?? ''),
                            (string) ($student->email ?? ''),
                            (string) ($student->departmentLabel() ?: ''),
                            (string) ($student->status ?? ''),
                        ])));
                    @endphp
                    <article
                        data-remarks-student
                        data-search-text="{{ e($searchBlob) }}"
                        class="overflow-hidden rounded-xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/40 shadow-sm ring-1 ring-slate-100"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-2 border-b border-slate-100/90 bg-white/80 px-3 py-2 sm:px-3.5 sm:py-2.5">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
                                    <span class="text-sm font-bold text-slate-900">{{ $student->displayName() }}</span>
                                    @if($student->displayRollNumber())
                                        <span class="text-xs font-medium tabular-nums text-slate-500">{{ $student->displayRollNumber() }}</span>
                                    @endif
                                </div>
                                <p class="mt-0.5 truncate text-xs text-slate-500">{{ $student->email ?: '—' }}</p>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                                @if($student->departmentLabel())
                                    <span class="inline-flex max-w-[10rem] truncate rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600 ring-1 ring-slate-200/80" title="{{ $student->departmentLabel() }}">{{ $student->departmentLabel() }}</span>
                                @endif
                                <span class="inline-flex rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-700 ring-1 ring-slate-200/80">{{ $student->status }}</span>
                            </div>
                        </div>
                        <div class="px-3 py-2.5 sm:px-3.5">
                            @include('manager.partials.student-assignment-submissions', [
                                'submissions' => $student->user_id ? ($submissionGroupsByUserId->get($student->user_id) ?? collect()) : collect(),
                                'compact' => true,
                            ])
                            <label for="remarks-field-{{ $student->id }}" class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">Instructor remarks</label>
                            <textarea
                                id="remarks-field-{{ $student->id }}"
                                name="remarks[{{ $student->id }}]"
                                rows="3"
                                placeholder="Feedback, notes, recognition…"
                                class="w-full rounded-lg border-0 bg-slate-100/80 px-3 py-2 text-sm text-slate-900 shadow-inner ring-1 ring-slate-200/80 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/35 @error('remarks.' . $student->id) ring-red-400 @enderror"
                            >{{ old('remarks.' . $student->id, $student->manager_remarks) }}</textarea>
                            @error('remarks.' . $student->id)
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="flex justify-end border-t border-slate-100 bg-slate-50/50 px-4 py-3 sm:px-5">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-hover">
                    <svg class="h-4 w-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save remarks
                </button>
            </div>
        </form>
    @endif
</div>

@if(!$students->isEmpty())
@push('scripts')
<script>
(function () {
    const input = document.getElementById('remarks-student-search');
    const cards = document.querySelectorAll('[data-remarks-student]');
    const empty = document.getElementById('remarks-no-matches');
    const label = document.getElementById('remarks-count-label');
    const total = cards.length;

    function runFilter() {
        const q = (input && input.value) ? input.value.trim().toLowerCase() : '';
        let visible = 0;
        cards.forEach(function (card) {
            const hay = (card.getAttribute('data-search-text') || '').toLowerCase();
            const show = q === '' || hay.indexOf(q) !== -1;
            card.classList.toggle('hidden', !show);
            if (show) {
                visible++;
            }
        });
        if (label) {
            if (q === '') {
                label.textContent = total + (total === 1 ? ' student' : ' students');
            } else {
                label.textContent = 'Showing ' + visible + ' of ' + total;
            }
        }
        if (empty) {
            empty.classList.toggle('hidden', visible !== 0 || q === '');
        }
    }

    if (input) {
        input.addEventListener('input', runFilter);
        input.addEventListener('search', runFilter);
    }
    runFilter();
})();
</script>
@endpush
@endif
@endsection

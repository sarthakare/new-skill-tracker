{{--
    $submissions: Illuminate\Support\Collection of App\Models\SyllabusAssignmentSubmission (syllabusAssignment loaded)
    $compact: optional bool — tighter spacing on dense pages (e.g. remarks list)
--}}
@php
    $submissions = $submissions ?? collect();
    $compact = ! empty($compact);
    $judge0LanguagesById = collect(config('judge0.languages', []))->keyBy('id');
@endphp
@if($submissions->isNotEmpty())
    <div class="{{ $compact ? 'mb-2 rounded-lg border border-slate-200 bg-slate-50/90 p-2 ring-1 ring-slate-100' : 'mb-3 rounded-lg border border-slate-200 bg-slate-50/90 p-3 ring-1 ring-slate-100' }}">
        <p class="{{ $compact ? 'mb-1.5 text-[10px]' : 'mb-2 text-[11px]' }} font-bold uppercase tracking-wide text-slate-600">Submitted assignments</p>
        <ul class="{{ $compact ? 'space-y-1.5' : 'space-y-2' }}">
            @foreach($submissions as $submission)
                @php
                    $assignment = $submission->syllabusAssignment;
                    $title = $assignment?->title ?? 'Assignment';
                @endphp
                <li class="rounded-md border border-slate-200/90 bg-white {{ $compact ? 'p-2 text-[13px] leading-snug' : 'p-2.5 text-sm' }} shadow-sm">
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <span class="font-semibold text-slate-900">{{ $title }}</span>
                        <span class="inline-flex shrink-0 rounded-md bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800 ring-1 ring-emerald-200/80">Submitted</span>
                        <span class="text-xs text-slate-500 tabular-nums">{{ $submission->created_at->timezone(config('app.timezone'))->format('M j, Y g:i A') }}</span>
                    </div>
                    <details class="mt-2 group">
                        <summary class="cursor-pointer list-none text-xs font-semibold text-primary hover:underline [&::-webkit-details-marker]:hidden">
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 shrink-0 transition group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                View submission
                            </span>
                        </summary>
                        <div class="mt-2 space-y-2 border-t border-slate-100 pt-2 text-xs">
                            @if($submission->judge0_language_id)
                                <p class="text-slate-600">
                                    <span class="font-medium text-slate-700">Language:</span>
                                    {{ data_get($judge0LanguagesById->get($submission->judge0_language_id), 'name') ?? ('#'.$submission->judge0_language_id) }}
                                </p>
                            @endif
                            @if(filled($submission->source_code))
                                <pre class="max-h-64 overflow-auto rounded border border-slate-200 bg-slate-950 p-2.5 font-mono text-[11px] leading-relaxed text-slate-100 sm:text-xs">{{ $submission->source_code }}</pre>
                            @else
                                <p class="text-slate-500">No source code was stored for this submission.</p>
                            @endif
                        </div>
                    </details>
                </li>
            @endforeach
        </ul>
    </div>
@endif

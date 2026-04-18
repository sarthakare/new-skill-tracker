@extends('manager.layouts.app')

@section('title', 'Create assignment')
@section('title_suffix', '')

@php
    $judge0Languages = config('judge0.languages', []);
    $oldLanguageIds = array_map('intval', (array) old('languages_supported', []));
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('manager.program.syllabus.index', $program) }}#topic-{{ $subtopic->syllabus_topic_id }}" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-hover mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to syllabus
    </a>
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
        </span>
        Create coding assignment
    </h1>
    <p class="mt-2 text-slate-600 text-sm">
        Placed under
        <span class="font-medium text-slate-800">{{ $subtopic->syllabusTopic->title }}</span>
        →
        <span class="font-medium text-slate-800">{{ $subtopic->title }}</span>
    </p>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">Assignment details</h2>
        <p class="text-sm text-slate-500 mt-1">Students will see the title and description; starter code, tests, and limits are used when you wire up Judge0 execution.</p>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.syllabus.assignments.store', [$program, $subtopic]) }}" method="POST" class="space-y-5 max-w-3xl">
            @csrf
            <div>
                <label for="assignment_title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                <input type="text" id="assignment_title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="e.g. Two Sum" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assignment_description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea id="assignment_description" name="description" rows="8" required placeholder="Problem statement, sample input/output, constraints…" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary font-mono text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="assignment_difficulty" class="block text-sm font-medium text-slate-700 mb-1">Difficulty</label>
                    <select id="assignment_difficulty" name="difficulty" required class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('difficulty') border-red-500 @enderror">
                        <option value="" disabled {{ old('difficulty') ? '' : 'selected' }}>Select…</option>
                        @foreach (['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('difficulty') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('difficulty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="assignment_time_limit" class="block text-sm font-medium text-slate-700 mb-1">Time limit (seconds) <span class="text-slate-500 font-normal">(optional)</span></label>
                    <input type="number" id="assignment_time_limit" name="time_limit" value="{{ old('time_limit') }}" min="1" max="600" placeholder="e.g. 5" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('time_limit') border-red-500 @enderror">
                    <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">When you run student code through Judge0, this caps how long each submission may use the CPU before it is stopped (time limit / TL). Leave blank until you configure auto-grading.</p>
                    @error('time_limit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <span class="block text-sm font-medium text-slate-700 mb-1">Languages students may use <span class="text-slate-500 font-normal">(optional, same list as “Run code” on the student dashboard)</span></span>
                <p class="text-xs text-slate-500 mb-2">Select one or more Judge0 languages. Stored as language IDs so they match the student code runner.</p>
                <div class="max-h-64 overflow-y-auto rounded-input border border-slate-300 bg-slate-50/50 p-3 space-y-2">
                    @foreach ($judge0Languages as $lang)
                        <label class="flex items-start gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="languages_supported[]" value="{{ $lang['id'] }}" class="mt-0.5 rounded border-slate-300 text-primary focus:ring-primary shrink-0" @checked(in_array((int) $lang['id'], $oldLanguageIds, true))>
                            <span>{{ $lang['name'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('languages_supported')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('languages_supported.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assignment_starter_code" class="block text-sm font-medium text-slate-700 mb-1">Starter code <span class="text-slate-500 font-normal">(optional)</span></label>
                <textarea id="assignment_starter_code" name="starter_code" rows="6" placeholder="def two_sum(nums, target):&#10;    pass" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary font-mono text-sm @error('starter_code') border-red-500 @enderror">{{ old('starter_code') }}</textarea>
                @error('starter_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assignment_test_cases" class="block text-sm font-medium text-slate-700 mb-1">Test cases <span class="text-slate-500 font-normal">(optional)</span></label>
                <textarea id="assignment_test_cases" name="test_cases" rows="6" placeholder="stdin lines, JSON payloads, or script snippets your grader will run…" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary font-mono text-sm @error('test_cases') border-red-500 @enderror">{{ old('test_cases') }}</textarea>
                @error('test_cases')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assignment_expected_output" class="block text-sm font-medium text-slate-700 mb-1">Expected output <span class="text-slate-500 font-normal">(optional)</span></label>
                <textarea id="assignment_expected_output" name="expected_output" rows="4" placeholder="Exact stdout you expect for the test cases above" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary font-mono text-sm @error('expected_output') border-red-500 @enderror">{{ old('expected_output') }}</textarea>
                @error('expected_output')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save assignment</button>
                <a href="{{ route('manager.program.syllabus.index', $program) }}#topic-{{ $subtopic->syllabus_topic_id }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-button font-medium text-slate-700 border border-border hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

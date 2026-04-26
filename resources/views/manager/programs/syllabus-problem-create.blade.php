@extends('manager.layouts.app')

@section('title', 'Create problem')
@section('title_suffix', '')

@php
    $judge0Languages = config('judge0.languages', []);
    $oldLanguageIds = array_map('intval', (array) old('languages_supported', []));
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('manager.program.syllabus.index', $program) }}#topic-{{ $subtopic->syllabus_topic_id }}" class="mb-3 inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-hover">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        Back to syllabus
    </a>
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 13h6m-2 8a9 9 0 100-18 9 9 0 000 18z" /></svg>
        </span>
        Create problem
    </h1>
    <p class="mt-2 text-sm text-slate-600">
        Placed under
        <span class="font-medium text-slate-800">{{ $subtopic->syllabusTopic->title }}</span>
        →
        <span class="font-medium text-slate-800">{{ $subtopic->title }}</span>
    </p>
</div>

<div class="overflow-hidden rounded-card border border-border bg-white shadow-card">
    <div class="border-b border-border bg-primary/5 px-5 py-4">
        <h2 class="text-lg font-semibold text-slate-800">Problem details</h2>
        <p class="mt-1 text-sm text-slate-500">Use this form to add a coding problem in the syllabus timeline.</p>
    </div>
    <div class="p-5">
        <form action="{{ route('manager.program.syllabus.assignments.store', [$program, $subtopic]) }}" method="POST" class="max-w-3xl space-y-5">
            @csrf
            <input type="hidden" name="type" value="problem">
            <div>
                <label for="problem_title" class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                <input type="text" id="problem_title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="e.g. Reverse Linked List" class="w-full rounded-input border border-slate-300 focus:border-primary focus:ring-2 focus:ring-primary @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="problem_description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                <textarea id="problem_description" name="description" rows="8" required placeholder="Problem statement, input/output format, constraints…" class="w-full rounded-input border border-slate-300 font-mono text-sm focus:border-primary focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <span class="mb-1 block text-sm font-medium text-slate-700">Languages students may use <span class="font-normal text-slate-500">(optional)</span></span>
                <div class="max-h-64 space-y-2 overflow-y-auto rounded-input border border-slate-300 bg-slate-50/50 p-3">
                    @foreach ($judge0Languages as $lang)
                        <label class="flex cursor-pointer items-start gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="languages_supported[]" value="{{ $lang['id'] }}" class="mt-0.5 shrink-0 rounded border-slate-300 text-primary focus:ring-primary" @checked(in_array((int) $lang['id'], $oldLanguageIds, true))>
                            <span>{{ $lang['name'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('languages_supported')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('languages_supported.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="problem_starter_code" class="mb-1 block text-sm font-medium text-slate-700">Starter code <span class="font-normal text-slate-500">(optional)</span></label>
                <textarea id="problem_starter_code" name="starter_code" rows="6" class="w-full rounded-input border border-slate-300 font-mono text-sm focus:border-primary focus:ring-2 focus:ring-primary @error('starter_code') border-red-500 @enderror">{{ old('starter_code') }}</textarea>
                @error('starter_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="problem_test_cases" class="mb-1 block text-sm font-medium text-slate-700">Test cases <span class="font-normal text-slate-500">(optional)</span></label>
                <textarea id="problem_test_cases" name="test_cases" rows="6" class="w-full rounded-input border border-slate-300 font-mono text-sm focus:border-primary focus:ring-2 focus:ring-primary @error('test_cases') border-red-500 @enderror">{{ old('test_cases') }}</textarea>
                @error('test_cases')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="problem_expected_output" class="mb-1 block text-sm font-medium text-slate-700">Expected output <span class="font-normal text-slate-500">(optional)</span></label>
                <textarea id="problem_expected_output" name="expected_output" rows="4" class="w-full rounded-input border border-slate-300 font-mono text-sm focus:border-primary focus:ring-2 focus:ring-primary @error('expected_output') border-red-500 @enderror">{{ old('expected_output') }}</textarea>
                @error('expected_output')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap items-center gap-2 pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-button bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-hover">Save problem</button>
                <a href="{{ route('manager.program.syllabus.index', $program) }}#topic-{{ $subtopic->syllabus_topic_id }}" class="inline-flex items-center justify-center rounded-button border border-border px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

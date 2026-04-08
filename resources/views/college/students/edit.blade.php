@extends('college.layouts.app')

@section('title', 'Edit student')

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </span>
        Edit student
    </h1>
    <p class="mt-2 text-sm text-slate-600">Update profile details for this student account. Leave password fields blank to keep the current password.</p>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">
            @if(filled($student->roll_number))
                <span class="text-slate-600 font-mono tabular-nums">{{ $student->roll_number }}</span>
                <span class="text-slate-400 mx-1.5">·</span>
            @endif
            {{ $student->name }}
        </h2>
        <p class="text-sm text-slate-500 mt-0.5">Registered {{ $student->created_at?->timezone(config('app.timezone'))->format('M j, Y') ?? '—' }}</p>
    </div>
    <div class="p-5">
        <form action="{{ route('college.students.update', $student) }}" method="POST" class="space-y-4 max-w-xl">
            @csrf
            @method('PUT')
            <div>
                <label for="roll_number" class="block text-sm font-medium text-slate-700 mb-1">Roll number <span class="text-red-500">*</span></label>
                <input type="text" id="roll_number" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}" required
                       class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('roll_number') border-red-500 @enderror">
                @error('roll_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                       class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required autocomplete="email"
                       class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                <select id="department_id" name="department_id" required
                        class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                    <option value="" disabled {{ old('department_id', $student->department_id) ? '' : 'selected' }}>Select department</option>
                    @forelse($departments as $d)
                        <option value="{{ $d->id }}" {{ (string) old('department_id', $student->department_id) === (string) $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @empty
                        <option value="" disabled>No departments — add them under Departments first</option>
                    @endforelse
                </select>
                @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="mobile" class="block text-sm font-medium text-slate-700 mb-1">Mobile <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile) }}" inputmode="tel"
                       placeholder="Optional"
                       class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-border">
                <p class="text-sm font-medium text-slate-800 mb-3">Password <span class="text-slate-400 font-normal">(optional)</span></p>
                <p class="text-xs text-slate-500 mb-3">Set a new password for this student only if needed. Minimum 8 characters.</p>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New password</label>
                        <input type="password" id="password" name="password" value="" autocomplete="new-password"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm new password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" value="" autocomplete="new-password"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">Save changes</button>
                <a href="{{ route('college.students.index') }}" class="inline-flex items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Back to students</a>
            </div>
        </form>
    </div>
</div>
@endsection

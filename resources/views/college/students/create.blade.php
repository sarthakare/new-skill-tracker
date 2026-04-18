@extends('college.layouts.app')

@section('title', 'Add student')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
            <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            </span>
            Add student
        </h1>
        <p class="mt-2 text-sm text-slate-600 max-w-2xl">Create a student account for your college. They will sign in with the email and password you set here—share those credentials securely.</p>
    </div>
    <a href="{{ route('college.students.index') }}" class="inline-flex shrink-0 items-center gap-2 px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">
        Back to students
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-border bg-primary/5">
        <h2 class="text-lg font-semibold text-slate-800">New student account</h2>
        <p class="text-sm text-slate-500 mt-1">Roll numbers must be unique among students at your college. Fields marked <span class="text-red-500">*</span> are required.</p>
    </div>
    <div class="p-5 sm:p-6 lg:p-8">
        <form action="{{ route('college.students.store') }}" method="POST" class="mx-auto max-w-4xl space-y-8">
            @csrf

            <section aria-labelledby="student-profile-heading">
                <div class="mb-4">
                    <h3 id="student-profile-heading" class="text-sm font-semibold text-slate-800">Student profile</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Identity and placement for this account.</p>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5">
                    <div>
                        <label for="roll_number" class="block text-sm font-medium text-slate-700 mb-1">Roll number <span class="text-red-500">*</span></label>
                        <input type="text" id="roll_number" name="roll_number" value="{{ old('roll_number') }}" required autocomplete="off"
                               placeholder="As on university ID"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('roll_number') border-red-500 @enderror">
                        @error('roll_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Legal name as on records"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="student@example.com"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select id="department_id" name="department_id" required
                                class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary bg-white @error('department_id') border-red-500 @enderror">
                            <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>Select department</option>
                            @forelse($departments as $d)
                                <option value="{{ $d->id }}" {{ (string) old('department_id') === (string) $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @empty
                                <option value="" disabled>No departments — add them under Departments first</option>
                            @endforelse
                        </select>
                        @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-slate-700 mb-1">Mobile <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" inputmode="tel"
                               placeholder="Phone or WhatsApp"
                               class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror">
                        @error('mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section class="pt-2 border-t border-border" aria-labelledby="student-credentials-heading">
                <div class="rounded-xl border border-slate-200/90 bg-slate-50/60 p-4 sm:p-5">
                    <div class="mb-4">
                        <h3 id="student-credentials-heading" class="text-sm font-semibold text-slate-800">Sign-in credentials</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Minimum 8 characters. The student will use these with the student login page.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <x-password-input id="password" name="password" required autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror" />
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm password <span class="text-red-500">*</span></label>
                            <x-password-input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                        </div>
                    </div>
                </div>
            </section>

            <div class="pt-2 border-t border-border space-y-4">
                <p class="text-xs text-slate-500 max-w-2xl">After creating the account, you can edit details or manage access from the students list.</p>
                <div class="flex flex-wrap items-center gap-2 justify-end">
                    <a href="{{ route('college.students.index') }}" class="inline-flex justify-center items-center px-4 py-2 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex justify-center items-center gap-2 px-4 py-2 rounded-button font-medium text-white bg-primary hover:bg-primary-hover">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Create student
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

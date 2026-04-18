<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student login - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans antialiased">
    <div class="hidden md:flex md:w-1/2 bg-primary flex-col justify-center px-12 lg:px-20 py-16">
        <div class="max-w-md">
            <div class="mb-6 rounded-xl bg-white/95 p-3 shadow-sm inline-block max-w-full">
                @include('partials.host-institution-logo', ['class' => 'h-11 sm:h-12 w-auto max-w-full object-contain object-left'])
            </div>
            <div class="flex items-center gap-3 text-white/90 mb-6">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                </span>
                <span class="text-xl font-semibold tracking-tight">Skill Tracker</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">Student portal</h1>
            <p class="mt-4 text-white/80 text-lg">Sign in to view your dashboard.</p>
        </div>
    </div>
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10 md:p-12">
        <div class="w-full max-w-md">
            <div class="md:hidden mb-6 rounded-xl bg-white p-3 shadow-sm border border-slate-200/80 inline-block max-w-full">
                @include('partials.host-institution-logo', ['class' => 'h-10 w-auto max-w-full object-contain object-left'])
            </div>
            <div class="md:hidden flex items-center gap-2 text-primary mb-8">
                <span class="font-semibold text-lg">Skill Tracker</span>
            </div>
            <div class="bg-white rounded-card shadow-card border border-slate-200/80 p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Student sign in</h2>
                    <p class="text-slate-500 mt-1">Use the email and password for your student account</p>
                </div>
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('student.login.post') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <x-password-input id="password" name="password" required class="w-full px-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary" />
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-slate-600">Remember me</label>
                    </div>
                    <button type="submit" class="w-full py-3 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Sign in
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-600">
                    No account yet?
                    <a href="{{ route('student.register') }}" class="text-primary font-medium hover:underline">Create one</a>
                </p>
                <p class="mt-4 text-center text-xs text-slate-500">
                    <a href="{{ route('login') }}" class="text-slate-500 hover:text-slate-700 underline">Other sign-in options</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

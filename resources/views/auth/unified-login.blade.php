<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans antialiased">
    {{-- Split left: branding --}}
    <div class="hidden md:flex md:w-1/2 bg-primary flex-col justify-center px-12 lg:px-20 py-16">
        <div class="max-w-md">
            <div class="flex items-center gap-3 text-white/90 mb-6">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <span class="text-xl font-semibold tracking-tight">Skill Tracker</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">
                Track and grow skills in one place.
            </h1>
            <p class="mt-4 text-white/80 text-lg">
                Sign in to access your dashboard.
            </p>
        </div>
    </div>

    {{-- Split right: form --}}
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10 md:p-12">
        <div class="w-full max-w-md animate-[fadeIn_0.4s_ease-out]">
            <div class="md:hidden flex items-center gap-2 text-primary mb-8">
                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <span class="font-semibold text-lg">Skill Tracker</span>
            </div>

            <div class="bg-white rounded-card shadow-card border border-slate-200/80 p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Welcome back</h2>
                    <p class="text-slate-500 mt-1">Sign in to continue</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Username / Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </span>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   required
                                   autofocus
                                   placeholder="Enter email or username"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-input border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('username') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">
                            Use email for Super Admin / College Admin, or username for Vendor Event access
                        </p>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Enter your password"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-input border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="remember"
                               name="remember"
                               class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-slate-600">Remember me</label>
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Sign in
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-slate-500">
                    System will automatically detect your account type and redirect you
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>

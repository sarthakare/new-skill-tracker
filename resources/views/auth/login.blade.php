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
    <div class="hidden md:flex md:w-1/2 bg-primary flex-col justify-center px-12 lg:px-20 py-16">
        <div class="max-w-md">
            <div class="flex items-center gap-3 text-white/90 mb-6">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </span>
                <span class="text-xl font-semibold tracking-tight">Skill Tracker</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">Track and grow skills in one place.</h1>
            <p class="mt-4 text-white/80 text-lg">Sign in to access your dashboard.</p>
        </div>
    </div>
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10 md:p-12">
        <div class="w-full max-w-md">
            <div class="md:hidden flex items-center gap-2 text-primary mb-8">
                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </span>
                <span class="font-semibold text-lg">Skill Tracker</span>
            </div>
            <div class="bg-white rounded-card shadow-card border border-slate-200/80 p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Skill Tracker</h2>
                    <p class="text-slate-500 mt-1">Login to continue</p>
                </div>
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('college.login.post') }}" id="loginForm" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Login As <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="role-option flex flex-col items-center justify-center p-4 rounded-lg border-2 cursor-pointer transition-all {{ old('role', $defaultRole ?? 'college') === 'college' ? 'border-primary bg-primary/10' : 'border-slate-200 hover:border-primary/50 hover:bg-primary/5' }}" id="collegeOption">
                                <input type="radio" name="role" value="college" {{ old('role', $defaultRole ?? 'college') === 'college' ? 'checked' : '' }} required class="sr-only">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span class="font-semibold text-slate-800 text-sm">College Admin</span>
                            </label>
                            <label class="role-option flex flex-col items-center justify-center p-4 rounded-lg border-2 cursor-pointer transition-all {{ old('role', $defaultRole ?? 'college') === 'super' ? 'border-primary bg-primary/10' : 'border-slate-200 hover:border-primary/50 hover:bg-primary/5' }}" id="superOption">
                                <input type="radio" name="role" value="super" {{ old('role', $defaultRole ?? 'college') === 'super' ? 'checked' : '' }} required class="sr-only">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                <span class="font-semibold text-slate-800 text-sm">Super Admin</span>
                            </label>
                        </div>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full pl-10 pr-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg></span>
                            <input type="password" id="password" name="password" required class="w-full pl-10 pr-4 py-2.5 rounded-input border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror">
                        </div>
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-slate-600">Remember me</label>
                    </div>
                    <button type="submit" id="submitBtn" class="w-full py-3 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const loginForm = document.getElementById('loginForm');
            const options = { college: document.getElementById('collegeOption'), super: document.getElementById('superOption') };
            const activeClasses = 'border-primary bg-primary/10';
            const inactiveClasses = 'border-slate-200 hover:border-primary/50 hover:bg-primary/5';

            function setActive(role) {
                options.college.className = 'role-option flex flex-col items-center justify-center p-4 rounded-lg border-2 cursor-pointer transition-all ' + (role === 'college' ? activeClasses : inactiveClasses);
                options.super.className = 'role-option flex flex-col items-center justify-center p-4 rounded-lg border-2 cursor-pointer transition-all ' + (role === 'super' ? activeClasses : inactiveClasses);
                loginForm.action = role === 'super' ? '{{ route("super-admin.login.post") }}' : '{{ route("college.login.post") }}';
            }

            roleInputs.forEach(function(input) {
                input.addEventListener('change', function() { setActive(this.value); });
            });

            var initial = document.querySelector('input[name="role"]:checked');
            if (initial) setActive(initial.value);
        })();
    </script>
</body>
</html>

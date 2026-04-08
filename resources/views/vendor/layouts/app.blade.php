<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event Dashboard') - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50/80 font-sans antialiased text-slate-800">
    {{-- Top bar (no sidebar) --}}
    <header class="sticky top-0 z-30 flex items-center justify-between h-14 px-4 sm:px-6 bg-slate-50 border-b-2 border-primary shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            @include('partials.host-institution-logo', ['class' => 'hidden sm:block h-7 w-auto max-w-[11rem] md:max-w-[13rem] object-contain object-left shrink-0'])
            <a href="{{ isset($event) ? route('vendor.event.dashboard', $event->id) : '#' }}" class="flex items-center gap-2 text-primary font-semibold truncate min-w-0">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/10">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </span>
                <span>{{ isset($event) ? $event->name : 'Event Dashboard' }}</span>
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-600">
                <span class="flex items-center gap-1.5">
                    <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </span>
                    {{ isset($credential) && $credential->vendor ? $credential->vendor->name : 'Vendor' }}
                </span>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- Main content (full width) --}}
    <main class="flex-1 min-w-0 p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm flex items-center justify-between" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800 ml-2" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-red-600 hover:text-red-800 ml-2" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm flex items-start justify-between" role="alert">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="text-red-600 hover:text-red-800 ml-2 shrink-0" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>

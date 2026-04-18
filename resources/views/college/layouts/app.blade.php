<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'College Admin') - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50/80 font-sans antialiased text-slate-800">
    {{-- Top bar --}}
    <header class="sticky top-0 z-30 flex items-center justify-between h-14 px-4 sm:px-6 bg-slate-50 border-b-2 border-primary shadow-sm">
        <div class="flex items-center gap-3">
            <button type="button"
                    id="sidebar-toggle"
                    aria-label="Toggle menu"
                    class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            @include('partials.host-institution-logo', ['class' => 'hidden sm:block h-7 w-auto max-w-[11rem] md:max-w-[13rem] object-contain object-left shrink-0'])
            <a href="{{ route('college.dashboard') }}" class="flex items-center gap-2 text-primary font-semibold min-w-0">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/10">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
                <span>{{ Auth::user()->college->name ?? 'College Admin' }}</span>
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-600">
                <span class="flex items-center gap-1.5">
                    <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                    {{ Auth::user()->name }}
                </span>
            </span>
            <form action="{{ route('college.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="flex">
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed top-14 left-0 z-20 w-56 h-[calc(100vh-3.5rem)] overflow-y-auto bg-slate-50 border-r border-border transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-out">
            <nav class="p-3 space-y-0.5">
                <a href="{{ route('college.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.dashboard') && !request()->routeIs('college.dashboard.completions') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('college.students.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.students.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Students
                </a>
                <a href="{{ route('college.departments.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.departments.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Departments
                </a>
                <a href="{{ route('college.events.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.events.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Year/Event
                </a>
                <a href="{{ route('college.vendors.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.vendors.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Vendors
                </a>
                <a href="{{ route('college.independent-trainers.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.independent-trainers.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Independent Trainers
                </a>
                <a href="{{ route('college.internal-managers.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.internal-managers.*') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Internal Managers
                </a>
                <a href="{{ route('college.dashboard.completions') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('college.dashboard.completions') ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-primary/10 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    Completions
                </a>
            </nav>
        </aside>

        {{-- Overlay when sidebar open on mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-10 bg-slate-900/50 md:hidden opacity-0 pointer-events-none transition-opacity duration-200" aria-hidden="true"></div>

        {{-- Main content --}}
        <main class="flex-1 min-w-0 p-4 sm:p-6 md:ml-56">
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
    </div>

    <script>
        (function() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggle = document.getElementById('sidebar-toggle');
            function open() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.setAttribute('aria-hidden', 'false');
            }
            function close() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.setAttribute('aria-hidden', 'true');
            }
            toggle && toggle.addEventListener('click', function() { sidebar.classList.contains('-translate-x-full') ? open() : close(); });
            overlay && overlay.addEventListener('click', close);
        })();
    </script>
    @stack('scripts')
</body>
</html>

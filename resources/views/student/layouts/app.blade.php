<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student') - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased text-slate-800 bg-slate-50 bg-[radial-gradient(ellipse_120%_80%_at_50%_-25%,rgba(67,56,202,0.11),transparent)]">
    <header class="sticky top-0 z-30 flex items-center justify-between h-14 px-4 sm:px-6 backdrop-blur-md bg-white/75 border-b border-slate-200/70 shadow-sm shadow-slate-900/5">
        <div class="flex items-center gap-3 min-w-0">
            @include('partials.host-institution-logo', ['class' => 'hidden sm:block h-7 w-auto max-w-[10rem] md:max-w-[12rem] object-contain object-left shrink-0'])
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 text-primary font-semibold truncate group">
                <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 ring-1 ring-primary/10 shrink-0 transition group-hover:from-primary/20 group-hover:to-primary/10">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                </span>
                <span class="truncate">Skill Tracker <span class="text-slate-500 font-normal">· Student</span></span>
            </a>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <span class="hidden sm:inline text-sm text-slate-600 truncate max-w-[12rem] px-2.5 py-1 rounded-lg bg-slate-100/80">{{ Auth::user()->name }}</span>
            <form action="{{ route('student.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100/90 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @if(session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/80 text-emerald-900 text-sm shadow-sm" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-5 p-4 rounded-2xl bg-red-50/90 border border-red-200/80 text-red-900 text-sm shadow-sm" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-red-50/90 border border-red-200/80 text-red-900 text-sm shadow-sm" role="alert">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>

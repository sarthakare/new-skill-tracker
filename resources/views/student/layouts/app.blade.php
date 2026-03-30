<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student') - Skill Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50/80 font-sans antialiased text-slate-800">
    <header class="sticky top-0 z-30 flex items-center justify-between h-14 px-4 sm:px-6 bg-slate-50 border-b-2 border-primary shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-primary font-semibold truncate">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/10 shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                </span>
                <span class="truncate">Skill Tracker <span class="text-slate-500 font-normal">· Student</span></span>
            </a>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span class="hidden sm:inline text-sm text-slate-600 truncate max-w-[12rem]">{{ Auth::user()->name }}</span>
            <form action="{{ route('student.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm" role="alert">
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

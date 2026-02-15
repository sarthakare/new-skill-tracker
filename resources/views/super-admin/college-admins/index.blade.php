@extends('super-admin.layouts.app')

@section('title', 'College Admins')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </span>
        College Admins
    </h1>
    <a href="{{ route('super-admin.colleges.create') }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        Create College with Admin
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">All College Admins</h2>
        <span class="text-sm text-slate-600">{{ $collegeAdmins->total() }} {{ Str::plural('admin', $collegeAdmins->total()) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px]">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">ID</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Email</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">College</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collegeAdmins as $admin)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm text-slate-700">{{ $admin->id }}</td>
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $admin->name }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $admin->email }}</td>
                        <td class="px-5 py-3">
                            @if($admin->college)
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $admin->college->name }}</span>
                            @else
                                <span class="text-sm text-slate-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('super-admin.college-admins.show', $admin) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-button text-sm font-medium text-white bg-info hover:bg-info/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-info transition-colors"
                               title="View Credentials">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-slate-500">No college admin credentials found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-border mt-4 flex justify-center">
        {{ $collegeAdmins->links() }}
    </div>
</div>
@endsection

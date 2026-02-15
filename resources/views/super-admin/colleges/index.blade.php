@extends('super-admin.layouts.app')

@section('title', 'Colleges')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </span>
        Colleges
    </h1>
    <a href="{{ route('super-admin.colleges.create') }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        Create College with Admin
    </a>
</div>

<div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
    <div class="px-5 py-4 border-b border-border bg-primary/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">All Colleges</h2>
        <span class="text-sm text-slate-600">{{ $colleges->total() }} {{ Str::plural('college', $colleges->total()) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px]">
            <thead>
                <tr class="bg-slate-100 border-b border-border">
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">ID</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Name</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Code</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Contact Email</th>
                    <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colleges as $college)
                    <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                        <td class="px-5 py-3 text-sm text-slate-700">{{ $college->id }}</td>
                        <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $college->name }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-200 text-slate-700">{{ $college->code }}</span>
                        </td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $college->contact_email }}</td>
                        <td class="px-5 py-3">
                            @if($college->status === 'active')
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-success-light text-success">Active</span>
                            @else
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('super-admin.colleges.show', $college) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-info hover:bg-info-light transition-colors"
                                   title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('super-admin.colleges.edit', $college) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form action="{{ route('super-admin.colleges.toggle-status', $college) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $college->status === 'active' ? 'text-slate-500 hover:bg-slate-100' : 'text-success hover:bg-success-light' }} transition-colors"
                                            title="{{ $college->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                        @if($college->status === 'active')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('super-admin.colleges.destroy', $college) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this college?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-500">No colleges found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-border mt-4 flex justify-center">
        {{ $colleges->links() }}
    </div>
</div>
@endsection

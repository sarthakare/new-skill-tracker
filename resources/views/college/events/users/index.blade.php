@extends('college.layouts.app')

@section('title', 'Year/Event users')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-semibold text-slate-800">
        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </span>
        Year/Event users - {{ $event->name }}
    </h1>
    <a href="{{ route('college.events.show', $event) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-button font-medium text-slate-700 bg-white border border-border hover:bg-slate-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to year/event
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Assigned Users</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-100 border-b border-border">
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">User</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Email</th>
                            <th class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Role</th>
                            <th class="text-right text-xs font-semibold text-slate-600 uppercase tracking-wider px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventUsers as $eventUser)
                            <tr class="border-b border-border odd:bg-slate-50/50 hover:bg-primary/5 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-slate-900">{{ $eventUser->user->name }}</td>
                                <td class="px-5 py-3 text-sm text-slate-600">{{ $eventUser->user->email }}</td>
                                <td class="px-5 py-3"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-info-light text-info">{{ $eventUser->role === 'Event Admin' ? 'Year/Event Admin' : $eventUser->role }}</span></td>
                                <td class="px-5 py-3 text-right">
                                    <form action="{{ route('college.events.users.destroy', [$event, $eventUser]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">No users assigned to this year/event.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-border flex justify-center">{{ $eventUsers->links() }}</div>
        </div>
    </div>
    <div>
        <div class="bg-white rounded-card border border-border shadow-card overflow-hidden">
            <div class="px-5 py-4 border-b border-border bg-primary/5">
                <h2 class="text-lg font-semibold text-slate-800">Assign User</h2>
            </div>
            <div class="p-5">
                <form action="{{ route('college.events.users.store', $event) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1">User <span class="text-red-500">*</span></label>
                        <select id="user_id" name="user_id" required class="w-full rounded-input border border-slate-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('user_id') border-red-500 @enderror">
                            <option value="">Select User</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" required class="w-full rounded-input border border-slate-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary @error('role') border-red-500 @enderror">
                            <option value="">Select Role</option>
                            <option value="Event Admin" {{ old('role') === 'Event Admin' ? 'selected' : '' }}>Year/Event Admin</option>
                            <option value="Trainer" {{ old('role') === 'Trainer' ? 'selected' : '' }}>Trainer</option>
                            <option value="Judge" {{ old('role') === 'Judge' ? 'selected' : '' }}>Judge</option>
                            <option value="Coordinator" {{ old('role') === 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                            <option value="Participant" {{ old('role') === 'Participant' ? 'selected' : '' }}>Participant</option>
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-button font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">Assign User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

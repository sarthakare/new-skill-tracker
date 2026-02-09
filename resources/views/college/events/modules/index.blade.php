@extends('college.layouts.app')

@section('title', 'Event Modules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-gear"></i> Event Modules - {{ $event->name }}</h1>
    <a href="{{ route('college.events.show', $event) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Event
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Manage Modules</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Enable or disable modules for this event. Disabled modules will not be accessible.</p>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td>
                                <strong>{{ $module->module_name }}</strong>
                            </td>
                            <td>
                                @if($module->is_enabled)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('college.events.modules.toggle', $event) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    <input type="hidden" name="module_name" value="{{ $module->module_name }}">
                                    <button type="submit" 
                                            class="btn btn-sm btn-{{ $module->is_enabled ? 'secondary' : 'success' }}">
                                        <i class="bi bi-{{ $module->is_enabled ? 'x-circle' : 'check-circle' }}"></i>
                                        {{ $module->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

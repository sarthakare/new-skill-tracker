@extends('manager.layouts.app')

@section('title', 'Program Completion')

@section('content')
    <h2 class="mb-4">Completion Request - {{ $program->name }}</h2>

    @if($existing)
        <div class="alert alert-info">
            Latest request status: <strong>{{ $existing->status }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manager.program.completion.store', $program) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Attachments (Attendance, Reports, Certificates)</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Submit Completion Request</button>
            </form>
        </div>
    </div>
@endsection

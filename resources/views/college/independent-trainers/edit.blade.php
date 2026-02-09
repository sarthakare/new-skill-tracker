@extends('college.layouts.app')

@section('title', 'Edit Independent Trainer')

@section('content')
    <h2 class="mb-4">Edit Independent Trainer</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('college.independent-trainers.update', $independentTrainer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $independentTrainer->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expertise</label>
                    <input type="text" name="expertise" class="form-control" value="{{ old('expertise', $independentTrainer->expertise) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $independentTrainer->email) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $independentTrainer->phone) }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('college.independent-trainers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

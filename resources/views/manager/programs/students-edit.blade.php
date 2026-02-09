@extends('manager.layouts.app')

@section('title', 'Edit Student')

@section('content')
    <h2 class="mb-4">Edit Student - {{ $program->name }}</h2>

    <div class="card mb-4">
        <div class="card-header">Student Details</div>
        <div class="card-body">
            <form action="{{ route('manager.program.students.update', [$program, $student]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Name</label>
                        <input type="text" name="student_name" class="form-control" value="{{ old('student_name', $student->student_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Student ID (Optional)</label>
                        <input type="text" name="student_identifier" class="form-control" value="{{ old('student_identifier', $student->student_identifier) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $student->department) }}" required>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('manager.program.students.index', $program) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection


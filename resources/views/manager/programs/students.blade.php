@extends('manager.layouts.app')

@section('title', 'Manage Students')

@section('content')
    <h2 class="mb-4">Students - {{ $program->name }}</h2>

    <div class="card mb-4">
        <div class="card-header">Add Student</div>
        <div class="card-body">
            <form action="{{ route('manager.program.students.store', $program) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Name</label>
                        <input type="text" name="student_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Student ID (Optional)</label>
                        <input type="text" name="student_identifier" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Student List</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Student ID</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->student_identifier ?? '—' }}</td>
                                <td>{{ $student->department }}</td>
                                <td>{{ $student->status }}</td>
                                <td class="text-end">
                                <a href="{{ route('manager.program.students.edit', [$program, $student]) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    Edit
                                </a>
                                    <form action="{{ route('manager.program.students.destroy', [$program, $student]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this student?')">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No students added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

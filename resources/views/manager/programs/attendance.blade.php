@extends('manager.layouts.app')

@section('title', 'Attendance')

@section('content')
    <h2 class="mb-4">Attendance - {{ $session->title }}</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manager.program.attendance.store', [$program, $session]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Attendance Method</label>
                    <select name="method" class="form-select" required>
                        <option value="Manual">Manual</option>
                        <option value="QR">QR</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Present</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $record = $attendance[$student->id] ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="attendance[]" value="{{ $student->id }}"
                                            {{ $record && $record->status === 'present' ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $student->student_name }}</td>
                                    <td>{{ $student->student_identifier ?? '—' }}</td>
                                    <td>{{ $student->department }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                    <a href="{{ route('manager.program.sessions.index', $program) }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('manager.layouts.app')

@section('title', 'Syllabus')

@section('content')
    <h2 class="mb-4">Syllabus - {{ $program->name }}</h2>
    <p class="text-muted mb-4">Add syllabus topics and subtopics. Mark each as complete as you teach it.</p>

    <div class="card mb-4">
        <div class="card-header">Add Topic</div>
        <div class="card-body">
            <form action="{{ route('manager.program.syllabus.topics.store', $program) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label">Topic Name</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Introduction to Python" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Add Topic</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse($topics as $topic)
        <div class="card mb-3" id="topic-{{ $topic->id }}">
            <div class="card-header py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('manager.program.syllabus.topics.toggle-complete', [$program, $topic]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 text-decoration-none" title="{{ $topic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                                @if($topic->is_complete)
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @else
                                    <i class="bi bi-circle text-muted fs-5"></i>
                                @endif
                            </button>
                        </form>
                        <span class="topic-title-display {{ $topic->is_complete ? 'text-decoration-line-through text-muted' : '' }}">{{ $topic->title }}</span>
                        <form action="{{ route('manager.program.syllabus.topics.update', [$program, $topic]) }}" method="POST" class="topic-edit-form d-none" style="max-width: 300px;">
                            @csrf
                            @method('PUT')
                            <div class="input-group input-group-sm">
                                <input type="text" name="title" class="form-control" value="{{ $topic->title }}" required>
                                <button type="submit" class="btn btn-outline-primary">Save</button>
                                <button type="button" class="btn btn-outline-secondary topic-edit-cancel">Cancel</button>
                            </div>
                        </form>
                        @if($topic->scheduled_date || $topic->scheduled_time)
                            <span class="badge bg-secondary ms-2">
                                <i class="bi bi-calendar3"></i>
                                {{ trim(($topic->scheduled_date?->format('M d, Y') ?? '') . ' ' . ($topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '')) }}
                            </span>
                        @endif
                        <div class="ms-2 topic-actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary topic-edit-btn" title="Edit topic"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('manager.program.syllabus.topics.destroy', [$program, $topic]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this topic and all its subtopics?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete topic"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('manager.program.syllabus.subtopics.store', [$program, $topic]) }}" method="POST" class="d-flex gap-2 align-items-center">
                        @csrf
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Add subtopic..." style="min-width: 320px;" required>
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="min-width: 130px;">Add Subtopic</button>
                    </form>
                </div>
                <form action="{{ route('manager.program.syllabus.topics.schedule', [$program, $topic]) }}" method="POST" class="d-flex gap-2 align-items-center">
                    @csrf
                    <label class="text-muted small mb-0">Scheduled:</label>
                    <input type="date" name="scheduled_date" class="form-control form-control-sm" style="width: 150px;" value="{{ $topic->scheduled_date?->format('Y-m-d') }}">
                    <input type="time" name="scheduled_time" class="form-control form-control-sm" style="width: 100px;" value="{{ $topic->scheduled_time ? substr($topic->scheduled_time, 0, 5) : '' }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Set</button>
                </form>
            </div>
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush">
                    @forelse($topic->subtopics as $subtopic)
                        <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-0 border-0">
                            <div class="d-flex align-items-center gap-2 ms-4">
                                <form action="{{ route('manager.program.syllabus.subtopics.toggle-complete', [$program, $subtopic]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none" title="{{ $subtopic->is_complete ? 'Mark incomplete' : 'Mark complete' }}">
                                        @if($subtopic->is_complete)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <i class="bi bi-circle text-muted"></i>
                                        @endif
                                    </button>
                                </form>
                                <span class="subtopic-title-display {{ $subtopic->is_complete ? 'text-decoration-line-through text-muted' : '' }}">{{ $subtopic->title }}</span>
                                <form action="{{ route('manager.program.syllabus.subtopics.update', [$program, $subtopic]) }}" method="POST" class="subtopic-edit-form d-none">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="title" class="form-control form-control-sm" value="{{ $subtopic->title }}" style="min-width: 200px;" required>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm subtopic-edit-cancel">Cancel</button>
                                    </div>
                                </form>
                                <div class="subtopic-actions">
                                    <button type="button" class="btn btn-link p-0 text-muted subtopic-edit-btn" title="Edit"><i class="bi bi-pencil small"></i></button>
                                    <form action="{{ route('manager.program.syllabus.subtopics.destroy', [$program, $subtopic]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subtopic?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="bi bi-trash small"></i></button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted ms-4 py-2 border-0"><small>No subtopics yet. Add one above.</small></li>
                    @endforelse
                </ul>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-journal-bookmark display-4"></i>
                <p class="mt-2 mb-0">No topics yet. Add your first topic above.</p>
            </div>
        </div>
    @endforelse

    @push('scripts')
    <script>
        document.querySelectorAll('.topic-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.card');
                card.querySelector('.topic-title-display').classList.add('d-none');
                card.querySelector('.topic-actions').classList.add('d-none');
                card.querySelector('.topic-edit-form').classList.remove('d-none');
                card.querySelector('.topic-edit-form input').focus();
            });
        });
        document.querySelectorAll('.topic-edit-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.card');
                card.querySelector('.topic-title-display').classList.remove('d-none');
                card.querySelector('.topic-actions').classList.remove('d-none');
                card.querySelector('.topic-edit-form').classList.add('d-none');
            });
        });
        document.querySelectorAll('.subtopic-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const li = this.closest('li');
                li.querySelector('.subtopic-title-display').classList.add('d-none');
                li.querySelector('.subtopic-actions').classList.add('d-none');
                li.querySelector('.subtopic-edit-form').classList.remove('d-none');
                li.querySelector('.subtopic-edit-form input').focus();
            });
        });
        document.querySelectorAll('.subtopic-edit-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                const li = this.closest('li');
                li.querySelector('.subtopic-title-display').classList.remove('d-none');
                li.querySelector('.subtopic-actions').classList.remove('d-none');
                li.querySelector('.subtopic-edit-form').classList.add('d-none');
            });
        });
    </script>
    @endpush
@endsection

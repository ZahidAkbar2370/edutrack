@extends('adminlayout.layout')

@section('title', 'Teacher Detail')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Teacher Detail</h1>
        <p class="text-muted mb-0">View teacher information</p>
    </div>
    <a href="{{ url('teacher') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(!$teacher)
    <div class="alert alert-danger">Teacher not found</div>
@else
<div class="card shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3 text-muted">Teacher Name</dt>
            <dd class="col-sm-9 fw-medium">{{ $teacher->teacher_name }}</dd>

            <dt class="col-sm-3 text-muted">Email</dt>
            <dd class="col-sm-9">{{ $teacher->teacher_email ?? '—' }}</dd>

            <dt class="col-sm-3 text-muted">Phone</dt>
            <dd class="col-sm-9">{{ $teacher->teacher_phone_no ?? '—' }}</dd>

            <dt class="col-sm-3 text-muted">Address</dt>
            <dd class="col-sm-9">{{ $teacher->teacher_address ?? '—' }}</dd>

            <dt class="col-sm-3 text-muted">School</dt>
            <dd class="col-sm-9">{{ $teacher->school->school_name ?? 'N/A' }}</dd>

            <dt class="col-sm-3 text-muted">Registered At</dt>
            <dd class="col-sm-9">{{ $teacher->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</dd>
        </dl>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <a href="{{ url('teacher/edit/' . $teacher->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <form action="{{ url('teacher/delete/' . $teacher->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this teacher?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </form>
    </div>
</div>
@endif

@endsection

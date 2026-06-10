@extends('adminlayout.layout')

@section('title', 'Subject Detail')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Subject Detail</h1>
        <p class="text-muted mb-0">View subject information</p>
    </div>
    <a href="{{ url('subject') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(!$subject)
    <div class="alert alert-danger">Subject not found</div>
@else
<div class="card shadow-sm">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3 text-muted">Subject Name</dt>
            <dd class="col-sm-9 fw-medium">{{ $subject->subject_name }}</dd>

            <dt class="col-sm-3 text-muted">School</dt>
            <dd class="col-sm-9">{{ $subject->school->school_name ?? 'N/A' }}</dd>

            <dt class="col-sm-3 text-muted">Created At</dt>
            <dd class="col-sm-9">{{ $subject->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</dd>
        </dl>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <a href="{{ url('subject/edit/' . $subject->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <form action="{{ url('subject/delete/' . $subject->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this subject?');">
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

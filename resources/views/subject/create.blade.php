@extends('adminlayout.layout')

@section('title', 'Add Subject')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Add Subject</h1>
        <p class="text-muted mb-0">Create a new subject</p>
    </div>
    <a href="{{ url('subject') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('subject/store') }}" method="POST">
    @csrf

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Subject Information</h2>
        </div>
        <div class="card-body">
            <div class="mb-0">
                <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                <input type="text" name="subject_name" id="subject_name" value="{{ old('subject_name') }}" class="form-control" placeholder="e.g. Mathematics, English, Science" required>
                @error('subject_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this subject?')">
            <i class="bi bi-check-lg me-1"></i> Save Subject
        </button>
    </div>
</form>

@endsection

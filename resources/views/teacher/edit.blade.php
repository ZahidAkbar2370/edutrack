@extends('adminlayout.layout')

@section('title', 'Edit Teacher')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit Teacher</h1>
        <p class="text-muted mb-0">Update teacher information</p>
    </div>
    <a href="{{ url('teacher') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(!$teacher)
    <div class="alert alert-danger">Teacher not found</div>
@else
<form action="{{ url('teacher/update/' . $teacher->id) }}" method="POST">
    @csrf

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Teacher Information</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="teacher_name" class="form-label">Teacher Name <span class="text-danger">*</span></label>
                    <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $teacher->teacher_name) }}" class="form-control" required>
                    @error('teacher_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="teacher_email" class="form-label">Email</label>
                    <input type="email" name="teacher_email" id="teacher_email" value="{{ old('teacher_email', $teacher->teacher_email) }}" class="form-control">
                    @error('teacher_email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="teacher_phone_no" class="form-label">Phone</label>
                    <input type="text" name="teacher_phone_no" id="teacher_phone_no" value="{{ old('teacher_phone_no', $teacher->teacher_phone_no) }}" class="form-control">
                    @error('teacher_phone_no')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="teacher_address" class="form-label">Address</label>
                    <input type="text" name="teacher_address" id="teacher_address" value="{{ old('teacher_address', $teacher->teacher_address) }}" class="form-control">
                    @error('teacher_address')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this teacher?')">
            <i class="bi bi-check-lg me-1"></i> Update Teacher
        </button>
    </div>
</form>
@endif

@endsection

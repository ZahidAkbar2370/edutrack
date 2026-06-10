@extends('adminlayout.layout')

@section('title', 'Create Class')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Create Class</h1>
        <p class="text-muted mb-0">Add a new class</p>
    </div>
    <a href="{{ url('class') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('class/store') }}" method="POST">
    @csrf

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Class Information</h2>
        </div>
        <div class="card-body">
            <div class="mb-0">
                <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
                <input type="text" name="class_name" id="class_name" value="{{ old('class_name') }}" class="form-control" placeholder="e.g. 1, 2, Nursery" required>
                @error('class_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this class?')">
            <i class="bi bi-check-lg me-1"></i> Save Class
        </button>
    </div>
</form>

@endsection

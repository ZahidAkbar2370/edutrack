@extends('adminlayout.layout')

@section('title', 'Edit Section')

@section('content')

@include('adminlayout.setting_menu')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit Section</h1>
        <p class="text-muted mb-0">Update class and section name</p>
    </div>
    <a href="{{ url('section') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(!$section)
    <div class="alert alert-danger">Section not found</div>
@else
<form action="{{ url('section/update/' . $section->id) }}" method="POST">
    @csrf

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Section Information</h2>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                <select name="class_id" id="class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $section->class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-0">
                <label for="section_name" class="form-label">Section Name <span class="text-danger">*</span></label>
                <input type="text" name="section_name" id="section_name" value="{{ old('section_name', $section->section_name) }}" class="form-control" required>
                @error('section_name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this section?')">
            <i class="bi bi-check-lg me-1"></i> Update Section
        </button>
    </div>
</form>
@endif

@endsection

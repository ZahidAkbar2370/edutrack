@extends('adminlayout.layout')

@section('title', 'Import Students')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Import Students</h1>
        <p class="text-muted mb-0">Upload a CSV file to add multiple students with parent details</p>
    </div>
    <a href="{{ url('student') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('import_errors'))
    <div class="alert alert-warning">
        <strong>Import issues:</strong>
        <ul class="mb-0 mt-2 small">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Upload CSV</h2>
            </div>
            <div class="card-body">
                <form action="{{ url('student/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File <span class="text-danger">*</span></label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv,text/csv" required>
                        @error('csv_file')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Import students from this CSV file?')">
                        <i class="bi bi-upload me-1"></i> Import Students
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Instructions</h2>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Use the same column names as the sample file. Class and section must already exist in your school.</p>
                <a href="{{ url('student/import-template') }}" class="btn btn-outline-primary btn-sm w-100 mb-3">
                    <i class="bi bi-download me-1"></i> Download Sample CSV
                </a>
                <p class="small fw-semibold mb-1">Required columns:</p>
                <ul class="small text-muted mb-0">
                    <li>student_name</li>
                    <li>class_name</li>
                    <li>section_name</li>
                    <li>parent_name</li>
                    <li>parent_phone_no</li>
                </ul>
                <p class="small text-muted mt-3 mb-0">Optional: roll number, emails, phones, admission date (YYYY-MM-DD), parent address, status (active, completed, banned, inactive — defaults to active).</p>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('schooladmin.layout.layout')

@section('title', 'Add Daily Test')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Add Daily Test</h1>
        <p class="text-muted mb-0">Select class and section, enter test details and marks</p>
    </div>
    <a href="{{ url('daily-test') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('daily-test/store') }}" method="POST" id="daily-test-form">
    @csrf

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Class &amp; Section</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                    <select name="section_id" id="section_id" class="form-select" required>
                        <option value="">Select Section</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="daily_test_date" class="form-label">Test Date <span class="text-danger">*</span></label>
                    <input type="date" name="daily_test_date" id="daily_test_date" value="{{ old('daily_test_date', date('Y-m-d')) }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Test Information</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="daily_test_name" class="form-label">Test Name <span class="text-danger">*</span></label>
                    <input type="text" name="daily_test_name" id="daily_test_name" value="{{ old('daily_test_name') }}" class="form-control" placeholder="e.g. Unit Test 1" required>
                    @error('daily_test_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <select name="subject" id="subject" class="form-select" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->subject_name }}" {{ old('subject') == $subject->subject_name ? 'selected' : '' }}>
                                {{ $subject->subject_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @if($subjects->isEmpty())
                        <div class="form-text text-warning">No subjects found. Add subjects from Settings first.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="teacher_name" class="form-label">Teacher <span class="text-danger">*</span></label>
                    <select name="teacher_name" id="teacher_name" class="form-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->teacher_name }}" {{ old('teacher_name') == $teacher->teacher_name ? 'selected' : '' }}>
                                {{ $teacher->teacher_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @if($teachers->isEmpty())
                        <div class="form-text text-warning">No teachers found. Register teachers first.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="daily_test_total" class="form-label">Total Marks <span class="text-danger">*</span></label>
                    <input type="number" name="daily_test_total" id="daily_test_total" value="{{ old('daily_test_total', 100) }}" class="form-control" min="1" required>
                    @error('daily_test_total')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-warning">
    <strong>Attention:</strong> The system will display only those students whose admission date is on or before the selected test date.
</div>

    <div class="card shadow-sm d-none" id="students-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students — Obtained Marks</h2>
            <span class="small text-muted" id="students-count"></span>
        </div>
        <div class="card-body p-0">
            <div id="students-loading" class="text-center text-muted py-4 d-none">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Loading students...
            </div>
            <div id="students-empty" class="text-center text-muted py-4 d-none">No students found for this class and section.</div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 d-none" id="students-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Roll #</th>
                            <th class="text-center" style="min-width: 140px;">Obtained Marks</th>
                            <th class="text-center" style="min-width: 100px;">Percentage</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="submit-wrap">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this daily test?')">
            <i class="bi bi-check-lg me-1"></i> Save Daily Test
        </button>
    </div>
</form>


@include('schooladmin.dailytest.javascript')

@endsection

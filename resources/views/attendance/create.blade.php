@extends('adminlayout.layout')

@section('title', 'Mark Attendance')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Mark Attendance</h1>
        <p class="text-muted mb-0">Select class and section, then mark each student</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('attendance/store') }}" method="POST" id="attendance-form">
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
                        <option value="">Select Class From List</option>
                        @if(!empty($classes))
                            @foreach($classes as $key => $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                    <select name="section_id" id="section_id" class="form-select" required>
                        <option value="">Select Section From List</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="attendance_date" class="form-label">Attendance Date <span class="text-danger">*</span></label>
                    <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date', date('Y-m-d')) }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm d-none" id="students-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students</h2>
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
                            <th class="text-center">Present / Absent</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="attendance-submit-wrap">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save attendance?')">
            <i class="bi bi-check-lg me-1"></i> Save Attendance
        </button>
    </div>
</form>


@include('attendance.javascript')

@endsection

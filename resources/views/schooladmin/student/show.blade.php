@extends('schooladmin.layout.layout')

@section('title', 'Student Detail')

@section('content')

<style>
    .btnPurple {
        border-color: #6f42c1 !important;   
        color: #6f42c1;
    }
    .btnPurple:hover {
        background-color: #6f42c1 !important;
        color: white;
    }
    .btnPink {
        border-color: #e83e8c !important;
        color: #e83e8c;
    }
    .btnPink:hover {
        background-color: #e83e8c !important;
        color: white;
    }   
</style>


<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Student Detail</h1>
        <p class="text-muted mb-0">Student and parent information</p>
    </div>

    @if($student)
    <div class="d-flex flex-wrap justify-content-end gap-2">

        <a href="{{ url('student/' . $student->id . '/fee-history') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-cash-coin me-1"></i> Fee History
        </a>

        <a href="{{ url('student/' . $student->id . '/attendance-history') }}" class="btn btn-outline-secondary btn-sm btnPurple">
            <i class="bi bi-attendance-fill me-1"></i> Attendance History
        </a>

        <a href="{{ url('student/' . $student->id . '/daily-test-history') }}" class="btn btn-outline-warning btn-sm btnPink">
            <i class="bi bi-table me-1"></i> Test History
        </a>

        <a href="{{ url('student/card/' . $student->id) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-person-badge me-1"></i> ID Card
        </a>
        <a href="{{ url('student/documents/' . $student->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-person-badge me-1"></i> Documents
        </a>
        <a href="{{ url('student/edit/' . $student->id) }}" class="btn btn-outline-info btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <form action="{{ url('student/delete/' . $student->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-outline-danger btn-sm"
                    data-confirm-action
                    data-confirm-title="Delete Student"
                    data-confirm-message="Are you sure you want to delete this student?"
                    data-confirm-yes="Yes, Delete"
                    data-confirm-yes-class="btn-danger">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </form>

        <a href="{{ url('student') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}</div>
@endif
@if(session('warning'))
<i class="bi bi-exclamation-triangle-fill"></i>
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

@if(!$student)
<div class="alert alert-danger">Student not found</div>
@else
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Personal Information</h2>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                <a href="{{ asset($student->student_photo) }}" target="_blank"><img src="{{ asset($student->student_photo) }}" alt="{{ $student->student_name }} Profile Image" class="rounded border" style="width:140px;height:140px;object-fit:cover;"></a>    
                
                </div>
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Roll Number</dt>
                    <dd class="col-sm-8">{{ $student->student_roll_number ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Name</dt>
                    <dd class="col-sm-8 fw-medium">{{ $student->student_name }}</dd>
                    <dt class="col-sm-4 text-muted">Email</dt>
                    <dd class="col-sm-8">{{ $student->student_email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Phone</dt>
                    <dd class="col-sm-8">{{ $student->student_phone_no ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Date of Birth</dt>
                    <dd class="col-sm-8">{{ $student->student_date_of_birth ? \Illuminate\Support\Carbon::parse($student->student_date_of_birth)->format('d M Y') : '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Gender</dt>
                    <dd class="col-sm-8">{{ ucfirst($student->student_gender) ? ucfirst($student->student_gender) : '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : ($student->status == 'completed' ? 'warning' : ($student->status == 'banned' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Parent Information</h2>
            </div>
            <div class="card-body">
                @if($student->parent)
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Name</dt>
                    <dd class="col-sm-8 fw-medium">{{ $student->parent->parent_name }}</dd>
                    <dt class="col-sm-4 text-muted">Phone</dt>
                    <dd class="col-sm-8">{{ $student->parent->parent_phone_no }}</dd>
                    <dt class="col-sm-4 text-muted">Email</dt>
                    <dd class="col-sm-8">{{ $student->parent->parent_email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Address</dt>
                    <dd class="col-sm-8">{{ $student->parent->parent_address ?? '—' }}</dd>
                </dl>
                @else
                <p class="text-muted mb-0">No parent record found</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Class & Section</h2>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Class</dt>
                    <dd class="col-sm-8">{{ $student->schoolClass->class_name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4 text-muted">Section</dt>
                    <dd class="col-sm-8">{{ $student->section->section_name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4 text-muted">Admission</dt>
                    <dd class="col-sm-8">
                        {{ $student->student_admission_date ? \Illuminate\Support\Carbon::parse($student->student_admission_date)->format('d M Y') : '—' }}
                    </dd>
                    <dt class="col-sm-4 text-muted">Monthly Fee</dt>
                    <dd class="col-sm-8">{{ $student->student_per_month_fee ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

{{-- Attendance summary --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h6 mb-0 fw-semibold">Attendance Summary</h2>
        <a href="{{ url('student/' . $student->id . '/attendance-history') }}" class="text-decoration-none small">
            View history <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="border rounded p-3 text-center h-100">
                    <div class="text-muted small">Total Records</div>
                    <div class="fs-4 fw-bold">{{ $attendanceStats['total'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="border rounded p-3 text-center h-100 border-success">
                    <div class="text-muted small">Present</div>
                    <div class="fs-4 fw-bold text-success">{{ $attendanceStats['present'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="border rounded p-3 text-center h-100 border-danger">
                    <div class="text-muted small">Absent</div>
                    <div class="fs-4 fw-bold text-danger">{{ $attendanceStats['absent'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="border rounded p-3 text-center h-100 border-warning">
                    <div class="text-muted small">Leave</div>
                    <div class="fs-4 fw-bold text-warning">{{ $attendanceStats['leave'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Daily test summary --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h6 mb-0 fw-semibold">Daily Test Summary</h2>
        <a href="{{ url('student/' . $student->id . '/daily-test-history') }}" class="text-decoration-none small">
            View history <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="border rounded p-3 text-center h-100">
                    <div class="text-muted small">Total Tests</div>
                    <div class="fs-4 fw-bold">{{ $dailyTestStats['total'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center h-100 border-success">
                    <div class="text-muted small">Attempted (marks &gt; 0)</div>
                    <div class="fs-4 fw-bold text-success">{{ $dailyTestStats['attempted'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center h-100 border-secondary">
                    <div class="text-muted small">Zero Marks</div>
                    <div class="fs-4 fw-bold text-secondary">{{ $dailyTestStats['not_attempted'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

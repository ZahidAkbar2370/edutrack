@extends('schooladmin.layout.layout')

@section('title', 'Edit Attendance')

@section('content')

@php

    $className = $attendances->first()->schoolClass->class_name;
    $sectionName = $attendances->first()->section->section_name;
    $attendanceDate = $attendances->first()->attendance_date;
    $attendanceCode = $attendances->first()->attendance_code;

@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit Attendance</h1>
        <p class="text-muted mb-0">
            <code><span class="fw-medium">{{ $className ?? 'N/A' }}</span> - ({{ $sectionName ?? 'N/A' }})
            · {{ $attendanceDate ? \Illuminate\Support\Carbon::parse($attendanceDate)->format('d M Y') : '' }}</code>
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('attendance/show/' . $attendanceCode) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Attendance Detail
            </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ URL::to('attendance/update/' . $attendanceCode) }}" method="POST">
    @csrf


    <div class="alert alert-warning">
    <strong>Attention:</strong> Attendance records are generated based on the selected Class, Section, and Attendance Date. The system displays only those students whose admission date is on or before the selected attendance date.
</div>

    <div class="card shadow-sm" id="students-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold"> <i class="bi bi-people me-1"></i> Students List</h2>
            <span class="small text-muted" id="students-count"></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="students-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Roll #</th>
                            <th class="text-center">Present / Absent</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        @if(!empty($attendances) && $attendances->count() > 0)
                        @foreach($attendances as $key => $attendance)

                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $attendance->student->student_name }}</td>
                            <td>{{ $attendance->student->student_roll_number ?? '' }}</td>
                            <td class="text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[{{ $attendance->student->id }}]"
                                           value="present"
                                           {{ $attendance->attendance_status == 'present' ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Present
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[{{ $attendance->student->id }}]"
                                           value="absent"
                                           {{ $attendance->attendance_status == 'absent' ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Absent
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[{{ $attendance->student->id }}]"
                                           value="leave"
                                           {{ $attendance->attendance_status == 'leave' ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                    Leave
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <div id="students-empty" class="text-center text-muted py-4">No students found.</div>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3" id="attendance-submit-wrap">
        <button type="submit" class="btn btn-primary" 
        data-confirm-action
                    data-confirm-title="Update Attendance"
                    data-confirm-message="Are you sure you want to update attendance?"
                    data-confirm-yes="Yes, Update"
                    data-confirm-yes-class="btn-primary"
        >
            <i class="bi bi-check-lg me-1"></i> Update Attendance
        </button>
    </div>
</form>

@endsection

@extends('schooladmin.layout.layout')

@section('title', 'Edit Attendance')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit Attendance</h1>
        <p class="text-muted mb-0">
            <code><span class="fw-medium">{{ $schoolClass->class_name ?? 'N/A' }}</span> - ({{ $section->section_name ?? 'N/A' }})
            · {{ $attendanceDate ? \Illuminate\Support\Carbon::parse($attendanceDate)->format('d M Y') : '' }}</code>
        </p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('attendance/update') }}" method="POST" id="attendance-form">
    @csrf

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

    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
    <input type="hidden" name="class_id" value="{{ $attendance->class_id }}">
    <input type="hidden" name="section_id" value="{{ $attendance->section_id }}">
    <input type="hidden" name="attendance_date" value="{{ $attendance->attendance_date }}">


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

@extends('schooladmin.layout.layout')

@section('title', 'Attendance Detail')

@section('content')

@php
    $classId = request('class_id');
    $sectionId = request('section_id');
    $attendanceDate = request('attendance_date');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Attendance Detail</h1>
        <p class="text-muted mb-0">
            <code><span class="fw-medium">{{ $schoolClass->class_name ?? 'N/A' }}</span> - ({{ $section->section_name ?? 'N/A' }})
            · {{ $attendanceDate ? \Illuminate\Support\Carbon::parse($attendanceDate)->format('d M Y') : '' }}</code>
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">

        <a href="{{ url('attendance/export-to-csv/' . $classId . '/' . $sectionId . '/' . $attendanceDate) }}" class="btn btn-outline-success" data-confirm-action
                    data-confirm-title="Export Attendance to Excel"
                    data-confirm-message="Are you sure you want to export to CSV?"
                    data-confirm-yes="Yes, Export"
                    data-confirm-yes-class="btn-success"
        >
            <i class="bi bi-download me-1"></i> Export To CSV
        </a>

        <a href="{{ url('attendance/edit/' . $classId . '/' . $sectionId . '/' . $attendanceDate) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Attendance
        </a>

        <a href="#" class="btn btn-outline-danger" data-confirm-action
                    data-confirm-title="Report via Whatsapp"
                    data-confirm-message="We are working on it... this feature will be available soon..."
                    data-confirm-yes="Yes, Report"
                    data-confirm-yes-class="btn-danger"
        >
            <i class="bi bi-whatsapp me-1"></i> Report via Whatsapp
        </a>

    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($attendances->isEmpty())
        <div class="alert alert-warning">No attendance records found.</div>
    @else


<form action="{{ url('attendance/update') }}" method="POST">
    @csrf
    <input type="hidden" name="class_id" value="{{ $classId }}">
    <input type="hidden" name="section_id" value="{{ $sectionId }}">
    <input type="hidden" name="attendance_date" value="{{ $attendanceDate }}">

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students</h2>
            <span class="small text-muted">{{ $attendances->count() }} Student(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Attendance Status</th>
                            <th>Whatsapp Alert Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $key => $attendance)

                            @php
                                $status = $attendance->attendance_status;
                                $rowClass = match($status) {
                                    'present' => 'success',
                                    'absent' => 'danger',
                                    'leave' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp

                            <tr class="attendance-row" data-student-id="{{ $attendance->student_id }}">
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-medium">
                                    <a href="{{ url('student/show/' . $attendance->student_id) }}" target="_blank" class="fw-medium">{{ $attendance->student->student_name ?? 'N/A' }}</a> <br>
                                    <span class="text-muted small">(Roll No: {{ $attendance->student->student_roll_number ?? '—' }})</span>
                                </td>
                                <td class="status-text text-capitalize fw-semibold"> <span class="p-2 bg-{{ $rowClass }} text-white">{{ $status }}</span> </td>
                                <td>
                                    <span class="p-2 bg-warning text-white">Pending</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
@endif

@endsection

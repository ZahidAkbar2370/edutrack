@extends('adminlayout.layout')

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
            <span class="fw-medium">{{ $schoolClass->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $section->section_name ?? 'N/A' }})</span>
            · {{ $attendanceDate ? \Illuminate\Support\Carbon::parse($attendanceDate)->format('d M Y') : '' }}
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="#" onclick="return confirm('we are working on it... this feature will be available soon...')" class="btn btn-success">
            <i class="bi bi-whatsapp me-1"></i> Send Attendance Report to Parents via Whatsapp
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
            <span class="small text-muted">{{ $attendances->count() }} student(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th class="text-center" style="min-width: 220px;">Mark Attendance</th>
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
                                <td class="status-text text-capitalize fw-semibold"> <span class="p-2 rounded-pill bg-{{ $rowClass }}">{{ $status }}</span> </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm attendance-status-group" role="group">
                                        <input type="radio" class="btn-check status-radio" name="students[{{ $attendance->student_id }}]"
                                               id="present-{{ $attendance->student_id }}" value="present"
                                               {{ $status === 'present' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="present-{{ $attendance->student_id }}">Present</label>

                                        <input type="radio" class="btn-check status-radio" name="students[{{ $attendance->student_id }}]"
                                               id="absent-{{ $attendance->student_id }}" value="absent"
                                               {{ $status === 'absent' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="absent-{{ $attendance->student_id }}">Absent</label>

                                        <input type="radio" class="btn-check status-radio" name="students[{{ $attendance->student_id }}]"
                                               id="leave-{{ $attendance->student_id }}" value="leave"
                                               {{ $status === 'leave' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="leave-{{ $attendance->student_id }}">Leave</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update attendance?')">
            <i class="bi bi-check-lg me-1"></i> Update Attendance
        </button>
    </div>
</form>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusMap = {
            present: { row: 'table-success', text: 'present' },
            absent: { row: 'table-danger', text: 'absent' },
            leave: { row: 'table-warning', text: 'leave' }
        };

        document.querySelectorAll('.attendance-row').forEach(function (row) {
            row.querySelectorAll('.status-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (!this.checked) return;
                    var status = this.value;
                    row.classList.remove('table-success', 'table-danger', 'table-warning');
                    if (statusMap[status]) {
                        row.classList.add(statusMap[status].row);
                        row.querySelector('.status-text').textContent = statusMap[status].text;
                    }
                });
            });
        });
    });
</script>
@endpush

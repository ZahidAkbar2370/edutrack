@extends('adminlayout.layout')

@section('title', 'Attendance History — ' . $student->student_name)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Attendance History</h1>
        <p class="text-muted mb-0">
            {{ $student->student_name }}
            · Roll {{ $student->student_roll_number ?? '—' }}
            · {{ $student->schoolClass->class_name ?? 'N/A' }} — {{ $student->section->section_name ?? 'N/A' }}
        </p>
    </div>
    <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Student
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small">Total</div>
                <div class="fs-4 fw-bold">{{ $attendanceStats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center border-success">
            <div class="card-body py-3">
                <div class="text-muted small">Present</div>
                <div class="fs-4 fw-bold text-success">{{ $attendanceStats['present'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center border-danger">
            <div class="card-body py-3">
                <div class="text-muted small">Absent</div>
                <div class="fs-4 fw-bold text-danger">{{ $attendanceStats['absent'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center border-warning">
            <div class="card-body py-3">
                <div class="text-muted small">Leave</div>
                <div class="fs-4 fw-bold text-warning">{{ $attendanceStats['leave'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceHistory as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($record->attendance_date)->format('d M Y') }}</td>
                            <td>
                                @if($record->attendance_status === 'present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($record->attendance_status === 'absent')
                                    <span class="badge bg-danger">Absent</span>
                                @else
                                    <span class="badge bg-warning text-dark">Leave</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $record->attendance_note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No attendance records for this student</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

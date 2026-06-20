@extends('schooladmin.layout.layout')

@section('title', 'Attendance History — ' . $student->student_name)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Attendance History</h1>
        <p class="text-muted mb-0">
        <code><span class="fw-medium">{{ $student->student_name }}</span> -> (Roll # {{ $student->student_roll_number ?? '—' }})
            -> {{ $student->schoolClass->class_name ?? 'N/A' }} -> {{ $student->section->section_name ?? 'N/A' }} </code>
        </p>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">

        <a href="{{ url('student/' . $student->id . '/export-attendance-history-csv') }}" class="btn btn-outline-success btn-sm"
        
        data-confirm-action
                    data-confirm-title="Export Attendance History to Excel"
                    data-confirm-message="Are you sure you want to export to CSV?"
                    data-confirm-yes="Yes, Export"
                    data-confirm-yes-class="btn-success"
        >
            <i class="bi bi-download me-1"></i> Export to CSV
        </a>

        <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Student Detail
    </a>
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
                    @if(!empty($attendanceHistory) && $attendanceHistory->count() > 0)
                        @foreach($attendanceHistory as $key => $record)
                            <tr>
                                <td>{{ $key + 1 }}</td>
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
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No attendance records for this student</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

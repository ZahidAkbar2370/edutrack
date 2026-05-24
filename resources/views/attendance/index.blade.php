@extends('adminlayout.layout')

@section('title', 'Attendance')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Attendance</h1>
        <p class="text-muted mb-0">Attendance records by class and section</p>
    </div>
    <a href="{{ url('attendance/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Mark Attendance
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class & Section</th>
                        <th class="text-center">Total Students</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">Leave</th>
                        <th>Attendance Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceGroups as $key => $group)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $classes[$group->class_id]->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $sections[$group->section_id]->section_name ?? 'N/A' }})</span>
                            </td>
                            <td class="text-center">{{ $group->total_students }}</td>
                            <td class="text-center text-success fw-semibold">{{ $group->present_count }}</td>
                            <td class="text-center text-danger fw-semibold">{{ $group->absent_count }}</td>
                            <td class="text-center text-warning fw-semibold">{{ $group->leave_count }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($group->attendance_date)->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('attendance.show', [
                                    'class_id' => $group->class_id,
                                    'section_id' => $group->section_id,
                                    'attendance_date' => $group->attendance_date,
                                ]) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No attendance records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

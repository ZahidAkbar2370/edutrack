@extends('adminlayout.layout')

@section('title', 'Students')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Students</h1>
        <p class="text-muted mb-0">All registered students</p>
    </div>
    <a href="{{ url('student/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Student
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
                        <th>Student Name</th>
                        <th>Class & Section</th>
                        <th>Parent Name & Phone</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $key => $student)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $student->student_name }}</span> <br>
                                <span class="text-muted small">(Roll No: {{ $student->student_roll_number ?? '—' }})</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $student->schoolClass->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $student->section->section_name ?? 'N/A' }})</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $student->parent->parent_name ?? 'N/A' }}</span> <br>
                                <span class="text-muted small">(Phone: {{ $student->parent->parent_phone_no ?? '—' }})</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('student/edit/' . $student->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

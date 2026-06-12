@extends('adminlayout.layout')

@section('title', 'Trash Students')

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Trash Students</h1>
        <p class="text-muted mb-0">All deleted students</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ url('student') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif


<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
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
                    @if(!empty($students) && $students->count() > 0)
                    @foreach($students as $key => $student)
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
                                <div class="d-flex flex-column align-items-center gap-1">
                               
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('student/restore-trash-student/' . $student->id) }}" class="btn btn-outline-danger" title="Restore" data-confirm-action
                    data-confirm-title="Restore Student"
                    data-confirm-message="Are you sure you want to restore / recover this student?"
                    data-confirm-yes="Yes, Restore"
                    data-confirm-yes-class="btn-success">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="text-center">No data found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

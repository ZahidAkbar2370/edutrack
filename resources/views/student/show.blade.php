@extends('adminlayout.layout')

@section('title', 'Student Detail')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Student Detail</h1>
        <p class="text-muted mb-0">Student and parent information</p>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ url('student/edit/' . $student->id) }}" class="btn btn-info btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <form action="{{ url('student/delete/' . $student->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this student?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </form>
    </div>

</div>

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
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Roll Number</dt>
                    <dd class="col-sm-8">{{ $student->student_roll_number ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted">Name</dt>
                    <dd class="col-sm-8 fw-medium">{{ $student->student_name }}</dd>


                    <dt class="col-sm-4 text-muted">Email</dt>
                    <dd class="col-sm-8">{{ $student->student_email ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted">Phone</dt>
                    <dd class="col-sm-8">{{ $student->student_phone_no ?? '—' }}</dd>

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
                <h2 class="h6 mb-0 fw-semibold">Class & Section Information</h2>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Class</dt>
                    <dd class="col-sm-8">{{ $student->schoolClass->class_name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4 text-muted">Section</dt>
                    <dd class="col-sm-8">{{ $student->section->section_name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4 text-muted">Admission Date</dt>
                    <dd class="col-sm-8">
                        {{ $student->student_admission_date ? \Illuminate\Support\Carbon::parse($student->student_admission_date)->format('d M Y') : '—' }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@endif

@endsection
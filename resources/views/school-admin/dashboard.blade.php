@extends('adminlayout.layout')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Dashboard</h1>
    <p class="text-muted mb-0">Overview of your school</p>
</div>

<h2 class="h6 text-uppercase text-muted fw-semibold mb-3">Students</h2>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-people fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-check fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Active</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['active_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-mortarboard fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Completed</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($stats['completed_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-slash-circle fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Banned</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['banned_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-dash fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Inactive</div>
                    <div class="fs-4 fw-bold text-secondary">{{ number_format($stats['inactive_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<h2 class="h6 text-uppercase text-muted fw-semibold mb-3">School</h2>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-journal-bookmark fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Classes</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_classes']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-clipboard-check fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Daily Tests</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_daily_tests']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-badge fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Teachers</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_teachers']) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h2 class="h6 fw-semibold mb-3">Quick links</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('student') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-people me-1"></i> Students</a>
            <a href="{{ url('student/create') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> Add Student</a>
            <a href="{{ url('attendance') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-calendar-check me-1"></i> Attendance</a>
            <a href="{{ url('daily-test') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-clipboard-check me-1"></i> Daily Test</a>
            <a href="{{ url('class') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-journal-bookmark me-1"></i> Classes</a>
            <a href="{{ url('teacher') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-person-badge me-1"></i> Teachers</a>
        </div>
    </div>
</div>

@endsection

@extends('superadmin.layout.layout')

@section('title', 'Super Admin Dashboard')

@section('content')

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Dashboard</h1>
    <p class="text-muted mb-0">Overview of the system</p>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
        </div>
    @endif


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-dark bg-opacity-10 text-dark d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-people fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Schools</div>
                    <div class="fs-4 fw-bold">{{ number_format($schools) }}</div>
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
                    <div class="text-muted small">Active Schools</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($activeSchools) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-xxl">
        <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-mortarboard fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Expired Schools</div>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($expiredSchools) }}</div>
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
                    <div class="text-muted small">Total Earnings</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($totalEarnings) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

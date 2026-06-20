@extends('superadmin.layout.layout')

@section('title', 'School Detail')

@section('content')

<style>
    .btnPurple {
        border-color: #6f42c1 !important;   
        color: #6f42c1;
    }
    .btnPurple:hover {
        background-color: #6f42c1 !important;
        color: white;
    }
    .btnPink {
        border-color: #e83e8c !important;
        color: #e83e8c;
    }
    .btnPink:hover {
        background-color: #e83e8c !important;
        color: white;
    }   
</style>


@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">School Detail</h1>
        <p class="text-muted mb-0">School and principal information</p>
    </div>
    



 
    <div class="d-flex flex-wrap justify-content-end gap-2">

        <a href="{{ url('school/upgrade-membership/' . $school->id) }}" class="btn btn-outline-secondary btn-sm btnPurple">
            <i class="bi bi-arrow-up-circle me-1"></i> Upgrade Membership
        </a>


        <a href="{{ url('school/transaction-history/' . $school->id) }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-cash-coin me-1"></i>Transaction History
        </a>

        <a href="{{ url('school/change-password/' . $school->id) }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-lock-fill me-1"></i> Change Password
        </a>

        <a href="{{ url('school/edit/' . $school->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>

        <a href="{{ url('school') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

</div>

@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> 
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(!$school)
<div class="alert alert-danger">School not found</div>
@else
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">School Information</h2>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                <!-- <a href="{{ asset($school->school_logo) ?? asset('Admin/images/school/logo/default.png') }}" target="_blank"><img src="{{ asset($school->school_logo) ?? asset('Admin/images/school/logo/default.png') }}" alt="{{ $school->school_name }} Logo" class="rounded border" style="width:140px;height:140px;object-fit:cover;"></a>     -->
                

                <a href="{{ asset('Admin/images/school/logo/default.png') }}" target="_blank"><img src="{{ asset('Admin/images/school/logo/default.png') }}" alt="{{ $school->school_name }} Logo" class="rounded border" style="width:140px;height:140px;object-fit:cover;"></a>

                </div>
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">School Name</dt>
                    <dd class="col-sm-8">{{ $school->school_name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">School Email</dt>
                    <dd class="col-sm-8">{{ $school->school_email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">School Phone</dt>
                    <dd class="col-sm-8">{{ $school->school_phone_no ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">City</dt>
                    <dd class="col-sm-8">{{ $school->city ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Address</dt>
                    <dd class="col-sm-8">{{ $school->address ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Principal Information</h2>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Principle Name</dt>
                    <dd class="col-sm-8">{{ $school->priciple_name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Principle Email</dt>
                    <dd class="col-sm-8">{{ $school->priciple_email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Principle Phone</dt>
                    <dd class="col-sm-8">{{ $school->priciple_phone_no ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Membership Information</h2>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Membership</dt>
                    <dd class="col-sm-8">{{ $school->membership->membership_name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Expiry Date</dt>
                    <dd class="col-sm-8">{{ $school->user->membership_expiry_date ? \Illuminate\Support\Carbon::parse($school->user->membership_expiry_date)->format('d M Y') : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

</div>


@endif

@endsection

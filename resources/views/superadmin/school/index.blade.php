@extends('adminlayout.layout')

@section('title', 'Schools')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Schools</h1>
        <p class="text-muted mb-0">Registered schools</p>
    </div>
    <a href="{{ url('school/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Register School
    </a>
</div>

<div class="row g-3">
    @if(!empty($schools))
    @foreach($schools as $key => $school)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-edutrack-dark text-white text-center py-2">
                <h2 class="h6 mb-0 fw-semibold text-truncate" title="{{ $school->school_name }}">
                    {{ $school->school_name }}
                </h2>
            </div>

            <div class="card-body py-3">
                
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Principal Name</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->priciple_name }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Principal Email</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->priciple_email }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Principal Phone No</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->priciple_phone_no }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">City</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->city }}</span>
                </div>

                
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Membership Expiry Date</span>
                    <span class="small text-end fw-medium text-break @if($school->user && $school->user->membership_expiry_date) @if($school->user->membership_expiry_date > now()) text-success @else text-danger @endif @else text-muted @endif" style="max-width: 58%;">
                        @if($school->user)
                            {{ $school->user->membership_expiry_date ? ($school->user->membership_expiry_date > now() ? $school->user->membership_expiry_date : 'Expired') : 'N/A' }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                
                <hr class="m-0">

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Address</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->address }}</span>
                </div>

            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center gap-2 py-2">
                <span class="badge rounded-pill border fw-bold px-3 py-2 @if($school->membership->membership_name == 'Basic') bg-secondary @elseif($school->membership->membership_name == 'Standard') bg-info @elseif($school->membership->membership_name == 'Premium') bg-success @elseif($school->membership->membership_name == 'Diamond') bg-danger @else bg-warning @endif">
                    {{ $school->membership->membership_name ?? 'No membership plan selected'}}
                </span>
                <div class="btn-group btn-group-sm flex-shrink-0">
                    <a href="{{ url('/school/show/'.$school->id) }}" class="btn btn-outline-secondary" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ url('/school/edit/'.$school->id) }}" class="btn btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger action-btn delete" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-muted">No schools found</div>
        </div>
    </div>
    @endif
</div>

@endsection

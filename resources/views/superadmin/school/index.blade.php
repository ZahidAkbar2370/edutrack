@extends('adminlayout.layout')

@section('title', 'Schools')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Registered Schools</h1>
        <p class="text-muted mb-0">All registered schools list</p>
    </div>
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
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>School</th>
                        <th>Principal</th>
                        <th>City & Address</th>
                        <th>Students</th>
                        <th>Teachers</th>
                        <th>Tests</th>
                        <th>Whatsapp Alerts</th>
                        <th>Membership</th>
                        <th>Expiry Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($schools))
                    @foreach($schools as $key => $school)

                        @php
                            $studentCount = \App\Models\Student::where('school_id', $school->id)->count();
                            $teacherCount = \App\Models\Teacher::where('school_id', $school->id)->count();
                            $dailyTestCount = \App\Models\DailyTest::where('school_id', $school->id)->count();
                            $whatsappCount = 0;
                        @endphp

                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $school->school_name }}</span> <br>
                                <span class="text-muted small">(Phone #: +{{ $school->school_phone_no ?? '—' }})</span>
                            </td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $school->priciple_name }}</span> <br>
                                <span class="text-muted small">(Phone #: +{{ $school->priciple_phone_no ?? '—' }})</span>
                            </td>
                            <td class="fw-medium">
                                <span class="fw-medium text-uppercase">{{ $school->city }}</span> <br>
                                <span class="text-muted small">({{ $school->address ?? '—' }})</span>
                            </td>
                            <td>{{ $studentCount }}</td>
                            <td>{{ $teacherCount }}</td>
                            <td>{{ $dailyTestCount }}</td>
                            <td>{{ $whatsappCount }}</td>
                            <td>
                                @php $membershipName = $school->membership->membership_name; @endphp
                                <span class="@if($membershipName == 'Free Trail') bg-warning @elseif($membershipName == 'Basic') bg-secondary @elseif($membershipName == 'Standard') bg-success @elseif($membershipName == 'Premium') bg-danger @elseif($membershipName == 'Diamond') bg-primary @endif text-white p-2 rounded">{{ $school->membership->membership_name }}</span>
                            </td>
                            <td class="fw-medium">
                            <span class="small text-end fw-medium text-break @if($school->user && $school->user->membership_expiry_date) @if($school->user->membership_expiry_date > now()) text-success @else text-danger @endif @else text-muted @endif" style="max-width: 58%;">
                        @if($school->user)
                            @if($school->user->membership_expiry_date > now())
                                <span>{{\Carbon\Carbon::parse($school->user->membership_expiry_date)->format('Y-m-d')}}</span>
                            @else
                                <span>Expired - {{\Carbon\Carbon::parse($school->user->membership_expiry_date)->format('Y-m-d')}}</span>
                            @endif
                        @endif
                    </span>
                            </td>


                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                               
                                <div class="btn-group btn-group-sm">

                                <a href="https://wa.me/{{$school->school_phone_no}}" class="btn btn-success text-white" title="Send Message Via Whatsapp" target="_blank">
                                        <i class="bi bi-whatsapp"></i> Whatsapp
                                    </a>

                                    <a href="{{ url('school/show/' . $school->id) }}" class="btn btn-secondary text-white" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10" class="text-center">No data found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
<!-- 

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
    @if($schools->count())
    @foreach($schools as $school)


    @php
        $studentCount = \App\Models\Student::where('school_id', $school->id)->count();
        $teacherCount = \App\Models\Teacher::where('school_id', $school->id)->count();
        $dailyTestCount = \App\Models\DailyTest::where('school_id', $school->id)->count();
        $whatsappCount = 0;
    @endphp

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
                    <span class="text-muted small">Principal Phone No</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;">{{ $school->priciple_phone_no }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Membership Expiry Date</span>
                    <span class="small text-end fw-medium text-break @if($school->user && $school->user->membership_expiry_date) @if($school->user->membership_expiry_date > now()) text-success @else text-danger @endif @else text-muted @endif" style="max-width: 58%;">
                        @if($school->user)
                            @if($school->user->membership_expiry_date > now())
                                <span>{{\Carbon\Carbon::parse($school->user->membership_expiry_date)->format('Y-m-d')}}</span>
                            @else
                                <span>Expired - {{\Carbon\Carbon::parse($school->user->membership_expiry_date)->format('Y-m-d')}}</span>
                            @endif
                        @endif
                    </span>
                </div>

                <hr class="mt-2">

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Total Students</span>
                   <span class="small text-end fw-medium text-break" style="max-width: 58%;"><span class="badge rounded-pill border fw-bold px-3 py-2 bg-success">{{ $studentCount }}</span></span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Total Teachers</span>
                   <span class="small text-end fw-medium text-break" style="max-width: 58%;"><span class="badge rounded-pill border fw-bold px-3 py-2 bg-danger">{{ $teacherCount }}</span></span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Total Daily Tests</span>
                    <span class="small text-end fw-medium text-break" style="max-width: 58%;"><span class="badge rounded-pill border fw-bold px-3 py-2 bg-warning">{{ $dailyTestCount }}</span></span>
                </div>

                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 {{ $loop->last ? 'mb-0' : '' }}">
                    <span class="text-muted small">Total Whatsapp Messages</span>
                   <span class="small text-end fw-medium text-break" style="max-width: 58%;"><span class="badge rounded-pill border fw-bold px-3 py-2 bg-primary">{{ $whatsappCount }}</span></span>
                </div>
                
                <hr class="mt-2">

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

@endsection -->

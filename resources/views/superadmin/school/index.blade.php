@extends('adminlayout.layout')

@section('title', 'Schools')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Registered Schools</h1>
        <p class="text-muted mb-0">All registered schools list</p>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">
        <a href="{{ url('school/create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Register School
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-3">
    <div class="card-header bg-light">
        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Schools</h2>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('school') }}" class="row g-3 align-items-end">

        <div class="col-md-3">
                <label for="school_name" class="form-label">School Name</label>
                <input type="text" name="school_name" id="school_name" class="form-control" placeholder="Search by school name"
                    value="{{ isset($_GET['school_name']) ? $_GET['school_name'] : '' }}">
            </div>

            <div class="col-md-2">
                <label for="class_id" class="form-label">Membership</label>
                <select name="membership_id" id="membership_id" class="form-select">
                    <option value="">All Memberships</option>
                    @if(!empty($memberships))
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ isset($_GET['membership_id']) && $_GET['membership_id'] == $membership->id ? 'selected' : '' }}>
                                    {{ $membership->membership_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <label for="section_id" class="form-label">Status</label>
                <select name="expiry_status" id="expiry_status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ isset($_GET['expiry_status']) && $_GET['expiry_status'] == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ isset($_GET['expiry_status']) && $_GET['expiry_status'] == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label">City</label>
                <select name="city" id="city" class="form-select">
                    <option value="">All Cities</option>
                    <option value="active" {{ isset($_GET['city']) && $_GET['city'] == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ isset($_GET['city']) && $_GET['city'] == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="banned" {{ isset($_GET['city']) && $_GET['city'] == 'banned' ? 'selected' : '' }}>Banned</option>
                    <option value="inactive" {{ isset($_GET['city']) && $_GET['city'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ url('school') }}" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

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
                    @if(!empty($schools) && count($schools) > 0)
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
                        <td colspan="11" class="text-center">No data found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@extends('adminlayout.layout')

@section('title', 'School Transaction History')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">School Transaction History</h1>
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
                        <th>Purpose</th>
                        <th>Image</th>
                        <th>Membership</th>
                        <th>Expiry Date</th>
                        <th>Amount</th>
                        <th>Note</th>
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

                                <!-- <a href="{{ url('school/upgrade/' . $school->id) }}" class="btn btn-success text-white" title="Upgrade Plan">
                                        <i class="bi bi-pencil"></i> Edit Membership
                                    </a> -->

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
                        <td colspan="8" class="text-center">No data found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
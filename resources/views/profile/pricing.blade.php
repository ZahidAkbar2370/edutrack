@extends('adminlayout.layout')

@section('title', 'Pricing Plans')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Pricing Plans</h1>
        <p class="text-muted mb-0">Compare pricing, limits, and features across all plans</p>
    </div>
</div>

{{-- Feature comparison table --}}
<div class="card shadow-sm">
    <div class="card-body pt-2">
        <div class="table-scroll-wrap">
            <p class="table-scroll-hint alert alert-light border py-2 mb-2 text-center">
                <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to compare all plans
            </p>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 membership-compare-table">
                    <tbody>

                        <tr>
                            <th>Plan Name</th>
                            <th>Price</th>
                            <th>Students Limit</th>
                            <th>Teachers Limit</th>
                            <th>Student Card</th>
                            <th>Attendance</th>
                            <th>Daily Test</th>
                            <th>Fee Management</th>
                            <th>WhatsApp Alert</th>
                            <th>Status</th>
                        </tr>

                        @php
                            $iconAllowed = '<i class="bi bi-check-circle-fill text-success"></i>';
                            $iconNotAllowed = '<i class="bi bi-x-circle text-muted"></i>';
                            $iconUnlimited = '<i class="bi bi-infinity"></i>';
                        @endphp

                        @if($memberships->count())
                            @foreach($memberships as $membership)
                                @php 
                                    $isCurrent = $membership->id == $currentMembership->membership_id; 
                                @endphp
                                
                                <tr>
                                    <th scope="row" class="membership-feature-col bg-light">
                                        <i class="bi bi-{{ $membership->icon }} me-2 text-primary"></i>{{ $membership->membership_name }}
                                    </th>
                                    <td class="text-center">
                                        <span>PKR {{ number_format((int) $membership->membership_price) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->students_limit == null ? $iconUnlimited : $membership->students_limit !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->teachers_limit == null ? $iconUnlimited : $membership->teachers_limit !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->allowed_student_card == true ? $iconAllowed : $iconNotAllowed !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->allowed_attendance == true ? $iconAllowed : $iconNotAllowed !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->allowed_daily_test == true ? $iconAllowed : $iconNotAllowed !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->allowed_fee_management == true ? $iconAllowed : $iconNotAllowed !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{!! $membership->allowed_whatsapp_message == true ? $iconAllowed : $iconNotAllowed !!}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($isCurrent)
                                            <span class="btn btn-primary bg-primary">Current Plan</span>
                                        @else
                                        @if($membership->membership_name != 'Free Trail')
                                        @php
                                            $message = "Hi, I want to change my membership to *" . $membership->membership_name . "* \n\n";
                                            $message .= "Name: " . Auth::user()->name . "\n";
                                            $message .= "Email: " . Auth::user()->email . "\n";
                                            $message .= "My Current Membership: " . $currentMembership->membership->membership_name;
                                        @endphp

                                            <span class="text-muted small"><a href="https://api.whatsapp.com/send?phone=923081312527&text={{ urlencode($message) }}"
   target="_blank"
   class="btn btn-success btn-sm">
    <i class="bi bi-whatsapp me-1"></i>Contact Us
</a></span>

@php $message = ""; @endphp

@else
<span class="text-muted small">—</span>

                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">No plans found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Legend --}}
<div class="d-flex flex-wrap gap-3 mt-3 small text-muted">
    <span><i class="bi bi-check-circle-fill text-success me-1"></i> Included</span>
    <span><i class="bi bi-x-circle me-1"></i> Not included</span>
    <span><i class="bi bi-infinity me-1"></i> Unlimited limit</span>
</div>

@endsection

@extends('schooladmin.layout.layout')

@section('title', 'Class Fee — ' . ($schoolClass->class_name ?? ''))

@section('content')

@php
$monthLabel = \Illuminate\Support\Carbon::createFromFormat('Y-m', $feeMonth)->format('F Y');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">{{ $schoolClass->class_name }} — Fee Collection</h1>
        <p class="text-muted mb-0">{{ $monthLabel }}</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="#" class="btn btn-outline-success" data-confirm-action
            data-confirm-title="Export Unpaid Students to Excel"
            data-confirm-message="Are you sure you want to export unpaid students to Excel?"
            data-confirm-yes="Yes, Export"
            data-confirm-yes-class="btn-success">
            <i class="bi bi-download me-1"></i> Export Unpaid Students to CSV
        </a>

        <a href="{{ url('fee-management?fee_month=' . $feeMonth) }}" class="btn btn-outline-secondary">
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

<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ url('fee-management/show/' . $schoolClass->id) }}" class="row g-3 align-items-end">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label for="fee_month" class="form-label">Fee Month</label>
                <input type="month" name="fee_month" id="fee_month" class="form-control" value="{{ $feeMonth }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Change Month</button>
            </div>
        </form>
    </div>
</div>

@if($students->isEmpty())
<div class="alert alert-warning">No active students in this class.</div>
@else
<div class="row">
    <div class="col-md-6">

        <form action="{{ url('fee-management/store') }}" method="POST" id="fee-form">
            @csrf
            <input type="hidden" name="class_id" value="{{ $schoolClass->id }}">
            <input type="hidden" name="fee_month" value="{{ $feeMonth }}">

            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0 fw-semibold">Students</h2>
                    <span class="small text-muted">{{ $students->count() }} student(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Section</th>
                                    <th>Remaining Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $key => $student)

                                @php
                                $studentFeeLastestRecord = \App\Models\MonthlyFee::where('student_id', $student->id)->latest()->first();
                                @endphp

                                <tr class="fee-row">
                                    <td>{{ $key + 1 }}</td>

                                    <td class="fw-medium">
                                        {{ $student->student_name }}
                                        <br>
                                        <span class="text-muted small">Roll: {{ $student->student_roll_number ?? '—' }}</span>
                                    </td>

                                    <td>{{ $student->section->section_name ?? '—' }}</td>


                                    <td>

                                        @if($studentFeeLastestRecord)
                                            <span class="monthly-display monthly-display-remaining-amount-{{ $student->id }}">{{ number_format($studentFeeLastestRecord->remaining_amount, 2) }}</span>
                                        @else
                                            <span class="monthly-display monthly-display-remaining-amount-{{ $student->id }}">{{ number_format($student->student_per_month_fee, 2) }}</span>
                                        @endif
                                    </td>


                                    <td>
                                        <div class="monthly-fee-status-container-{{ $student->id }}">
                                        @if($studentFeeLastestRecord)
                                            @if($studentFeeLastestRecord->remaining_amount > 0)
                                                <span class="badge bg-warning text-dark monthly-fee-remaining-status-{{ $student->id }}">Remaining</span>
                                            @else
                                                <span class="badge bg-success text-dark monthly-fee-paid-status-{{ $student->id }}">Paid</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger monthly-fee-unpaid-status-{{ $student->id }}">Unpaid</span>
                                        @endif
                                        </div>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm pay-fee-button" data-student-id="{{ $student->id }}" data-payment-month="{{ $_GET['fee_month'] }}" id="pay-fee-button">
                                            <i class="bi bi-cash-coin me-1"></i> Pay Fee
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </form>

    </div>



    <div class="col-md-6">
        <div class="row card p-1" id="student-fee-payment-records">

            <div class="col-md-12">

                <!-- no record found -->
                <div class="text-center text-muted py-4">Select a student to view their fee payment records</div>

            </div>



        </div>
    </div>

</div>
@endif


@include('schooladmin.feemanagement.javascript')

@endsection
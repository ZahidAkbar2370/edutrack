@extends('schooladmin.layout.layout')

@section('title', 'Fee History — ' . $student->student_name)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Fee History</h1>
        <p class="text-muted mb-0">
        <code><span class="fw-medium">{{ $student->student_name }}</span> -> (Roll # {{ $student->student_roll_number ?? '—' }})
            -> {{ $student->schoolClass->class_name ?? 'N/A' }} -> {{ $student->section->section_name ?? 'N/A' }} </code>
        </p>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">

        <a href="{{ url('student/' . $student->id . '/export-fee-history-csv') }}" class="btn btn-outline-success btn-sm" 
        
        data-confirm-action
                    data-confirm-title="Export Fee History to Excel"
                    data-confirm-message="Are you sure you want to export to CSV?"
                    data-confirm-yes="Yes, Export"
                    data-confirm-yes-class="btn-success"

        >
            <i class="bi bi-download me-1"></i> Export to CSV
        </a>

        <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Student Detail
    </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fee Month</th>
                        <th>Monthly Fee / Remaining Amount</th>
                        <th>Any Fine</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Remaining Amount</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($feeHistory) && $feeHistory->count() > 0)
                        @foreach($feeHistory as $key => $fee)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($fee->payment_date)->format('M Y') }}</td>
                                <td>
                                    {{ $fee->monthly_fee_amount }}
                                </td>
                                <td>
                                    {{ $fee->any_fine_amount }}
                                </td>
                                <td>
                                    {{ $fee->any_discount_amount }}
                                </td>
                                <td>
                                    {{ $fee->total_amount }}
                                </td>
                                <td>
                                    {{ $fee->paid_amount }}
                                </td>
                                <td>
                                    {{ $fee->remaining_amount }}
                                </td>
                                <td>
                                    {{ $fee->created_at }} - {{$fee->created_at->diffForHumans()}}
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No fee transaction records for this student</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

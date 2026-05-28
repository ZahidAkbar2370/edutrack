@extends('adminlayout.layout')

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
    <a href="{{ url('fee-management?fee_month=' . $feeMonth) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Classes
    </a>
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
                                <th>Monthly Fee</th>
                                <th>Fine</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $key => $student)
                                @php
                                    $fee = $fees->get($student->id);
                                    $isPaid = $fee && (float) $fee->remaining_amount <= 0;
                                    $monthly = (float) ($fee->monthly_fee_amount ?? $student->student_per_month_fee ?? 0);
                                    $fine = (float) ($fee->any_fine_amount ?? 0);
                                    $discount = (float) ($fee->any_discount_amount ?? 0);
                                    $total = (float) ($fee->total_amount ?? max(0, $monthly + $fine - $discount));
                                    $paid = (float) ($fee->paid_amount ?? 0);
                                    $remaining = (float) ($fee->remaining_amount ?? max(0, $total - $paid));
                                @endphp
                                <tr class="fee-row {{ $isPaid ? 'table-success' : '' }}"
                                    data-student-id="{{ $student->id }}"
                                    data-readonly="{{ $isPaid ? '1' : '0' }}"
                                    data-monthly="{{ $monthly }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td class="fw-medium">
                                        {{ $student->student_name }}
                                        <br>
                                        <span class="text-muted small">Roll: {{ $student->student_roll_number ?? '—' }}</span>
                                    </td>
                                    <td>{{ $student->section->section_name ?? '—' }}</td>
                                    <td>
                                        <span class="monthly-display">{{ number_format($monthly, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span>{{ number_format($fine, 2) }}</span>
                                        @else
                                            <input type="number" step="0.01" min="0"
                                                   name="students[{{ $student->id }}][any_fine_amount]"
                                                   class="form-control form-control-sm fee-fine"
                                                   value="{{ $fine > 0 ? $fine : '' }}" placeholder="0">
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span>{{ number_format($discount, 2) }}</span>
                                        @else
                                            <input type="number" step="0.01" min="0"
                                                   name="students[{{ $student->id }}][any_discount_amount]"
                                                   class="form-control form-control-sm fee-discount"
                                                   value="{{ $discount > 0 ? $discount : '' }}" placeholder="0">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fee-total fw-semibold">{{ number_format($total, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span class="text-success fw-semibold">{{ number_format($paid, 2) }}</span>
                                        @else
                                            <input type="number" step="0.01" min="0"
                                                   name="students[{{ $student->id }}][paid_amount]"
                                                   class="form-control form-control-sm fee-paid"
                                                   value="{{ $paid > 0 ? $paid : '' }}" placeholder="0">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fee-remaining fw-semibold {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($remaining, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isPaid)
                                            <span class="small text-muted">{{ $fee->note ?? '—' }}</span>
                                        @else
                                            <input type="text"
                                                   name="students[{{ $student->id }}][note]"
                                                   class="form-control form-control-sm"
                                                   value="{{ $fee->note ?? '' }}"
                                                   placeholder="Optional note">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Save fee payments for unpaid students?')">
                <i class="bi bi-check-lg me-1"></i> Save Fees
            </button>
        </div>
    </form>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function parseNum(val) {
            var n = parseFloat(val);
            return isNaN(n) ? 0 : n;
        }

        function recalcRow(row) {
            if (row.dataset.readonly === '1') return;

            var monthly = parseNum(row.dataset.monthly);
            var fine = parseNum(row.querySelector('.fee-fine')?.value);
            var discount = parseNum(row.querySelector('.fee-discount')?.value);
            var paid = parseNum(row.querySelector('.fee-paid')?.value);

            var total = Math.max(0, monthly + fine - discount);
            var remaining = Math.max(0, total - paid);

            row.querySelector('.fee-total').textContent = total.toFixed(2);
            var remEl = row.querySelector('.fee-remaining');
            remEl.textContent = remaining.toFixed(2);
            remEl.classList.toggle('text-danger', remaining > 0);
            remEl.classList.toggle('text-success', remaining <= 0);
        }

        document.querySelectorAll('.fee-row').forEach(function (row) {
            row.querySelectorAll('.fee-fine, .fee-discount, .fee-paid').forEach(function (input) {
                input.addEventListener('input', function () {
                    recalcRow(row);
                });
            });
        });
    });
</script>
@endpush

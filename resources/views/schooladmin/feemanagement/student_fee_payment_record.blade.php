<div class="col-md-12">

                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0 fw-semibold">Student Fee Payments</h2>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Monthly Fee</th>
                                        <th>Fine</th>
                                        <th>Discount</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Remaining Amount</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @php
                                    $paymentRecordCount = $studentFeePaymentRecords->count();
                                    $totalMonthlyFee = $student->student_per_month_fee;
                                    $totalRemainingAmount = $totalMonthlyFee;
                                @endphp

                                    @if($studentFeePaymentRecords->count() > 0)
                                        @foreach($studentFeePaymentRecords as $studentFeePaymentRecord)
                                            <tr>
                                                <td>{{ $studentFeePaymentRecord->monthly_fee_amount }}</td>
                                                <td>{{ $studentFeePaymentRecord->fine_amount ?? 0 }}</td>
                                                <td>{{ $studentFeePaymentRecord->discount_amount ?? 0 }}</td>
                                                <td>{{ $studentFeePaymentRecord->total_amount ?? 0 }}</td>
                                                <td>{{ $studentFeePaymentRecord->paid_amount ?? 0 }}</td>
                                                <td>{{ $studentFeePaymentRecord->remaining_amount ?? 0 }}</td>
                                                <td>{{ $studentFeePaymentRecord->note ?? 'N/A' }}</td>
                                            </tr>


                                            @php
                                                $totalRemainingAmount = $studentFeePaymentRecord->remaining_amount;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No student fee payment records found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>





                </div>

            </div>



            @if($totalRemainingAmount > 0)
            <div class="col-md-12 mt-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0 fw-semibold">Pay Fee for Selected Student</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Remaining Amount</th>
                                        <th>Fine</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Paid Amount</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>
                                            <span class="fee-remaining fw-semibold text-success">
                                                @if($paymentRecordCount > 0)
                                                    {{ number_format($totalRemainingAmount, 2) }}
                                                @else
                                                    {{ number_format($totalMonthlyFee, 2) }}
                                                @endif
                                                <input type="hidden" id="feemanagement-total-amount" value="{{ $paymentRecordCount > 0 ? $totalRemainingAmount : $totalMonthlyFee }}" readonly>
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm" placeholder="0" id="feemanagement-fine-amount">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm" placeholder="0" id="feemanagement-discount-amount">
                                        </td>
                                        <td>
                                            <span class="fee-remaining fw-semibold text-danger" id="feemanagement-total-amount-display">
                                            @if($paymentRecordCount > 0)
                                                    {{ number_format($totalRemainingAmount, 2) }}
                                                @else
                                                    {{ number_format($totalMonthlyFee, 2) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm" placeholder="0" id="feemanagement-paid-amount">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control form-control-sm" placeholder="Remarks" id="feemanagement-note">
                                            </td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="buton" class="btn btn-primary" id="feemanagement-save-record-button" data-student-id="{{ $student->id }}" data-payment-month="{{ $paymentMonth }}">
                            <i class="bi bi-check-lg me-1"></i> Save Record
                        </button>
                    </div>


            </div>
            @endif
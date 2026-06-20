@extends('schooladmin.layout.layout')

@section('title', 'Transaction History')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Transaction History</h1>
        <p class="text-muted mb-0">Your school's payment and membership transactions</p>
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
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @if($transactions->count())
                    @foreach($transactions as $key => $transaction)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ ucwords(str_replace('_', ' ', $transaction->transaction_purpose)) }}</span>
                            </td>
                            <td class="fw-medium">PKR {{ number_format((int) $transaction->transaction_amount) }}</td>
                            <td>
                                @if($transaction->transaction_prove_image)
                                    <a href="{{ asset($transaction->transaction_prove_image) }}" target="_blank">
                                        <img src="{{ asset($transaction->transaction_prove_image) }}" alt="Proof" class="rounded border" style="width:42px;height:42px;object-fit:cover;">
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="text-center">No transactions found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

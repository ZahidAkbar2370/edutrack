@extends('schooladmin.layout.layout')

@section('title', 'Fee Management')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Fee Management</h1>
        <p class="text-muted mb-0">Monthly fee overview by class</p>
    </div>

    <div class="d-flex flex-wrap gap-2">

        <a href="#" class="btn btn-outline-danger" data-confirm-action
                    data-confirm-title="Unpaid Alert via Whatsapp"
                    data-confirm-message="We are working on it... this feature will be available soon..."
                    data-confirm-yes="Yes, Alert"
                    data-confirm-yes-class="btn-danger"
        >
            <i class="bi bi-whatsapp me-1"></i> Unpaid Alert via Whatsapp
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
    <div class="card-body">
        <form method="GET" action="{{ url('fee-management') }}" class="row g-3 align-items-end">
            
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label for="fee_month" class="form-label">Fee Month</label>
                <input type="month" name="fee_month" id="fee_month" class="form-control"
                       value="{{ $feeMonth }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h2 class="h6 mb-0 fw-semibold">
            Classes — {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $feeMonth)->format('F Y') }}
        </h2>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class Name</th>
                        <th class="text-center">Total Students</th>
                        <th class="text-center">Paid</th>
                        <th class="text-center">Unpaid</th>
                        <th class="text-center">Remaining</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($classStats) && $classStats->count() > 0)
                        @foreach($classStats as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">{{ $row->class->class_name }}</td>
                            <td class="text-center">{{ $row->total_students }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $row->paid_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $row->unpaid_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">0</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ url('fee-management/show/' . $row->class->id . '?fee_month=' . $feeMonth) }}"
                                   class="btn btn-sm btn-outline-primary" title="View students">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No data found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

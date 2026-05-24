@extends('admin.layout.layout')

@section('title', 'Customers')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Customers</h1>
        <p class="text-muted mb-0">Manage customer accounts</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Excel</button>
        <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</button>
        <button type="button" class="btn btn-success btn-sm"><i class="bi bi-filetype-csv me-1"></i> CSV</button>
        <button type="button" class="btn btn-dark btn-sm"><i class="bi bi-printer me-1"></i> Print</button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body border-bottom">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted fw-semibold">SEARCH</label>
                <input type="text" class="form-control" placeholder="Name, email, phone...">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold">STATUS</label>
                <select class="form-select">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                <button type="button" class="btn btn-link text-secondary">Clear</button>
            </div>
        </div>
    </div>

    <div class="card-body pt-2">
        <div class="table-scroll-wrap">
            <p class="table-scroll-hint alert alert-light border py-2 mb-2 text-center">
                <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all columns
            </p>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small text-muted">ID</th>
                            <th class="small text-muted">NAME</th>
                            <th class="small text-muted">EMAIL</th>
                            <th class="small text-muted">PHONE</th>
                            <th class="small text-muted">STATUS</th>
                            <th class="small text-muted">JOINED</th>
                            <th class="small text-muted table-sticky-actions">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['id'=>1,'name'=>'Zahid Akbar','initial'=>'Z','email'=>'zahid@example.com','phone'=>'+92 300 1234567','active'=>true,'joined'=>'Jan 15, 2025'],
                            ['id'=>2,'name'=>'Sara Khan','initial'=>'S','email'=>'sara@example.com','phone'=>'+92 321 9876543','active'=>true,'joined'=>'Mar 02, 2025'],
                            ['id'=>3,'name'=>'Ali Hassan','initial'=>'A','email'=>'ali@example.com','phone'=>'+92 333 5551234','active'=>false,'joined'=>'May 10, 2025'],
                        ] as $row)
                        <tr>
                            <td>{{ $row['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fw-semibold" style="width:36px;height:36px;">{{ $row['initial'] }}</span>
                                    <span>{{ $row['name'] }}</span>
                                </div>
                            </td>
                            <td>{{ $row['email'] }}</td>
                            <td>{{ $row['phone'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input status-switch" type="checkbox" {{ $row['active'] ? 'checked' : '' }}>
                                    </div>
                                    <span class="status-switch-label small {{ $row['active'] ? 'text-success' : 'text-muted' }}">{{ $row['active'] ? 'Active' : 'Inactive' }}</span>
                                </div>
                            </td>
                            <td>{{ $row['joined'] }}</td>
                            <td class="table-sticky-actions">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('/admin/customers/'.$row['id']) }}" class="btn btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-outline-danger action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

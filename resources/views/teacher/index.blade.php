@extends('adminlayout.layout')

@section('title', 'Teachers')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Teachers</h1>
        <p class="text-muted mb-0">All registered teachers</p>
    </div>

    <div class="d-flex flex-wrap gap-2">


    <a href="#" class="btn btn-outline-secondary">
        <i class="bi bi-upload me-1"></i> Import CSV
    </a>

    <a href="#" class="btn btn-outline-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>

        <a href="{{ url('teacher/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Teacher
        </a>
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
                        <th>Teacher Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">{{ $teacher->teacher_name }}</td>
                            <td>{{ $teacher->teacher_email ?? '—' }}</td>
                            <td>{{ $teacher->teacher_phone_no ?? '—' }}</td>
                            <td class="text-truncate" style="max-width: 200px;" title="{{ $teacher->teacher_address }}">
                                {{ $teacher->teacher_address ?? '—' }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('teacher/show/' . $teacher->id) }}" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('teacher/edit/' . $teacher->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ url('teacher/delete/' . $teacher->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No teachers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

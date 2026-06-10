@extends('adminlayout.layout')

@section('title', 'Classes')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Classes</h1>
        <p class="text-muted mb-0">All classes for your school</p>
    </div>
    <a href="{{ url('class/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Class
    </a>
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
                        <th>Class Name</th>
                        <th>Publication Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">{{ $class->class_name }}</td>
                            <td class="fw-medium"><span class="badge bg-{{ $class->publication_status == 'active' ? 'success' : 'danger' }}">{{ $class->publication_status == 'active' ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <!-- <a href="{{ url('class/show/' . $class->id) }}" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('class/edit/' . $class->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a> -->
                                    <!-- <form action="{{ url('class/delete/' . $class->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this class?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form> -->

                                    <!-- add troggle to update publication status -->
                                    <form action="{{ url('class/update-publication-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <input type="hidden" name="publication_status" value="{{ $class->publication_status == 'active' ? 'inactive' : 'active' }}">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="publication_status" name="publication_status" value="active" {{ $class->publication_status == 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="publication_status">{{ $class->publication_status == 'active' ? 'Active' : 'Inactive' }}</label>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No classes found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

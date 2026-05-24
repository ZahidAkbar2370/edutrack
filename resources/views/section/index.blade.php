@extends('adminlayout.layout')

@section('title', 'Sections')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Sections</h1>
        <p class="text-muted mb-0">All sections by class</p>
    </div>
    <a href="{{ url('section/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Section
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
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class Name</th>
                        <th>Section Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $key => $section)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">{{ $section->schoolClass->class_name ?? 'N/A' }}</td>
                            <td>{{ $section->section_name }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('section/show/' . $section->id) }}" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('section/edit/' . $section->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ url('section/delete/' . $section->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this section?');">
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
                            <td colspan="5" class="text-center text-muted py-4">No sections found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

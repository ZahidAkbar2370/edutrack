@extends('admin.layout.layout')

@section('title', 'Edit School')

@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/school') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-2">
        <i class="bi bi-arrow-left"></i> Back to Schools
    </a>
    <h1 class="h3 fw-bold">Edit School</h1>
    <p class="text-muted mb-0">School ID: {{ $id ?? 1 }} (demo)</p>
</div>

<div class="card shadow-sm">
    <div class="card-body text-muted">Edit form — reuse create form with data when backend is ready.</div>
</div>

@endsection

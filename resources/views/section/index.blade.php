@extends('adminlayout.layout')

@section('title', 'Sections')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Sections</h1>
        <p class="text-muted mb-0">All sections by class</p>
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
                        <th>Class Name</th>
                        <th>Section Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($sections))
                    @foreach($sections as $key => $section)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">{{ $section->schoolClass->class_name ?? 'N/A' }}</td>
                            <td>{{ $section->section_name }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">

                                <form class="publication-status-form">
    @csrf

    <div class="form-check form-switch">
        <input
            class="form-check-input publication-status-toggle"
            type="checkbox"
            data-section-id="{{ $section->id }}"
            data-publication-status="{{ $section->publication_status == 'active' ? 'inactive' : 'active' }}"
            {{ $section->publication_status == 'active' ? 'checked' : '' }}
        >
    </div>
</form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

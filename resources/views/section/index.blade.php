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
                        <th>Publication Status</th>
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
                            <td class="fw-medium"><span class="badge bg-{{ $section->publication_status == 'active' ? 'success' : 'danger' }} text-uppercase">{{ $section->publication_status == 'active' ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-center">
                            <form action="{{ url('section/update-publication-status') }}" method="POST" class="d-inline needs-validation" data-confirm-action
                    data-confirm-title="Update Publication Status"
                    data-confirm-message="Are you sure you want to update the publication status of this section?"
                    data-confirm-yes="Yes, Update"
                    data-confirm-yes-class="btn-primary"
                >
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="section_id" value="{{ $section->id }}">
                                        <input type="hidden" name="publication_status" value="{{ $section->publication_status == 'active' ? 'inactive' : 'active' }}">
                                            <input class="btn btn-{{ $section->publication_status == 'active' ? 'danger' : 'success' }} btn-sm text-uppercase" type="submit" value="{{ $section->publication_status == 'active' ? 'Make Inactive' : 'Make Active' }}" title="{{ $section->publication_status == 'inactive' ? 'Make Active' : 'Make Inactive' }}" style="width: 130px;">
                                    </form>
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

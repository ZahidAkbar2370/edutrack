@extends('adminlayout.layout')

@section('title', 'Subjects')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Subjects</h1>
        <p class="text-muted mb-0">All subjects for your school</p>
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
                        <th>Subject Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($subjects))
                    @foreach($subjects as $key => $subject)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">{{ $subject->subject_name }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                <form class="publication-status-form">
    @csrf

    <div class="form-check form-switch">
        <input
            class="form-check-input publication-status-toggle"
            type="checkbox"
            data-subject-id="{{ $subject->id }}"
            data-publication-status="{{ $subject->publication_status == 'active' ? 'inactive' : 'active' }}"
            {{ $subject->publication_status == 'active' ? 'checked' : '' }}
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

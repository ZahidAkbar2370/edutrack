@extends('adminlayout.layout')

@section('title', 'Classes')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Classes</h1>
        <p class="text-muted mb-0">All classes for your school</p>
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
                        <th>Publication Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($classes))
                    @foreach($classes as $key => $class)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">{{ $class->class_name }}</td>
                            <td class="fw-medium"><span class="badge bg-{{ $class->publication_status == 'active' ? 'success' : 'danger' }}" id="class_publication_status_{{ $class->id }}" data-class-id="{{ $class->id }}">{{ $class->publication_status == 'active' ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">

                                    <!-- <form action="{{ url('class/update-publication-status') }}" method="POST" class="d-inline needs-validation">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <input type="hidden" name="publication_status" value="{{ $class->publication_status == 'active' ? 'inactive' : 'active' }}">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="class_publication_status_toggle" name="publication_status" value="active" {{ $class->publication_status == 'active' ? 'checked' : '' }} title="{{ $class->publication_status == 'inactive' ? 'Make Active' : 'Make Inactive' }}">
                                        </div>
                                    </form> -->

                                    <form class="publication-status-form">
    @csrf

    <div class="form-check form-switch">
        <input
            class="form-check-input publication-status-toggle"
            type="checkbox"
            data-class-id="{{ $class->id }}"
            data-publication-status="{{ $class->publication_status == 'active' ? 'inactive' : 'active' }}"
            {{ $class->publication_status == 'active' ? 'checked' : '' }}
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


@include('class.javascript')

@endsection

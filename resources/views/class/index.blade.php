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
                        <td class="fw-medium"><span class="badge bg-{{ $class->publication_status == 'active' ? 'success' : 'danger' }} text-uppercase">{{ $class->publication_status == 'active' ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">

                                <form action="{{ url('class/update-publication-status') }}" method="POST" class="d-inline needs-validation" data-confirm-action
                    data-confirm-title="Update Publication Status"
                    data-confirm-message="Are you sure you want to update the publication status of this class?"
                    data-confirm-yes="Yes, Update"
                    data-confirm-yes-class="btn-primary"
                >
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                        <input type="hidden" name="publication_status" value="{{ $class->publication_status == 'active' ? 'inactive' : 'active' }}">
                                            <input class="btn btn-{{ $class->publication_status == 'active' ? 'danger' : 'success' }} btn-sm text-uppercase" type="submit" value="{{ $class->publication_status == 'active' ? 'Make Inactive' : 'Make Active' }}" title="{{ $class->publication_status == 'inactive' ? 'Make Active' : 'Make Inactive' }}" style="width: 130px;">
                                    </form>

                                <!-- <form method="POST" action="{{ URL::to('class/update-publication-status') }}" class="publication-status-form">
                                    @csrf

                                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                                    <input type="hidden" name="publication_status" value="{{ $class->publication_status == 'active' ? 'inactive' : 'active' }}">

                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input" id="publication-status-toggle"
                                            type="checkbox"

                                            

                                            {{ $class->publication_status == 'active' ? 'checked' : '' }}>
                                    </div>
                                </form> -->

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
@extends('adminlayout.layout')

@section('title', 'Admin Profile')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Admin Profile</h1>
        <p class="text-muted mb-0">Update your account information</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ URL::to('admin-profile') }}" method="POST" id="admin-profile-form">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-person-circle me-1"></i> Admin Profile</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required value="{{ old('name')  ?? $user->name }}">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required value="{{ old('email')  ?? $user->email }}">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update your profile?')"><i class="bi bi-person-circle me-1"></i> Update Profile</button>
                    </div>

                </div>
            </div>
        </div>

    </div>


</form>

@endsection

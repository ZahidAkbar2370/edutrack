@extends('adminlayout.layout')

@section('title', 'Change Password')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Change Password</h1>
        <p class="text-muted mb-0">Change your account login password</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ URL::to('change-password') }}" method="POST" id="change-password-form">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-lock-fill me-1"></i> Change Password</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Old Password <span class="text-danger">*</span></label>
                        <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter your current password" required value="{{ old('old_password') }}">
                        @error('old_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="At least 8 characters" required value="{{ old('new_password') }}">
                        @error('new_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Re-type the new password" required value="{{ old('new_password_confirmation') }}">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to change your password?')"><i class="bi bi-key me-1"></i> Update Password</button>
                    </div>

                </div>
            </div>
        </div>

    </div>


</form>

@endsection

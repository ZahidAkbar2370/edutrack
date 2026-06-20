@extends('superadmin.layout.layout')

@section('title', 'Change School Username & Password')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Change School Password</h1>
            <p class="text-muted mb-0">Change the password of the school</p>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">
        <a href="{{ url('school/show/' . $school->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to School
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ URL::to('school/change-password/' . $school->id) }}" method="POST" id="school-form">
    @csrf

    {{-- School col-6 | Principal col-6 --}}
    <div class="row g-3 mb-3">

    <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Username</h2>
                </div>
                <div class="card-body">


                    <div class="mb-3">
                        <label for="user_email" class="form-label">Username / Email<span class="text-danger">*</span></label>
                        <input type="text" name="user_email" id="user_email" class="form-control" value="{{ $school->user->email ?? $school->school_email }}" placeholder="example@edutrack.school.com" disabled>
                    </div>

                </div>
            </div>
    </div>

        <div class="col-lg-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Update School Password</h2>
                </div>
                <div class="card-body">


                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="At least 8 characters" required>
                        @error('new_password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Re-type the new password" required>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this school password?')"><i class="bi bi-lock-fill me-1"></i> Update Password</button>
    </div>

</form>

@endsection
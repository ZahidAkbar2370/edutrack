@extends('adminlayout.layout')

@section('title', 'Register School')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Change School Password</h1>
        <p class="text-muted mb-0">Add a new school to EduTrack</p>
    </div>
</div>

<form action="{{ URL::to('school/store') }}" method="POST" id="school-form">
    @csrf

    {{-- School col-6 | Principal col-6 --}}
    <div class="row g-3 mb-3">

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Old Username</h2>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="username" class="form-label">User Email <span class="text-danger">*</span></label>
                        <input type="email" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="User Login Email" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">New Username & Password</h2>
                </div>
                <div class="card-body">

                <div class="mb-3">
                        <label for="username" class="form-label">New User Email <span class="text-danger">*</span></label>
                        <input type="email" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="User Login Email" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="email" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this school?')"><i class="bi bi-building me-1"></i> Update</button>
    </div>

</form>

@endsection
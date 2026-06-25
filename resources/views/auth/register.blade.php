@extends('frontend.layout')

@section('title', 'Register')
@section('main_class', 'auth-page auth-page-wide')
@section('auth_page', true)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="auth-card">

            <div class="auth-card-header">
                <h1 class="h3 mb-1 fw-bold">Register Your School</h1>
                <p class="mb-0 auth-card-subtitle">Create your EduTrack account and start managing attendance, tests, and students.</p>
            </div>

                <div class="auth-card-body">
                    <form method="POST" action="{{ route('register') }}" id="register-form">
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="auth-panel">
                                    <div class="auth-panel-header">
                                        <h2><i class="bi bi-building me-1"></i> School Information</h2>
                                    </div>
                                    <div class="auth-panel-body">
                                        <div class="mb-3">
                                            <label for="school_name" class="form-label">School Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                <input type="text" name="school_name" id="school_name" class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name') }}" placeholder="e.g. City Public School" required autofocus>
                                            </div>
                                            @error('school_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="school_email" class="form-label">School Email <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="school_email" id="school_email" class="form-control @error('school_email') is-invalid @enderror" value="{{ old('school_email') }}" placeholder="school@example.com" required>
                                            </div>
                                            @error('school_email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="school_phone_no" class="form-label">School Phone <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="school_phone_no" id="school_phone_no" class="form-control @error('school_phone_no') is-invalid @enderror" value="{{ old('school_phone_no') }}" placeholder="03001234567 or 04212332222" required>
                                            </div>
                                            @error('school_phone_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>


                                        <!-- <div class="mb-3">
                                            <label for="school_password" class="form-label">School Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                <input type="text" name="school_password" id="school_password" class="form-control @error('school_password') is-invalid @enderror bg-secondary text-white" value="12345678" placeholder="Password" required readonly>
                                            </div>
                                            @error('school_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div> -->

                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="e.g. Layyah">
                                            </div>
                                            @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-0">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Full school address">{{ old('address') }}</textarea>
                                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="auth-panel">
                                    <div class="auth-panel-header">
                                        <h2><i class="bi bi-person-badge me-1"></i> Admin Account</h2>
                                    </div>
                                    <div class="auth-panel-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Full Name" required>
                                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="admin@example.com">
                                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="Password" required>
                                            @error('admin_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Package</h5>
                                                    <p class="card-text">
                                                        <span class="badge bg-primary">Free Trail for 7 days</span>
                                                        <p class="text-muted small">If you want to upgrade to a Paid Plan, please <a href="https://wa.me/{{ $supportWhatsApp }}" target="_blank" rel="noopener noreferrer">contact us</a>.</p>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4 pt-3 border-top">
                            <p class="text-muted small mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="auth-link">Login here</a>
                            </p>
                            <button type="submit" class="btn btn-login px-4"
                                data-confirm-action
                                data-confirm-title="Register School"
                                data-confirm-message="Are you sure you want to register this school?"
                                data-confirm-yes="Yes, Register"
                                data-confirm-yes-class="btn-primary">
                                <i class="bi bi-building-add me-1"></i> Register School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- <script>
document.getElementById('school_name').addEventListener('input', function () {
    let name = this.value;

    let email = name
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .trim()
        .replace(/\s+/g, '')
        + '@gmail.com';

    document.getElementById('school_email').value = email;
});
</script>
@endsection -->

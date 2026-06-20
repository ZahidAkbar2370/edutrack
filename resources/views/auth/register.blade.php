@extends('frontend.layout')

@section('title', 'Register')
@section('main_class', 'auth-page auth-page-wide')
@section('auth_page', true)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="auth-card">
                @include('auth.partials.card-header', [
                    'title' => 'Register Your School',
                    'subtitle' => 'Create your EduTrack account and start managing attendance, tests, and students.',
                    'headerClass' => 'text-center text-md-start',
                ])

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
                                            <label for="school_phone_no" class="form-label">School Phone <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="school_phone_no" id="school_phone_no" class="form-control @error('school_phone_no') is-invalid @enderror" value="{{ old('school_phone_no') }}" placeholder="923001234567" required>
                                            </div>
                                            @error('school_phone_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="school_email" class="form-label">School Email <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="school_email" id="school_email" class="form-control @error('school_email') is-invalid @enderror bg-secondary text-white" value="{{ old('school_email') }}" placeholder="school@example.com" required readonly>
                                            </div>
                                            @error('school_email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="school_password" class="form-label">School Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                <input type="text" name="school_password" id="school_password" class="form-control @error('school_password') is-invalid @enderror bg-secondary text-white" value="12345678" placeholder="Password" required readonly>
                                            </div>
                                            @error('school_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="e.g. Layyah" required>
                                            </div>
                                            @error('city')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-0">
                                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Full school address" required>{{ old('address') }}</textarea>
                                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="auth-panel">
                                    <div class="auth-panel-header">
                                        <h2><i class="bi bi-person-badge me-1"></i> Principal Information</h2>
                                    </div>
                                    <div class="auth-panel-body">
                                        <div class="mb-3">
                                            <label for="priciple_name" class="form-label">Principal Name <span class="text-danger">*</span></label>
                                            <input type="text" name="priciple_name" id="priciple_name" class="form-control @error('priciple_name') is-invalid @enderror" value="{{ old('priciple_name') }}" placeholder="Principal full name" required>
                                            @error('priciple_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="priciple_phone_no" class="form-label">Principal Phone</label>
                                            <input type="text" name="priciple_phone_no" id="priciple_phone_no" class="form-control @error('priciple_phone_no') is-invalid @enderror" value="{{ old('priciple_phone_no') }}" placeholder="+92 321 9876543">
                                            @error('priciple_phone_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-0">
                                            <label for="priciple_email" class="form-label">Principal Email</label>
                                            <input type="email" name="priciple_email" id="priciple_email" class="form-control @error('priciple_email') is-invalid @enderror" value="{{ old('priciple_email') }}" placeholder="principal@example.com">
                                            @error('priciple_email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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

<script>
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
@endsection

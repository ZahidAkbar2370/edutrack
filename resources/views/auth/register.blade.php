@extends('layouts.app')

@section('content')
<style>
    :root {
        --et-primary: #D81B60;
        --et-dark: #05192D;
    }
    .register-hero {
        background: linear-gradient(135deg, var(--et-dark) 0%, #0a2744 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
        padding: 2rem;
    }
    .register-hero .brand-icon {
        width: 48px; height: 48px;
        background: var(--et-primary);
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .btn-et-primary {
        background: var(--et-primary);
        border-color: var(--et-primary);
        color: #fff;
    }
    .btn-et-primary:hover {
        background: #C2185B;
        border-color: #C2185B;
        color: #fff;
    }
    .input-group-text { background: #f8f9fc; }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="register-hero text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                        <div>
                            <h1 class="h3 mb-1 fw-bold">Register Your School</h1>
                            <p class="mb-0 opacity-75">Create your EduTrack account and start managing attendance, tests, and students.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" id="register-form">
                        @csrf

                        <div class="row g-3">
                            {{-- School Information --}}
                            <div class="col-lg-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-building me-1"></i> School Information</h2>
                                    </div>
                                    <div class="card-body">
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

                            {{-- Principal Information --}}
                            <div class="col-lg-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-person-badge me-1"></i> Principal Information</h2>
                                    </div>
                                    <div class="card-body">
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
                                <a href="{{ route('login') }}" class="fw-semibold">Login here</a>
                            </p>
                            <button type="submit" class="btn btn-et-primary px-4"
                            data-confirm-action
                            data-confirm-title="Register School"
                            data-confirm-message="Are you sure you want to register this school?"
                            data-confirm-yes="Yes, Register"
                            data-confirm-yes-class="btn-primary"
                            >
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
        .replace(/[^a-z0-9\s]/g, '')   // remove special chars
        .trim()
        .replace(/\s+/g, '')           // remove spaces
        + '@edutrack.softwebies.com';

    document.getElementById('school_email').value = email;
});
</script>
@endsection
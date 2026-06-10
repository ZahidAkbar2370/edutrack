@extends('layouts.app')

@section('content')
<style>
    :root {
        --et-primary: #D81B60;
        --et-dark: #05192D;
    }
    .auth-hero {
        background: linear-gradient(135deg, var(--et-dark) 0%, #0a2744 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
        padding: 2rem;
    }
    .auth-hero .brand-icon {
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
    .auth-link { color: var(--et-primary); text-decoration: none; }
    .auth-link:hover { color: #C2185B; text-decoration: underline; }
</style>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="auth-hero text-center">
                    <span class="brand-icon mb-3"><i class="bi bi-mortarboard-fill"></i></span>
                    <h1 class="h3 mb-1 fw-bold">Welcome Back</h1>
                    <p class="mb-0 opacity-75 small">Sign in to your EduTrack school dashboard</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}"
                                    placeholder="admin@school.com"
                                    required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Enter your password"
                                    required autocomplete="current-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-link small fw-semibold">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-et-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="auth-link fw-semibold">Register your school</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
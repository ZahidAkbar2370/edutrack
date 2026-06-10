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
    .auth-link { color: var(--et-primary); text-decoration: none; }
    .auth-link:hover { color: #C2185B; text-decoration: underline; }
</style>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="auth-hero text-center">
                    <span class="brand-icon mb-3"><i class="bi bi-envelope-check-fill"></i></span>
                    <h1 class="h3 mb-1 fw-bold">Verify Your Email</h1>
                    <p class="mb-0 opacity-75 small">One more step before you can access EduTrack</p>
                </div>

                <div class="card-body p-4">
                    @if (session('resent'))
                        <div class="alert alert-success d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="text-muted small mb-2">
                            We've sent a verification link to:
                        </p>
                        <p class="fw-semibold mb-0">
                            <i class="bi bi-envelope me-1 text-secondary"></i>
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <p class="text-muted small text-center mb-4">
                        Please check your inbox and click the verification link to activate your account.
                    </p>

                    <div class="alert alert-warning d-flex gap-2 py-2 small mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                        <div>
                            <strong>Can't find the email?</strong>
                            Check your <strong>Spam</strong> or <strong>Junk</strong> folder. Some providers may filter automated messages there.
                        </div>
                    </div>

                    <form method="POST" action="{{ route('verification.resend') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-et-primary w-100 py-2">
                            <i class="bi bi-arrow-repeat me-1"></i> Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 py-2">
                            <i class="bi bi-box-arrow-left me-1"></i> Logout
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">
                            Wrong email address?
                            <span class="d-block">Logout and register again with the correct email.</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
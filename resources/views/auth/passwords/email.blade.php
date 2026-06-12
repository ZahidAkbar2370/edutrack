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
                    <span class="brand-icon mb-3"><i class="bi bi-key-fill"></i></span>
                    <h1 class="h3 mb-1 fw-bold">Forgot Password?</h1>
                    <p class="mb-0 opacity-75 small">Enter your email and we'll send you a reset link</p>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center gap-2 py-2 small mb-4" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
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

                        <button type="submit" class="btn btn-et-primary w-100 py-2"
                        data-confirm-action
                        data-confirm-title="Send Reset Link"
                        data-confirm-message="Are you sure you want to send a reset link to your email ? (View Inbox or Spam Folder)"
                        data-confirm-yes="Yes, Send"
                        data-confirm-yes-class="btn-primary"
                        >
                            <i class="bi bi-send me-1"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <a href="{{ route('login') }}" class="auth-link small fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
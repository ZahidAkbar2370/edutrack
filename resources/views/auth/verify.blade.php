@extends('frontend.layout')

@section('title', 'Verify Email')
@section('main_class', 'auth-page')
@section('auth_page', true)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                @include('auth.partials.card-header', [
                    'title' => 'Verify Your Email',
                    'subtitle' => 'One more step before you can access EduTrack',
                ])

                <div class="auth-card-body">
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
                        <button type="submit" class="btn btn-login w-100 py-2">
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

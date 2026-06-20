@extends('frontend.layout')

@section('title', 'Forgot Password')
@section('main_class', 'auth-page')
@section('auth_page', true)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                @include('auth.partials.card-header', [
                    'title' => 'Forgot Password?',
                    'subtitle' => "Enter your email and we'll send you a reset link",
                ])

                <div class="auth-card-body">
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

                        <button type="submit" class="btn btn-login w-100 py-2"
                            data-confirm-action
                            data-confirm-title="Send Reset Link"
                            data-confirm-message="Are you sure you want to send a reset link to your email? (View Inbox or Spam Folder)"
                            data-confirm-yes="Yes, Send"
                            data-confirm-yes-class="btn-primary">
                            <i class="bi bi-send me-1"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <a href="{{ route('login') }}" class="auth-link small">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

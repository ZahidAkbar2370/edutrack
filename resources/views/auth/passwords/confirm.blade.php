@extends('frontend.layout')

@section('title', 'Confirm Password')
@section('main_class', 'auth-page')
@section('auth_page', true)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                @include('auth.partials.card-header', [
                    'title' => 'Confirm Password',
                    'subtitle' => 'Please confirm your password before continuing',
                ])

                <div class="auth-card-body">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Enter your password"
                                    required autocomplete="current-password" autofocus>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login w-100 py-2 mb-3">
                            <i class="bi bi-check2 me-1"></i> Confirm Password
                        </button>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="auth-link small">
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

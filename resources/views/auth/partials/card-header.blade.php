<div class="auth-card-header {{ $headerClass ?? 'text-center' }}">
    <a href="{{ url('/') }}" class="auth-card-logo d-inline-block mb-3">
        <img src="{{ asset('images/edutrack-logo.png') }}" alt="EduTrack">
    </a>
    <h1 class="h3 mb-1 fw-bold">{{ $title }}</h1>
    @if(!empty($subtitle))
        <p class="mb-0 auth-card-subtitle">{{ $subtitle }}</p>
    @endif
</div>

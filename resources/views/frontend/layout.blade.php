<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="keywords" content="EduTrack, Smart School Management System, School Management System, School Management Software, School Management Solution, School Management Solution Software, School Management Solution Software">
    <meta name="author" content="EduTrack Powered by Softwebies">
    <meta name="description" content="@yield('meta_description', 'EduTrack — Smart School Management System')">

    <title>@yield('title', 'EduTrack') — EduTrack</title>

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body class="landing-page">


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg landing-nav fixed-top scrolled" id="mainNav">
        <div class="container">
            <a class="landing-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/edutrack-logo.png') }}" alt="EduTrack">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    @if(trim($__env->yieldContent('auth_page')))
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                        @if(request()->routeIs('login'))
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('register') }}" class="btn btn-login">
                                    <i class="bi bi-building-add me-1"></i> Register
                                </a>
                            </li>
                        @elseif(request()->routeIs('register'))
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('login') }}" class="btn btn-login">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                </a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('login') }}" class="btn btn-login">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/#features') }}">Features</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/#pricing') }}">Pricing</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/#how-it-works') }}">How It Works</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/#contact') }}">Contact</a></li>
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('register') }}" class="btn btn-login">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Free Trial
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="@yield('main_class')">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <a href="{{ url('/') }}" class="landing-brand d-inline-flex">
                        <img src="{{ asset('images/edutrack-logo.png') }}" alt="EduTrack">
                    </a>
                    <p class="mt-2 mb-0 small">Smart school management for modern education.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('login') }}" class="me-3">Login</a>
                    <a href="{{ url('/#pricing') }}" class="me-3">Plans</a>
                    <a href="{{ url('privacy-policy') }}" class="me-3">Privacy</a>
                    <a href="{{ url('terms-and-conditions') }}">Terms & Conditions</a>
                    <p class="mt-2 mb-0 small">&copy; {{ date('Y') }} EduTrack. All rights reserved. Powered by <a href="https://softwebies.com" target="_blank" rel="noopener noreferrer" class="text-white fw-bold">Softwebies</a></p>
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/{{ $supportWhatsApp }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp" title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

    @include('common.confirm_alert_model')
    @include('common.confirm_alert_javascript')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function () {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
        });

        document.getElementById('contactForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            document.getElementById('contactSuccess')?.classList.remove('d-none');
            this.reset();
        });
    </script>
</body>
</html>
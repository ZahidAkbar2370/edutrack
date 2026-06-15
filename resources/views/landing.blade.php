<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EduTrack — School management platform for attendance, students, daily tests, and multi-school administration.">
    <title>EduTrack — Smart School Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body class="landing-page">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg landing-nav fixed-top" id="mainNav">
        <div class="container">
            <a class="landing-brand" href="{{ url('/') }}">
                <!-- <span class="landing-brand-icon"><i class="bi bi-mortarboard-fill"></i></span> -->
                <img src="{{ asset('Admin/images/icon-logo.png') }}" alt="EduTrack" width="50" height="50" class="rounded bg-white p-1"> EduTrack
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#plans">Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#schools">Schools</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> -->
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section" id="home">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">
                        One Platform for <span>Every School</span> You Manage
                    </h1>
                    <p class="hero-lead">
                        EduTrack connects multiple schools on a single powerful system — manage students, attendance, daily tests, teachers, and memberships from one secure admin panel.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-login btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>School Registration
                    </a>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <strong>Multi-School</strong>
                            <span>One dashboard, many campuses</span>
                        </div>
                        <div class="hero-stat">
                            <strong>Real-Time</strong>
                            <span>Attendance & test records</span>
                        </div>
                        <div class="hero-stat">
                            <strong>Secure</strong>
                            <span>Role-based access control</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-wrap">
                        <img src="{{ asset('images/landing/hero.jpg') }}"
                             alt="Students on campus — EduTrack school management"
                             loading="eager">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section-padding" id="features">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Everything Your School Needs</h2>
                <p class="section-subtitle">From registration to daily operations — EduTrack simplifies school administration for principals, teachers, and super admins.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-building"></i></div>
                        <h3 class="h5 fw-bold">Multi-School Network</h3>
                        <p class="text-muted mb-0">Register and manage unlimited schools. Each school gets its own classes, sections, students, and staff — all under your control.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <h3 class="h5 fw-bold">Student & Parent Records</h3>
                        <p class="text-muted mb-0">Complete student profiles with class, section, roll number, and linked parent contact information in one form.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-calendar-check"></i></div>
                        <h3 class="h5 fw-bold">Daily Attendance</h3>
                        <p class="text-muted mb-0">Mark present, absent, or leave by class and section. View summaries and edit records anytime.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-clipboard-check"></i></div>
                        <h3 class="h5 fw-bold">Daily Tests & Marks</h3>
                        <p class="text-muted mb-0">Record test scores per subject with obtained marks, totals, and automatic percentage calculation.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-layers"></i></div>
                        <h3 class="h5 fw-bold">Membership Plans</h3>
                        <p class="text-muted mb-0">Flexible plans with feature limits — attendance, daily tests, WhatsApp alerts, and student cards per tier.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                        <h3 class="h5 fw-bold">Secure Roles</h3>
                        <p class="text-muted mb-0">Super admin and school admin access levels keep data safe and operations organized.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans / Membership -->
    <section class="section-padding bg-light-section" id="plans">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Choose Your Plan</h2>
                <p class="section-subtitle">Flexible membership plans for schools of every size. Pick the plan that fits your needs.</p>
            </div>

            @php
                $plans = [
                    [
                        'name' => 'Basic',
                        'price' => '1,000',
                        'validity' => '1 Month',
                        'students' => 'Up to 100 Students',
                        'teachers' => 'Up to 10 Teachers',
                        'featured' => false,
                        'features' => [
                            ['label' => 'Student & Teacher Management', 'enabled' => true],
                            ['label' => 'Student Card', 'enabled' => true],
                            ['label' => 'Class & Section Management', 'enabled' => true],
                            ['label' => 'Attendance Management', 'enabled' => true],
                            ['label' => 'Daily Test & Marks Management', 'enabled' => false],
                            ['label' => 'Fee Management', 'enabled' => false],
                            ['label' => 'WhatsApp Alerts', 'enabled' => false],
                            ['label' => 'Custom Domains & Hosting', 'enabled' => false],
                            ['label' => '24/7 Support', 'enabled' => true],
                            ['label' => 'Refund Policy', 'enabled' => false],
                        ],
                    ],
                    [
                        'name' => 'Standard',
                        'price' => '1,800',
                        'validity' => '1 Month',
                        'students' => 'Up to 250 Students',
                        'teachers' => 'Up to 25 Teachers',
                        'featured' => false,
                        'features' => [
                            ['label' => 'Student & Teacher Management', 'enabled' => true],
                            ['label' => 'Student Card', 'enabled' => true],
                            ['label' => 'Class & Section Management', 'enabled' => true],
                            ['label' => 'Attendance Management', 'enabled' => true],
                            ['label' => 'Daily Test & Marks Management', 'enabled' => true],
                            ['label' => 'Fee Management', 'enabled' => false],
                            ['label' => 'WhatsApp Alerts', 'enabled' => false],
                            ['label' => 'Custom Domains & Hosting', 'enabled' => false],
                            ['label' => '24/7 Support', 'enabled' => true],
                            ['label' => 'Refund Policy', 'enabled' => false],
                        ],
                    ],
                    [
                        'name' => 'Premium',
                        'price' => '3000',
                        'validity' => '1 Month',
                        'students' => 'Up to 500 Students',
                        'teachers' => 'Up to 50 Teachers',
                        'featured' => true,
                        'features' => [
                            ['label' => 'Student & Teacher Management', 'enabled' => true],
                            ['label' => 'Student Card', 'enabled' => true],
                            ['label' => 'Class & Section Management', 'enabled' => true],
                            ['label' => 'Attendance Management', 'enabled' => true],
                            ['label' => 'Daily Test & Marks Management', 'enabled' => true],
                            ['label' => 'Fee Management', 'enabled' => true],
                            ['label' => 'WhatsApp Alerts', 'enabled' => false],
                            ['label' => 'Custom Domains & Hosting', 'enabled' => false],
                            ['label' => '24/7 Support', 'enabled' => true],
                            ['label' => 'Refund Policy', 'enabled' => false],
                        ],
                    ],
                    [
                        'name' => 'Diamond',
                        'price' => '5500',
                        'validity' => '1 Month',
                        'students' => 'Unlimited Students',
                        'teachers' => 'Unlimited Teachers',
                        'featured' => false,
                        'features' => [
                            ['label' => 'Student Management', 'enabled' => true],
                            ['label' => 'Student Card', 'enabled' => true],
                            ['label' => 'Class & Section Management', 'enabled' => true],
                            ['label' => 'Attendance Management', 'enabled' => true],
                            ['label' => 'Daily Test Records', 'enabled' => true],
                            ['label' => 'Fee Management', 'enabled' => true],
                            ['label' => 'WhatsApp Alerts', 'enabled' => true],
                            ['label' => 'Custom Domains & Hosting', 'enabled' => true],
                            ['label' => '24/7 Support', 'enabled' => true],
                            ['label' => 'Refund Policy', 'enabled' => true],
                        ],
                    ],
                ];
            @endphp

            <div class="row g-4 justify-content-center">
                @foreach($plans as $plan)
                    <div class="col-md-6 col-lg-3">
                        <div class="plan-card h-100 {{ $plan['featured'] ? 'plan-card-featured' : '' }}">
                            @if($plan['featured'])
                                <span class="plan-badge">Most Popular</span>
                            @endif

                            <div class="plan-card-header text-center">
                                <h3 class="plan-name">{{ $plan['name'] }}</h3>
                                <div class="plan-price">
                                    <span class="plan-currency">Rs</span>
                                    <span class="plan-amount">{{ $plan['price'] }}</span>
                                </div>
                                <div class="plan-validity">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Valid for {{ $plan['validity'] }}
                                </div>
                            </div>

                            <div class="plan-card-body">
                                <ul class="plan-limits list-unstyled mb-3">
                                    <li><i class="bi bi-people-fill me-2 text-primary"></i>{{ $plan['students'] }}</li>
                                    <li><i class="bi bi-person-badge-fill me-2 text-primary"></i>{{ $plan['teachers'] }}</li>
                                </ul>

                                <ul class="plan-features list-unstyled mb-0">
                                    @foreach($plan['features'] as $feature)
                                        <li class="plan-feature {{ $feature['enabled'] ? 'enabled' : 'disabled' }}">
                                            @if($feature['enabled'])
                                                <i class="bi bi-check-circle-fill"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill"></i>
                                            @endif
                                            <span>{{ $feature['label'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="plan-card-footer text-center">
                                <a href="{{ route('register') }}" class="btn {{ $plan['featured'] ? 'btn-login' : 'btn-outline-plan' }} w-100">
                                    Get Started
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="section-padding" id="how-it-works">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">How EduTrack Works</h2>
                <p class="section-subtitle">A simple flow from school onboarding to daily classroom management.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <img src="{{ asset('images/landing/step-register.jpg') }}"
                             alt="School registration and setup" loading="lazy">
                        <div class="step-card-body">
                            <span class="step-number">1</span>
                            <h3 class="h6 fw-bold">Register School</h3>
                            <p class="text-muted small mb-0">Super admin adds schools with membership plans, login credentials, and principal details.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <img src="{{ asset('images/landing/step-enroll.jpg') }}"
                             alt="Student enrollment and class setup" loading="lazy">
                        <div class="step-card-body">
                            <span class="step-number">2</span>
                            <h3 class="h6 fw-bold">Setup & Enroll</h3>
                            <p class="text-muted small mb-0">School admin creates classes, sections, subjects, teachers, and registers students with parents.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <img src="{{ asset('images/landing/step-attendance.jpg') }}"
                             alt="Daily attendance tracking in classroom" loading="lazy">
                        <div class="step-card-body">
                            <span class="step-number">3</span>
                            <h3 class="h6 fw-bold">Mark Attendance</h3>
                            <p class="text-muted small mb-0">Select class and section, then mark each student present, absent, or on leave for the day.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <img src="{{ asset('images/landing/step-tests.jpg') }}"
                             alt="Daily test marks and academic progress" loading="lazy">
                        <div class="step-card-body">
                            <span class="step-number">4</span>
                            <h3 class="h6 fw-bold">Record Daily Tests</h3>
                            <p class="text-muted small mb-0">Enter test marks by subject and teacher. Percentages are calculated automatically for every student.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Multiple schools -->
    <section class="section-padding schools-section" id="schools">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="section-title">Multiple Schools, One Connected Platform</h2>
                    <p class="mb-4" style="color: rgba(255,255,255,0.8);">
                        Whether you run a single academy or a network of institutions, EduTrack scales with you. Each school operates independently with its own data, while super administrators oversee the entire network from a central panel.
                    </p>
                    <div class="mb-4">
                        <span class="school-pill"><i class="bi bi-check-circle-fill text-success"></i> Independent school accounts</span>
                        <span class="school-pill"><i class="bi bi-check-circle-fill text-success"></i> Membership-based features</span>
                        <span class="school-pill"><i class="bi bi-check-circle-fill text-success"></i> Centralized reporting</span>
                        <span class="school-pill"><i class="bi bi-check-circle-fill text-success"></i> Easy onboarding</span>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-login btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Connect Your School
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="schools-image">
                        <img src="{{ asset('images/landing/schools-network.jpg') }}"
                             alt="Multiple schools connected on EduTrack platform"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="section-padding" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Contact Us</h2>
                <p class="section-subtitle">Interested in bringing EduTrack to your school or network? Get in touch — we are happy to help.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="contact-card h-100">
                        <h3 class="h5 fw-bold mb-4">Get in Touch</h3>
                        <div class="contact-info-item">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <div class="fw-semibold">Email</div>
                                <a href="mailto:softwebies@gmail.com" class="text-muted text-decoration-none">softwebies@gmail.com</a>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <div class="fw-semibold">Phone</div>
                                <a href="tel:+923081312527" class="text-muted text-decoration-none">+92 308 1312527</a>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <i class="bi bi-whatsapp"></i>
                            <div>
                                <div class="fw-semibold">WhatsApp</div>
                                <a href="https://wa.me/923081312527" class="text-muted text-decoration-none">+92 308 1312527</a>
                            </div>
                        </div>
                        <div class="contact-info-item mb-0">
                            <i class="bi bi-geo-alt"></i>
                            <div>
                                <div class="fw-semibold">Office</div>
                                <span class="text-muted">Housing Colony 2, B Block, Layyah, Pakistan</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="contact-card">
                        <h3 class="h5 fw-bold mb-4">Send a Message</h3>
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="contact_name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="contact_name" name="name" placeholder="Full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="contact_email" name="email" placeholder="you@school.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_school" class="form-label">School Name</label>
                                <input type="text" class="form-control" id="contact_school" name="school" placeholder="Your school or organization">
                            </div>
                            <div class="mb-3">
                                <label for="contact_message" class="form-label">Message</label>
                                <textarea class="form-control" id="contact_message" name="message" rows="4" placeholder="How can we help you?" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-login w-100">
                                <i class="bi bi-send me-1"></i> Send Message
                            </button>
                            <div id="contactSuccess" class="alert alert-success mt-3 d-none mb-0" role="alert">
                                Thank you! We will get back to you soon.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <a href="{{ url('/') }}" class="landing-brand d-inline-flex">
                        <!-- <span class="landing-brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                        EduTrack -->

                        <img src="{{ asset('Admin/images/logo.png') }}" alt="EduTrack" width="300" height="100" class="rounded bg-white p-1">
                        
                    </a>
                    <p class="mt-2 mb-0 small">Smart school management for modern education.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('login') }}" class="me-3">Login</a>
                    <a href="#plans" class="me-3">Plans</a>
                    <a href="#" class="me-3">Privacy</a>
                    <a href="#">Terms and Condition</a>
                    <p class="mt-2 mb-0 small">&copy; {{ date('Y') }} EduTrack. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function () {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
        });

        document.getElementById('contactForm').addEventListener('submit', function (e) {
            e.preventDefault();
            document.getElementById('contactSuccess').classList.remove('d-none');
            this.reset();
        });
    </script>
</body>
</html>
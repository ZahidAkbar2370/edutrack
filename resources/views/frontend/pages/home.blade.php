@extends('frontend.layout')


@section('title', 'EduTrack — Smart School Management System')
@section('meta_description', 'EduTrack — Smart School Management System')

@section('content')

<!-- Hero -->
<section class="hero-section" id="home">
        <div class="container position-relative">
            <div class="row align-items-center mt-5">
                <div class="col-lg-6">
                    <h1 class="hero-title">
                        One Platform for <span>Every School</span> You Manage
                    </h1>
                    <p class="hero-lead">
                        EduTrack connects multiple schools on a single powerful system — manage students, attendance, daily tests, teachers, and memberships from one secure admin panel.
                    </p>
                   
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
                        <div class="hero-stat">
                            <strong>Quick Setup</strong>
                            <span>Register & start in minutes</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn btn-login btn-lg px-4 mt-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Register Now
                    </a>    

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
                        <p class="text-muted mb-0">Mark present, absent, or leave by class and section. View summaries, edit records anytime, and send WhatsApp alerts to parents on daily attendance.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-clipboard-check"></i></div>
                        <h3 class="h5 fw-bold">Daily Tests & Marks</h3>
                        <p class="text-muted mb-0">Record test scores per subject with obtained marks, totals, and automatic percentage calculation. WhatsApp alerts notify parents when daily test results are saved.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-cash-coin"></i></div>
                        <h3 class="h5 fw-bold">Fee Management</h3>
                        <p class="text-muted mb-0">Track monthly fees, fines, discounts, and payments by class. Parents receive WhatsApp alerts for unpaid or overdue fee reminders automatically.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                        <h3 class="h5 fw-bold">Student Card</h3>
                        <p class="text-muted mb-0">Generate professional student ID cards with photo, roll number, and school details. Download ready-to-print cards for every enrolled student.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-whatsapp"></i></div>
                        <h3 class="h5 fw-bold">WhatsApp Notification</h3>
                        <p class="text-muted mb-0">Keep parents informed instantly — attendance updates, daily test marks, and unpaid fee reminders are sent directly to parent WhatsApp numbers.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-layers"></i></div>
                        <h3 class="h5 fw-bold">Membership Plans</h3>
                        <p class="text-muted mb-0">Flexible plans with feature limits — attendance, daily tests, fee management, WhatsApp alerts, and student cards per tier.</p>
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
    <section class="section-padding" id="pricing">
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
                        'price' => '5000',
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
    <section class="section-padding" id="schools">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="section-title">Multiple Schools, One Connected Platform</h2>
                    <p class="mb-4">
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
                                <a href="mailto:{{ $supportEmail }}" class="text-muted text-decoration-none">{{ $supportEmail }}</a>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <div class="fw-semibold">Phone</div>
                                <a href="tel:{{ $supportPhone }}" class="text-muted text-decoration-none">{{ $supportPhone }}</a>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <i class="bi bi-whatsapp"></i>
                            <div>
                                <div class="fw-semibold">WhatsApp</div>
                                <a href="https://wa.me/{{ $supportWhatsApp }}" class="text-muted text-decoration-none">{{ $supportWhatsApp }}</a>
                            </div>
                        </div>
                        <div class="contact-info-item mb-0">
                            <i class="bi bi-geo-alt"></i>
                            <div>
                                <div class="fw-semibold">Office</div>
                                <span class="text-muted">{{ $supportAddress }}</span>
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

    <!-- Mobile App Download -->
    <section class="section-padding app-download-section" id="download-app">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="app-download-badge"><i class="bi bi-phone me-1"></i> Mobile App</span>
                    </div>
                    <h2 class="section-title text-white mb-3">Manage Your School On the Go</h2>
                    <p class="app-download-lead mb-4">
                        The EduTrack mobile app for Android and iOS is launching soon. Access attendance, daily tests, fee records, and WhatsApp notifications — anytime, anywhere.
                    </p>
                    <ul class="app-download-list list-unstyled mb-4">
                        <li><i class="bi bi-check-circle-fill"></i> Real-time attendance & test updates</li>
                        <li><i class="bi bi-check-circle-fill"></i> Fee status and payment reminders</li>
                        <li><i class="bi bi-check-circle-fill"></i> WhatsApp alerts synced with the web panel</li>
                        <li><i class="bi bi-check-circle-fill"></i> Secure login for school admins & staff</li>
                    </ul>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="app-store-btn app-store-android app-store-disabled" aria-disabled="true">
                            <i class="bi bi-google-play"></i>
                            <span>
                                <small>Get it on</small>
                                <strong>Google Play</strong>
                            </span>
                        </span>
                        <span class="app-store-btn app-store-ios app-store-disabled" aria-disabled="true">
                            <i class="bi bi-apple"></i>
                            <span>
                                <small>Download on the</small>
                                <strong>App Store</strong>
                            </span>
                        </span>
                    </div>
                    <p class="app-coming-soon-note mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i> Android & iOS apps are under development. Stay tuned for the official release.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="app-download-visual">
                        <div class="app-download-image-wrap">
                            <img src="{{ asset('images/landing/mobile-app-preview.png') }}"
                                 alt="EduTrack mobile app for Android and iOS"
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
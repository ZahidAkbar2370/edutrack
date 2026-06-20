@extends('frontend.layout')

@section('title', 'Privacy Policy')
@section('meta_description', 'EduTrack Privacy Policy — how we collect, use, and protect school and student data.')

@section('main_class', 'legal-page')

@section('content')
<div class="container">
<div class="legal-card">
    <div class="legal-header">
        <h1 class="legal-title">Privacy Policy</h1>
        <p class="legal-updated text-muted mb-0">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="legal-body">
        <p>
            Welcome to <strong>EduTrack</strong> (“we”, “our”, “us”). This Privacy Policy explains how we collect, use, store, and protect information when you use our school management platform, website, and related services (collectively, the “Service”).
        </p>
        <p>
            By registering a school, creating an account, or using EduTrack, you agree to the practices described in this Privacy Policy.
        </p>

        <h2>1. Who We Are</h2>
        <p>
            EduTrack is a multi-school management system operated for educational institutions. The platform is managed through a secure <strong>Super Admin</strong> panel that controls school registration, membership plans, and platform-wide settings. Each registered school receives its own secure <strong>School Admin</strong> account to manage students, teachers, attendance, daily tests, fees, and related records.
        </p>

        <h2>2. Information We Collect</h2>
        <p>We may collect the following types of information:</p>
        <ul>
            <li><strong>School information:</strong> school name, address, city, phone, email, and principal details.</li>
            <li><strong>User account information:</strong> name, email, password (stored in encrypted/hashed form), role, and membership details.</li>
            <li><strong>Student & parent information:</strong> student names, roll numbers, class/section, photos, admission dates, parent names, phone numbers, emails, and addresses.</li>
            <li><strong>Academic & operational data:</strong> attendance records, daily test marks, fee records, documents, and transaction history.</li>
            <li><strong>Communication data:</strong> WhatsApp notification logs and contact form messages submitted through our website.</li>
            <li><strong>Technical data:</strong> IP address, browser type, device information, login sessions, and usage logs for security and performance.</li>
        </ul>

        <h2>3. How We Use Your Information</h2>
        <p>We use collected information to:</p>
        <ul>
            <li>Provide and maintain the EduTrack platform and its features.</li>
            <li>Manage school accounts, memberships, and subscription expiry.</li>
            <li>Enable attendance, daily tests, fee management, student cards, and reporting.</li>
            <li>Send WhatsApp notifications to parents (attendance, daily tests, unpaid fees) when enabled by the school’s plan.</li>
            <li>Improve security, prevent fraud, and troubleshoot technical issues.</li>
            <li>Respond to support requests and contact form inquiries.</li>
            <li>Comply with applicable legal obligations.</li>
        </ul>

        <h2>4. Super Admin & Role-Based Access</h2>
        <p>
            EduTrack uses strict role-based access control. <strong>Super Admin</strong> accounts have platform-level access to manage schools and memberships. <strong>School Admin</strong> accounts can access only their own school’s data. We implement authentication, email verification, membership expiry checks, and access middleware to help keep accounts secure.
        </p>
        <p>
            Schools are responsible for keeping their login credentials confidential and for actions performed under their accounts.
        </p>

        <h2>5. Data Sharing & Third Parties</h2>
        <p>
            We do not sell your personal data. We may share limited information only when necessary:
        </p>
        <ul>
            <li>With service providers that help us host, maintain, or deliver the Service (e.g. hosting, email, WhatsApp API providers).</li>
            <li>When required by law, court order, or government request.</li>
            <li>To protect the rights, safety, and security of EduTrack, our users, or the public.</li>
        </ul>
        <p>
            <strong>Third-party services</strong> (such as WhatsApp, payment processors, hosting providers, or external APIs) operate under their own privacy policies. EduTrack is <strong>not responsible</strong> for the privacy practices, data handling, outages, or misconduct of any third-party service.
        </p>

        <h2>6. Security</h2>
        <p>
            We take reasonable technical and organizational measures to protect data, including secure authentication, role restrictions, and server-side access controls. However, no method of transmission or storage is 100% secure.
        </p>
        <div class="legal-notice">
            <p class="mb-0">
                <strong>Important:</strong> EduTrack shall <strong>not be held responsible</strong> for any unauthorized access, data loss, service interruption, or misuse caused by <strong>hackers, cyberattacks, malware, phishing, or any external security breach</strong> beyond our reasonable control. Users should also protect their devices, passwords, and network access.
            </p>
        </div>

        <h2>7. Data Retention</h2>
        <p>
            We retain data for as long as your school account is active or as needed to provide the Service, comply with legal obligations, resolve disputes, and enforce our agreements. Deleted records may remain in backups for a limited period before permanent removal.
        </p>

        <h2>8. Your Responsibilities</h2>
        <p>Schools and users agree to:</p>
        <ul>
            <li>Provide accurate information and update it when necessary.</li>
            <li>Use EduTrack only for lawful educational and administrative purposes.</li>
            <li>Obtain necessary consent from parents/guardians before storing or messaging student/parent contact data.</li>
            <li>Not upload unlawful, offensive, or unauthorized content.</li>
        </ul>

        <h2>9. Children & Student Data</h2>
        <p>
            EduTrack is designed for use by schools and authorized staff. Student data is entered and managed by the school. Schools are responsible for complying with applicable laws regarding student and minor data protection in their region.
        </p>

        <h2>10. Cookies & Sessions</h2>
        <p>
            We use session cookies and similar technologies to keep you logged in, remember preferences, and maintain platform security. You can control cookies through your browser settings, but disabling them may affect functionality.
        </p>

        <h2>11. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated “Last updated” date. Continued use of the Service after changes means you accept the revised policy.
        </p>

        <h2>12. Contact Us</h2>
        <p>If you have questions about this Privacy Policy or your data, contact us:</p>
        <ul class="list-unstyled">
            <li><i class="bi bi-envelope me-2 text-primary"></i> <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
            <li><i class="bi bi-telephone me-2 text-primary"></i> <a href="tel:{{ $supportPhone }}">{{ $supportPhone }}</a></li>
            <li><i class="bi bi-geo-alt me-2 text-primary"></i> {{ $supportAddress }}</li>
        </ul>
    </div>
    <div class="mb-5"></div>

</div>
</div>
@endsection

@extends('frontend.layout')

@section('title', 'Terms & Conditions')
@section('meta_description', 'EduTrack Terms and Conditions — rules for using our school management platform.')

@section('main_class', 'legal-page')

@section('content')
<div class="container">
<div class="legal-card">
    <div class="legal-header">
        <h1 class="legal-title">Terms & Conditions</h1>
        <p class="legal-updated text-muted mb-0">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="legal-body">
        <p>
            These Terms & Conditions (“Terms”) govern your access to and use of <strong>EduTrack</strong>, including our website, web application, mobile applications (when available), and all related services (the “Service”). By using EduTrack, you agree to these Terms.
        </p>
        <p>
            If you do not agree, please do not use the Service.
        </p>

        <h2>1. Definitions</h2>
        <ul>
            <li><strong>Platform / EduTrack / We / Us:</strong> The EduTrack school management system and its operators.</li>
            <li><strong>Super Admin:</strong> Authorized platform administrator with access to manage schools, memberships, and system settings.</li>
            <li><strong>School Admin:</strong> Authorized user managing a registered school’s data and operations.</li>
            <li><strong>User:</strong> Any person who accesses or uses the Service under a valid account.</li>
            <li><strong>School:</strong> An educational institution registered on EduTrack.</li>
        </ul>

        <h2>2. Eligibility & Account Registration</h2>
        <p>
            Schools must be registered by the Super Admin or through an approved registration process. Each school receives login credentials for its School Admin account. You must provide accurate and complete information and keep your account details up to date.
        </p>
        <p>
            You are responsible for all activity under your account. Notify us immediately if you suspect unauthorized access.
        </p>

        <h2>3. Membership Plans & Payments</h2>
        <p>
            EduTrack offers membership plans (e.g. Basic, Standard, Premium, Diamond) with different feature limits and expiry dates. Features such as attendance, daily tests, fee management, student cards, and WhatsApp notifications depend on the active plan assigned to your school.
        </p>
        <ul>
            <li>Membership fees, validity, and plan features are defined at the time of purchase or upgrade.</li>
            <li>Expired memberships may restrict access until renewal.</li>
            <li>Payment records and transaction history are maintained for billing and support purposes.</li>
            <li>Refund eligibility depends on your selected plan and is stated in the plan details where applicable.</li>
        </ul>

        <h2>4. Acceptable Use</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use EduTrack for any unlawful, fraudulent, or harmful purpose.</li>
            <li>Upload false, misleading, or unauthorized student, parent, or staff information.</li>
            <li>Attempt to hack, reverse engineer, scrape, overload, or disrupt the Service.</li>
            <li>Access data belonging to other schools without authorization.</li>
            <li>Share login credentials with unauthorized persons.</li>
            <li>Use WhatsApp or notification features to send spam, harassment, or illegal messages.</li>
        </ul>
        <p>
            We reserve the right to suspend or terminate accounts that violate these Terms.
        </p>

        <h2>5. School Responsibilities</h2>
        <p>Schools using EduTrack are solely responsible for:</p>
        <ul>
            <li>The accuracy of data entered into the system (students, parents, teachers, fees, attendance, etc.).</li>
            <li>Obtaining consent from parents/guardians for storing contact details and sending WhatsApp alerts.</li>
            <li>Training staff on proper and lawful use of the platform.</li>
            <li>Compliance with local education, privacy, and communication laws.</li>
        </ul>

        <h2>6. WhatsApp Notifications</h2>
        <p>
            Where enabled by membership plan, EduTrack may send WhatsApp messages for attendance updates, daily test results, and unpaid fee reminders. WhatsApp delivery depends on third-party services, parent phone numbers, network availability, and school configuration.
        </p>
        <p>
            EduTrack does not guarantee message delivery times or success rates. We are not responsible for failures caused by incorrect numbers, blocked contacts, WhatsApp service outages, or third-party API limitations.
        </p>

        <h2>7. Intellectual Property</h2>
        <p>
            EduTrack, including its software, design, branding, logos, and content, is owned by us or our licensors. You may not copy, modify, distribute, or create derivative works without written permission.
        </p>
        <p>
            Schools retain ownership of the data they upload. You grant EduTrack a limited license to host, process, and display that data solely to provide the Service.
        </p>

        <h2>8. Service Availability</h2>
        <p>
            We strive to keep EduTrack available and secure but do not guarantee uninterrupted or error-free operation. Maintenance, upgrades, server issues, or external factors may cause temporary downtime.
        </p>

        <h2>9. Limitation of Liability</h2>
        <div class="legal-notice">
            <p>
                To the maximum extent permitted by law, EduTrack shall <strong>not be liable</strong> for any indirect, incidental, special, consequential, or punitive damages, including loss of data, revenue, reputation, or business opportunity.
            </p>
            <p class="mb-0">
                EduTrack shall <strong>not be responsible</strong> for any loss, damage, unauthorized access, or misconduct caused by <strong>third parties, hackers, cyberattacks, malware, phishing, hosting failures, WhatsApp/API providers, internet outages, or user negligence</strong>, even if we have been advised of the possibility of such damages.
            </p>
        </div>

        <h2>10. Disclaimer of Warranties</h2>
        <p>
            The Service is provided on an <strong>“as is”</strong> and <strong>“as available”</strong> basis without warranties of any kind, whether express or implied, including fitness for a particular purpose, accuracy of reports, or non-infringement.
        </p>

        <h2>11. Indemnification</h2>
        <p>
            You agree to indemnify and hold EduTrack harmless from any claims, damages, losses, or expenses (including legal fees) arising from your misuse of the Service, violation of these Terms, unlawful data handling, or messages sent through your account.
        </p>

        <h2>12. Termination</h2>
        <p>
            We may suspend or terminate access to the Service if you breach these Terms, fail to renew membership, or if continued service poses security or legal risk. Schools may request account closure through official support channels.
        </p>

        <h2>13. Changes to Terms</h2>
        <p>
            We may update these Terms at any time. Updated Terms will be posted on this page with a revised date. Continued use after changes constitutes acceptance of the new Terms.
        </p>

        <h2>14. Governing Law</h2>
        <p>
            These Terms shall be governed by the laws of Pakistan, without regard to conflict of law principles. Disputes shall be subject to the jurisdiction of competent courts in Pakistan, unless otherwise required by mandatory local law.
        </p>

        <h2>15. Contact Information</h2>
        <p>For questions about these Terms, contact us:</p>
        <ul class="list-unstyled">
            <li><i class="bi bi-envelope me-2 text-primary"></i> <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
            <li><i class="bi bi-telephone me-2 text-primary"></i> <a href="tel:{{ $supportPhone }}">{{ $supportPhone }}</a></li>
            <li><i class="bi bi-whatsapp me-2 text-primary"></i> <a href="https://wa.me/{{ $supportWhatsApp }}" target="_blank" rel="noopener">WhatsApp: {{ $supportWhatsApp }}</a></li>
            <li><i class="bi bi-geo-alt me-2 text-primary"></i> {{ $supportAddress }}</li>
        </ul>
    </div>

</div>
</div>
@endsection

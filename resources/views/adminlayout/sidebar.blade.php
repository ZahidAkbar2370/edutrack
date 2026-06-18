@php

use Illuminate\Support\Facades\Auth;

$membership = \App\Models\Membership::find(Auth::user()->membership_id);
@endphp

<aside class="admin-sidebar text-white" id="admin-sidebar">

    <div class="d-flex align-items-center gap-2 p-3 border-bottom border-secondary border-opacity-25">
    <img src="{{ asset('Admin/images/logo.png') }}" alt="EduTrack" width="290" height="80" class="rounded bg-white p-1">
        <!-- <div class="logo-text flex-grow-1">
            <div class="fw-bold">EduTrack</div>
            <span>{{ Auth::user()->role == 'super-admin' ? 'Super Admin' : 'School Admin' }}</span>
        </div> -->
        <button type="button" class="btn btn-sm btn-outline-light d-lg-none" id="sidebar-close" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="nav flex-column p-2 pb-4">


        @if(Auth::user()->role == 'super-admin')
        <a class="nav-link {{ request()->is('admin-dashboard') || request()->is('admin-dashboard') ? 'active' : '' }}" href="{{ url('admin-dashboard') }}">
            <i class="bi bi-grid me-2"></i> Dashboard
        </a>

        <!-- Super Admin : Memberships -->
        <a class="nav-link {{ request()->is('membership*') ? 'active' : '' }}" href="{{ url('membership') }}">
            <i class="bi bi-layers me-2"></i> Memberships
        </a>

        <!-- Super Admin : Schools -->
        <a class="nav-link d-flex align-items-center {{ request()->is('school*') ? '' : 'collapsed' }}"
            data-bs-toggle="collapse" href="#schoolSubmenu" role="button"
            aria-expanded="{{ request()->is('school*') ? 'true' : 'false' }}">
            <i class="bi bi-building me-2"></i> Schools
            <i class="bi bi-chevron-down small ms-auto"></i>
        </a>
        <div class="collapse submenu {{ request()->is('school*') ? 'show' : '' }}" id="schoolSubmenu">
            <a class="nav-link {{ request()->is('school*') && ! request()->is('school/create') ? 'active' : '' }}" href="{{ url('school') }}">School List</a>
            <a class="nav-link {{ request()->is('school/create') ? 'active' : '' }}" href="{{ url('/school/create') }}">Register School</a>
        </div>
        @endif

        @if(Auth::user()->role == 'school-admin')

        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
            <i class="bi bi-grid me-2"></i> Dashboard
        </a>

        <!-- School : Students -->
        <a class="nav-link {{ request()->is('student*') ? 'active' : '' }}" href="{{ url('student') }}">
            <i class="bi bi-people me-2"></i> Students
        </a>

        @if($membership->allowed_attendance)
        <!-- School : Attendance -->
        <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="{{ url('attendance') }}">
            <i class="bi bi-calendar-check me-2"></i> Attendance
        </a>
        @endif

        @if($membership->allowed_daily_test)
        <!-- School : Daily Test -->
        <a class="nav-link {{ request()->is('daily-test*') ? 'active' : '' }}" href="{{ url('daily-test') }}">
            <i class="bi bi-clipboard-check me-2"></i> Daily Test
        </a>
        @endif

        @if($membership->allowed_fee_management)
        <a class="nav-link {{ request()->is('fee-management*') ? 'active' : '' }}" href="{{ url('fee-management') }}">
            <i class="bi bi-cash-coin me-2"></i> Fee Management
        </a>
        @endif

        <!-- <a class="nav-link {{ request()->is('general*') ? 'active' : '' }}" href="{{ url('class') }}">
            <i class="bi bi-gear me-2"></i> Settings
        </a> -->

        <a class="nav-link d-flex align-items-center {{ request()->is('class*') || request()->is('section*') || request()->is('teacher*') ? '' : 'collapsed' }}"
            data-bs-toggle="collapse"
            href="#settingsSubmenu"
            role="button"
            aria-expanded="{{ request()->is('class*') || request()->is('section*') || request()->is('teacher*') ? 'true' : 'false' }}">

            <i class="bi bi-gear me-2"></i> Settings
            <i class="bi bi-chevron-down small ms-auto"></i>
        </a>

        <div class="collapse submenu {{ request()->is('class*') || request()->is('section*') || request()->is('subject*') || request()->is('teacher*') ? 'show' : '' }}"
            id="settingsSubmenu">

            <a class="nav-link {{ request()->is('class*') ? 'active' : '' }}"
                href="{{ url('class') }}">
                Classes
            </a>

            <a class="nav-link {{ request()->is('section*') ? 'active' : '' }}"
                href="{{ url('section') }}">
                Sections
            </a>

            <a class="nav-link {{ request()->is('subject*') ? 'active' : '' }}"
                href="{{ url('subject') }}">
                Subjects
            </a>

            <a class="nav-link {{ request()->is('teacher*') ? 'active' : '' }}"
                href="{{ url('teacher') }}">
                Teachers
            </a>

        </div>

        
        
        @endif
        
        <hr class=" border-opacity-25">

        @if(Auth::user()->role == 'super-admin')
            <a class="nav-link {{ request()->is('admin-profile') ? 'active' : '' }}" href="{{ url('admin-profile') }}">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>
        @endif

        @if(Auth::user()->role == 'school-admin')
            <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ url('profile') }}">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>
        @endif

        <a class="nav-link {{ request()->is('change-password') ? 'active' : '' }}" href="{{ url('change-password') }}">
            <i class="bi bi-key me-2"></i> Change Password
        </a>

        @if(Auth::user()->role == 'school-admin')
        <a class="nav-link {{ request()->is('transaction-history') ? 'active' : '' }}" href="{{ url('transaction-history') }}">
            <i class="bi bi-cash-coin me-2"></i> Transaction History
        </a>

        <a class="nav-link {{ request()->is('pricing') ? 'active' : '' }}" href="{{ url('pricing') }}">
            <i class="bi bi-stars me-2"></i> Pricing Plan
        </a>
        @endif

        <hr class=" border-opacity-25">

        <a class="nav-link text-danger" href="{{ url('logout') }}" data-confirm-action
            data-confirm-title="Logout"
            data-confirm-message="Are you sure you want to logout?"
            data-confirm-yes="Yes, Logout"
            data-confirm-yes-class="btn-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>

    </nav>

</aside>
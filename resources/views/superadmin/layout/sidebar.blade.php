@php

use Illuminate\Support\Facades\Auth;

$membership = \App\Models\Membership::find(Auth::user()->membership_id);
@endphp

<aside class="admin-sidebar text-white" id="admin-sidebar">

    <div class="d-flex align-items-center gap-2 p-3 border-bottom border-secondary border-opacity-25">
    <a href="{{ url('/') }}"> <img src="{{ asset('images/edutrack-logo.png') }}" alt="EduTrack" width="230" height="80" class="rounded bg-white p-1"></a>
        <!-- <div class="logo-text flex-grow-1">
            <div class="fw-bold">EduTrack</div>
            <span>{{ Auth::user()->role == 'super-admin' ? 'Super Admin' : 'School Admin' }}</span>
        </div> -->
        <button type="button" class="btn btn-sm btn-outline-light d-lg-none" id="sidebar-close" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="nav flex-column p-2 pb-4">

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
        
        <hr class=" border-opacity-25">

            <a class="nav-link {{ request()->is('admin-profile') ? 'active' : '' }}" href="{{ url('admin-profile') }}">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>
        




        <!-- <hr class=" border-opacity-25"> -->

        <a class="nav-link text-danger" href="{{ url('logout') }}" data-confirm-action
            data-confirm-title="Logout"
            data-confirm-message="Are you sure you want to logout?"
            data-confirm-yes="Yes, Logout"
            data-confirm-yes-class="btn-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>

    </nav>

</aside>
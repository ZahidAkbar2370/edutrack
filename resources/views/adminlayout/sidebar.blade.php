<aside class="admin-sidebar text-white" id="admin-sidebar">

    <div class="d-flex align-items-center gap-2 p-3 border-bottom border-secondary border-opacity-25">
        <img src="{{ asset('Admin/images/logo.png') }}" alt="EduTrack" width="40" height="40" class="rounded bg-white p-1">
        <div class="logo-text flex-grow-1">
            <div class="fw-bold">EduTrack</div>
            <span>{{ Auth::user()->role == 'super-admin' ? 'Super Admin' : 'School Admin' }}</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-light d-lg-none" id="sidebar-close" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="nav flex-column p-2 pb-4">
        
        
        @if(Auth::user()->role == 'super-admin')
        <a class="nav-link {{ request()->is('dashboard') || request()->is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
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
            <a class="nav-link {{ request()->is('school') && ! request()->is('school/create') ? 'active' : '' }}" href="{{ url('school') }}">School List</a>
            <a class="nav-link {{ request()->is('school/create') ? 'active' : '' }}" href="{{ url('/school/create') }}">Register School</a>
            <!-- <a class="nav-link" href="#">Import</a> -->
        </div>
        @endif

        <!-- <a class="nav-link {{ request()->is('class*') ? 'active' : '' }}" href="{{ url('class') }}">
            <i class="bi bi-journal-bookmark me-2"></i> Classes
        </a>

        <a class="nav-link {{ request()->is('section*') ? 'active' : '' }}" href="{{ url('section') }}">
            <i class="bi bi-diagram-3 me-2"></i> Sections
        </a> -->

        <!-- School : Teachers -->
        <!-- <a class="nav-link {{ request()->is('teacher*') ? 'active' : '' }}" href="{{ url('teacher') }}">
            <i class="bi bi-person-badge me-2"></i> Teachers
        </a> -->

        @if(Auth::user()->role == 'school-admin')

        <!-- School : Students -->
        <a class="nav-link {{ request()->is('student*') ? 'active' : '' }}" href="{{ url('student') }}">
            <i class="bi bi-people me-2"></i> Students
        </a>

        <!-- School : Attendance -->
        <a class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}" href="{{ url('attendance') }}">
            <i class="bi bi-calendar-check me-2"></i> Attendance
        </a>

        <!-- School : Daily Test -->
        <a class="nav-link {{ request()->is('daily-test*') ? 'active' : '' }}" href="{{ url('daily-test') }}">
            <i class="bi bi-clipboard-check me-2"></i> Daily Test
        </a>

        <a class="nav-link d-flex align-items-center {{ (request()->is('class*') || request()->is('section*') || request()->is('subject*')) ? '' : 'collapsed' }}"
           data-bs-toggle="collapse" href="#settingsSubmenu" role="button"
           aria-expanded="{{ (request()->is('class*') || request()->is('section*') || request()->is('subject*')) ? 'true' : 'false' }}">
            <i class="bi bi-gear me-2"></i> Settings
            <i class="bi bi-chevron-down small ms-auto"></i>
        </a>


        <div class="collapse submenu {{ request()->is('class*') || request()->is('section*') || request()->is('subject*') ? 'show' : '' }}" id="settingsSubmenu">

        <a class="nav-link {{ (request()->is('teacher') && ! request()->is('teacher/create') || request()->is('teacher/edit*')) ? 'active' : '' }}" href="{{ url('teacher') }}">Teacher List</a>


            <a class="nav-link {{ (request()->is('class') && ! request()->is('class/create') || request()->is('class/edit*')) ? 'active' : '' }}" href="{{ url('class') }}">Class List</a>

            <a class="nav-link {{ (request()->is('section') && ! request()->is('section/create') || request()->is('section/edit*')) ? 'active' : '' }}" href="{{ url('section') }}">Section List</a>

            <a class="nav-link {{ (request()->is('subject') && ! request()->is('subject/create') || request()->is('subject/edit*')) ? 'active' : '' }}" href="{{ url('subject') }}">Subject List</a>

        </div>
        @endif

    </nav>

</aside>

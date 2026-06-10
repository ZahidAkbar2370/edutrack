<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - EduTrack</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Admin/css/style.css') }}">

    @stack('styles')
</head>
<body class="bg-light">

<div class="sidebar-overlay" id="sidebar-overlay"></div>

@include('adminlayout.sidebar')

<div class="admin-main d-flex flex-column">

    <header class="navbar navbar-expand bg-white border-bottom sticky-top px-3 py-2">
        <button type="button" class="btn btn-outline-secondary d-lg-none me-2" id="sidebar-toggle" aria-label="Open menu">
            <i class="bi bi-list fs-5 sidebar-icon-open"></i>
            <i class="bi bi-x-lg fs-5 sidebar-icon-close d-none"></i>
        </button>

        <form class="flex-grow-1 me-3" role="search">
            <div class="input-group">

            @php
                $user = \Illuminate\Support\Facades\Auth::user();
                $school = \App\Models\School::find($user->school_id);
            @endphp

                <h5 class="mb-0" style="color: #D81B60;">{{ $school->school_name ?? 'N/A' }} - <span class="text-muted small">{{ $school->city ?? 'N/A' }}</span></h5>
                <!-- <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span> -->
                <!-- <input type="search" class="form-control border-start-0 w-25" placeholder="Search..." aria-label="Search"> -->
            </div>
        </form>

        <ul class="navbar-nav flex-row align-items-center gap-1">
            <!-- <li class="nav-item d-none d-md-block">
                <a class="nav-link position-relative px-2" href="#" title="Notifications">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">3</span>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a class="nav-link position-relative px-2" href="#" title="Messages">
                    <i class="bi bi-envelope fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">1</span>
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link px-2" href="{{ url('logout') }}" title="Logout" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="bi bi-box-arrow-right fs-4 text-danger"></i>
                </a>
            </li>
        </ul>
    </header>

    <main class="flex-grow-1 p-3 p-md-4">
        @yield('content')
    </main>

    <footer class="border-top bg-white py-3 text-center text-muted small">
        &copy; {{ date('Y') }} EduTrack Powered by <a href="#" target="_blank">Softwebies</a>. All rights reserved.
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('Admin/js/main.js') }}"></script>
<script src="{{ asset('js/table-pagination.js') }}"></script>
@stack('scripts')

</body>
</html>

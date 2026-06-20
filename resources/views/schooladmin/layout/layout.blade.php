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

    @include('schooladmin.layout.sidebar')

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

                    @if(!empty($school->school_name))
                        <h5 class="mb-0" style="color: #D81B60;">{{ $school->school_name ?? 'N/A' }} - <span class="text-muted small">{{ $school->city ?? 'N/A' }}</span></h5>
                    @endif
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



                @if($user->role != 'super-admin')

                <li class="nav-item dropdown btn btn-primary btn-sm d-none d-md-block">
                    <a class="nav-link position-relative px-2 dropdown-toggle text-white fw-bold"
                        href="#"
                        id="notificationDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        title="Membership Information">
                        <i class="bi bi-layers fs-5 fw-bold me-1"></i> Membership
                        
                    </a>

                    <div class="dropdown-menu dropdown-menu-end shadow border-0 notification-dropdown p-0" aria-labelledby="notificationDropdown">
                        <div class="notification-dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <h6 class="mb-0 fw-semibold">Membership</h6>
                            
                        </div>

                        <div class="notification-dropdown-body">


                        <p 
                                class="dropdown-item notification-item d-flex align-items-start gap-2 py-3">
                                <span class="notification-icon text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-alert"></i>
                                </span>
                                <span class="flex-grow-1 min-width-0">
                                    <span class="d-flex justify-content-between align-items-start gap-2">
                                        <strong class="small d-block text-truncate text-success">Membership Plan</strong>
                                    </span>




                                    <span class="text-muted small d-block notification-message">{{\App\Models\Membership::find(Auth::user()->membership_id)->membership_name ?? 'Unknown'}}</span>
                                </span>
</p>
                            
                        
                        <p 
                                class="dropdown-item notification-item d-flex align-items-start gap-2 py-3">
                                <span class="notification-icon text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-alert"></i>
                                </span>
                                <span class="flex-grow-1 min-width-0">
                                    <span class="d-flex justify-content-between align-items-start gap-2">
                                        <strong class="small d-block text-truncate text-danger">Membership Expiry Date</strong>
                                    </span>

                                    
                                    @php
    $daysRemaining = \Carbon\Carbon::now()->diffInDays(
        \Carbon\Carbon::parse(Auth::user()->membership_expiry_date),
        false
    );
@endphp


                                    <span class="text-muted small d-block notification-message">{{ \Carbon\Carbon::parse(Auth::user()->membership_expiry_date)->format('Y-m-d') }}




                                    (
                                    @if((int) $daysRemaining === 1)
                                    {{ (int) $daysRemaining }} day remaining
                                    @elseif((int) $daysRemaining > 0)
                                    {{ (int) $daysRemaining }} days remaining
                                @elseif((int) $daysRemaining == 0)
                                    Expires today
                                @else
                                    Expired {{ abs($daysRemaining) }} days ago
                                @endif
                                    )

                                    </span>
                                </span>
</p>

                        </div>

                    </div>
                </li>
                @endif

                <!-- <li class="nav-item d-none d-md-block btn btn-secondary btn-sm">
                <a class="nav-link position-relative px-2 text-white fw-bold" href="{{ url('profile') }}" title="Profile">
                    <i class="bi bi-person-circle fs-5 fw-bold me-1"></i> Profile
                </a>
            </li> -->

                <!-- <li class="nav-item">
                <a class="nav-link position-relative px-2" href="#" title="Messages">
                    <button class="btn btn-primary btn-sm">Expiry Date: {{ \Carbon\Carbon::parse(Auth::user()->membership_expiry_date)->format('Y-m-d') }}</button>
                </a>
            </li> -->

            <li class="nav-item btn btn-danger btn-sm d-none d-md-block">
                <a class="nav-link position-relative px-2 text-white fw-bold" href="{{ url('logout') }}" title="Logout"
                
                data-confirm-action
                        data-confirm-title="Logout"
                        data-confirm-message="Are you sure you want to logout?"
                        data-confirm-yes="Yes, Logout"
                        data-confirm-yes-class="btn-danger"
                >
                    <i class="bi bi-box-arrow-right fs-5 fw-bold me-1"></i> Logout
                </a>
            </li>

                <li class="nav-item btn-sm d-md-none d-lg-none">
                    <a class="nav-link px-2 fw-bold" href="{{ url('logout') }}" title="Logout"
                        data-confirm-action
                        data-confirm-title="Logout"
                        data-confirm-message="Are you sure you want to logout?"
                        data-confirm-yes="Yes, Logout"
                        data-confirm-yes-class="btn-danger">
                        <i class="bi bi-box-arrow-right fs-4 me-1"></i>
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

    @include('common.confirm_alert_model')
    @include('common.confirm_alert_javascript')
    @stack('scripts')

</body>

</html>
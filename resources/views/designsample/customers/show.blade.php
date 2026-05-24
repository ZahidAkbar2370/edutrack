@extends('admin.layout.layout')

@section('title', 'Customer Detail')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <a href="{{ url('/admin/customers') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Customers
        </a>
        <h1 class="h3 mb-1 fw-bold">Zahid Akbar</h1>
        <p class="text-muted mb-0">Student ID: STU-{{ $id ?? 1 }} &bull; Class 10-A</p>
    </div>
    <a href="#" class="btn btn-outline-secondary">Edit</a>
</div>

{{-- Overview + Student card --}}
<div class="row g-3 mb-4">
    <div class="col-lg-9">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Overview</h2>
                <p class="text-muted small mb-0">Summary of tests and attendance</p>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([
                        ['v'=>48,'l'=>'Total Tests','c'=>'primary'],
                        ['v'=>120,'l'=>'Total Attendance','c'=>'secondary'],
                        ['v'=>98,'l'=>'Present','c'=>'success'],
                        ['v'=>12,'l'=>'Absent','c'=>'warning'],
                        ['v'=>10,'l'=>'Leave','c'=>'info'],
                    ] as $stat)
                    <div class="col">
                        <div class="border rounded p-2 text-center bg-{{ $stat['c'] }}-subtle">
                            <div class="fs-4 fw-bold text-{{ $stat['c'] }}">{{ $stat['v'] }}</div>
                            <div class="small text-muted">{{ $stat['l'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h2 class="h6 mb-0 fw-semibold">Student Card</h2>
            </div>
            <div class="card-body">
                <div id="student-card" class="student-card mb-3">
                    <div class="student-card-header d-flex align-items-center gap-3 p-3">
                        <img src="{{ asset('Admin/images/logo.png') }}" alt="EduTrack" width="36" height="36" class="rounded bg-white p-1">
                        <div>
                            <strong class="d-block">EduTrack</strong>
                            <small class="opacity-75">Student ID Card</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 p-3">
                        <div class="student-card-photo d-flex align-items-center justify-content-center rounded">Z</div>
                        <div class="small">
                            <h3 class="h6 fw-bold mb-2">Zahid Akbar</h3>
                            <p class="mb-1"><strong>ID:</strong> STU-{{ $id ?? 1 }}</p>
                            <p class="mb-1"><strong>Class:</strong> 10-A</p>
                            <p class="mb-0"><strong>Roll:</strong> 1024</p>
                        </div>
                    </div>
                    <div class="text-center small text-muted py-2 border-top bg-light">Session 2025–2026</div>
                </div>
                <div class="d-flex flex-wrap gap-1">
                    <button type="button" class="btn btn-primary btn-sm flex-fill" id="btn-download-png">PNG</button>
                    <button type="button" class="btn btn-danger btn-sm flex-fill" id="btn-download-pdf">PDF</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" id="btn-print-card">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Customer detail --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-light"><h2 class="h6 mb-0 fw-semibold">Customer Detail</h2></div>
    <div class="card-body">
        <div class="row g-3">
            @foreach([
                ['Full Name','Zahid Akbar'],['Email','zahid@example.com'],['Phone','+92 300 1234567'],
                ['Date of Birth','15 Mar 2010'],['Class / Section','10-A'],['Roll Number','1024'],
                ['Address','House 12, Street 5, Lahore'],['Joined','Jan 15, 2025'],
            ] as $item)
            <div class="col-md-6 col-lg-4">
                <div class="small text-muted text-uppercase fw-semibold">{{ $item[0] }}</div>
                <div>{{ $item[1] }}</div>
            </div>
            @endforeach
            <div class="col-md-6 col-lg-4">
                <div class="small text-muted text-uppercase fw-semibold">Status</div>
                <span class="badge text-bg-success">Active</span>
            </div>
        </div>
    </div>
</div>

{{-- Parent detail --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-light"><h2 class="h6 mb-0 fw-semibold">Parent Detail</h2></div>
    <div class="card-body">
        <div class="row g-3">
            @foreach([
                ['Father Name','Muhammad Akbar'],['Mother Name','Fatima Akbar'],
                ['Guardian Phone','+92 321 5558899'],['Guardian Email','akbar.parent@example.com'],
                ['Relation','Father'],['Emergency Contact','+92 300 9998877'],
            ] as $item)
            <div class="col-md-6 col-lg-4">
                <div class="small text-muted text-uppercase fw-semibold">{{ $item[0] }}</div>
                <div>{{ $item[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Attendance history --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h2 class="h6 mb-0 fw-semibold">Daily Attendance History</h2>
        <a href="#" class="btn btn-outline-secondary btn-sm">Complete History</a>
    </div>
    <div class="card-body">
        <div class="table-scroll-wrap">
            <p class="table-scroll-hint alert alert-light border py-2 mb-2 text-center small">
                <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all columns
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small text-muted">DATE</th>
                            <th class="small text-muted">DAY</th>
                            <th class="small text-muted">CHECK IN</th>
                            <th class="small text-muted">CHECK OUT</th>
                            <th class="small text-muted">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>May 20, 2026</td><td>Tuesday</td><td>08:05 AM</td><td>02:10 PM</td><td><span class="badge text-bg-success">Present</span></td></tr>
                        <tr><td>May 19, 2026</td><td>Monday</td><td>08:12 AM</td><td>02:05 PM</td><td><span class="badge text-bg-success">Present</span></td></tr>
                        <tr><td>May 18, 2026</td><td>Sunday</td><td>—</td><td>—</td><td><span class="badge text-bg-info">Leave</span></td></tr>
                        <tr><td>May 17, 2026</td><td>Saturday</td><td>—</td><td>—</td><td><span class="badge text-bg-danger">Absent</span></td></tr>
                        <tr><td>May 16, 2026</td><td>Friday</td><td>08:00 AM</td><td>02:00 PM</td><td><span class="badge text-bg-success">Present</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Test history --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h2 class="h6 mb-0 fw-semibold">Daily Test History</h2>
        <a href="#" class="btn btn-outline-secondary btn-sm">Complete History</a>
    </div>
    <div class="card-body">
        <div class="table-scroll-wrap">
            <p class="table-scroll-hint alert alert-light border py-2 mb-2 text-center small">
                <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all columns
            </p>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small text-muted">DATE</th>
                            <th class="small text-muted">SUBJECT</th>
                            <th class="small text-muted">TEST NAME</th>
                            <th class="small text-muted">MARKS</th>
                            <th class="small text-muted">TOTAL</th>
                            <th class="small text-muted">RESULT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>May 20, 2026</td><td>Mathematics</td><td>Chapter 5 Quiz</td><td>42</td><td>50</td><td><span class="badge text-bg-success">Pass</span></td></tr>
                        <tr><td>May 18, 2026</td><td>English</td><td>Grammar Test</td><td>38</td><td>50</td><td><span class="badge text-bg-success">Pass</span></td></tr>
                        <tr><td>May 15, 2026</td><td>Science</td><td>Lab Assessment</td><td>28</td><td>50</td><td><span class="badge text-bg-danger">Fail</span></td></tr>
                        <tr><td>May 12, 2026</td><td>Urdu</td><td>Monthly Test</td><td>45</td><td>50</td><td><span class="badge text-bg-success">Pass</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var card = document.getElementById('student-card');
    if (!card) return;
    document.getElementById('btn-download-png')?.addEventListener('click', function () {
        if (typeof html2canvas === 'undefined') return;
        html2canvas(card, { scale: 2, backgroundColor: '#ffffff' }).then(function (canvas) {
            var link = document.createElement('a');
            link.download = 'student-card-STU-{{ $id ?? 1 }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
    function printCard() {
        document.body.classList.add('print-student-card-only');
        window.print();
        setTimeout(function () { document.body.classList.remove('print-student-card-only'); }, 500);
    }
    document.getElementById('btn-download-pdf')?.addEventListener('click', printCard);
    document.getElementById('btn-print-card')?.addEventListener('click', printCard);
});
</script>
@endpush

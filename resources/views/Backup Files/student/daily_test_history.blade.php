@extends('adminlayout.layout')

@section('title', 'Daily Test History — ' . $student->student_name)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Daily Test History</h1>
        <p class="text-muted mb-0">
            {{ $student->student_name }}
            · Roll {{ $student->student_roll_number ?? '—' }}
            · {{ $student->schoolClass->class_name ?? 'N/A' }} — {{ $student->section->section_name ?? 'N/A' }}
        </p>
    </div>
    <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Student
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body py-3">
                <div class="text-muted small">Total Tests</div>
                <div class="fs-4 fw-bold">{{ $dailyTestStats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-success">
            <div class="card-body py-3">
                <div class="text-muted small">Attempted (marks &gt; 0)</div>
                <div class="fs-4 fw-bold text-success">{{ $dailyTestStats['attempted'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-secondary">
            <div class="card-body py-3">
                <div class="text-muted small">Zero Marks</div>
                <div class="fs-4 fw-bold text-secondary">{{ $dailyTestStats['not_attempted'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Test Name</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th class="text-center">Obtained</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyTestHistory as $test)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($test->daily_test_date)->format('d M Y') }}</td>
                            <td class="fw-medium">{{ $test->daily_test_name }}</td>
                            <td>{{ $test->subject ?? '—' }}</td>
                            <td>{{ $test->teacher->teacher_name ?? '—' }}</td>
                            <td class="text-center">{{ $test->daily_test_obtained }}</td>
                            <td class="text-center">{{ $test->daily_test_total }}</td>
                            <td class="text-center fw-semibold">{{ number_format($test->daily_test_percentage, 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No daily test records for this student</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

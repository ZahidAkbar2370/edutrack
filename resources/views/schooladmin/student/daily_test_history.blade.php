@extends('schooladmin.layout.layout')

@section('title', 'Daily Test History — ' . $student->student_name)

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Daily Test History</h1>
        <p class="text-muted mb-0">
        <code><span class="fw-medium">{{ $student->student_name }}</span> -> (Roll # {{ $student->student_roll_number ?? '—' }})
            -> {{ $student->schoolClass->class_name ?? 'N/A' }} -> {{ $student->section->section_name ?? 'N/A' }} </code>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">

        <a href="{{ url('student/' . $student->id . '/export-daily-test-history-csv') }}" class="btn btn-outline-success btn-sm"
        data-confirm-action
                    data-confirm-title="Export Daily Test History to Excel"
                    data-confirm-message="Are you sure you want to export to CSV?"
                    data-confirm-yes="Yes, Export"
                    data-confirm-yes-class="btn-success"
        >
            <i class="bi bi-download me-1"></i> Export to CSV
        </a>

        <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Student Detail
        </a>
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
                    @if(!empty($dailyTestHistory) && $dailyTestHistory->count() > 0)
                        @foreach($dailyTestHistory as $key => $test)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($test->daily_test_date)->format('d M Y') }}</td>
                            <td class="fw-medium">{{ $test->daily_test_name }}</td>
                            <td>{{ $test->subject ?? '—' }}</td>
                            <td>{{ $test->teacher->teacher_name ?? '—' }}</td>
                            <td class="text-center">{{ $test->daily_test_obtained }}</td>
                            <td class="text-center">{{ $test->daily_test_total }}</td>
                            <td class="text-center fw-semibold">{{ number_format($test->daily_test_percentage, 1) }}%</td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No daily test records for this student</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@extends('schooladmin.layout.layout')

@section('title', 'Daily Test Detail')

@section('content')

@php
    $classId = request('class_id');
    $sectionId = request('section_id');
    $testDate = request('daily_test_date');
    $testName = request('daily_test_name');
    $teacherId = request('teacher_id');
    $subject = request('subject');
    $teacherName = $dailyTests->first()?->teacher->teacher_name ?? 'N/A';
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Daily Test Detail</h1>
        <p class="text-muted mb-0">
            {{ $schoolClass->class_name ?? 'N/A' }} — {{ $section->section_name ?? 'N/A' }}
            · {{ $testName }} 
            · {{ $testDate ? \Illuminate\Support\Carbon::parse($testDate)->format('d M Y') : '' }}
        </p>
        <p class="text-muted small mb-0">Teacher: {{ $teacherName }} · Total Marks: {{ $totalMarks }} · Subject: {{ $subject }}</p>
    </div>

    <div class="d-flex flex-wrap gap-2">

    <!-- <a href="{{ url('daily-test/export-to-csv/' . $dailyTests->first()->daily_test_code) }}" class="btn btn-outline-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Export to CSV
    </a> -->

    <!-- edit daily test -->
    <!-- <a href="{{ url('daily-test/edit/' . $dailyTests->first()->daily_test_code) }}" class="btn btn-outline-primary">
        <i class="bi bi-pencil-square me-1"></i> Edit Daily Test
    </a> -->


        <a href="#" class="btn btn-outline-danger"
        data-confirm-action
        data-confirm-title="Notification to Parents via Whatsapp"
        data-confirm-message="Are you sure you want to send notification to parents via whatsapp?"
        data-confirm-yes="Yes, Send Notification"
        data-confirm-yes-class="btn-danger"
        >
            <i class="bi bi-whatsapp me-1"></i> Report to Parents via Whatsapp
        </a>

        <a href="{{ url('daily-test') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Daily Test List
    </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($dailyTests->isEmpty())
    <div class="alert alert-warning">No daily test records found.</div>
@else
<form action="{{ url('daily-test/update') }}" method="POST">
    @csrf
    <input type="hidden" name="class_id" value="{{ $classId }}">
    <input type="hidden" name="section_id" value="{{ $sectionId }}">
    <input type="hidden" name="daily_test_date" value="{{ $testDate }}">
    <input type="hidden" name="daily_test_name" value="{{ $testName }}">
    <input type="hidden" name="teacher_id" value="{{ $teacherId }}">
    <input type="hidden" name="subject" value="{{ $subject }}">
    <input type="hidden" name="daily_test_total" value="{{ $totalMarks }}">

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students</h2>
            <span class="small text-muted">{{ $dailyTests->count() }} student(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th class="text-center" style="min-width: 140px;">Obtained Marks</th>
                            <th class="text-center">Percentage</th>
                            <th>Whatsapp Notification Status</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyTests as $key => $test)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-medium">
                                    <a href="{{ url('student/show/' . $test->student_id) }}" target="_blank" class="fw-medium">{{ $test->student->student_name ?? 'N/A' }}</a> <br>
                                    <span class="text-muted small">(Roll No: {{ $test->student->student_roll_number ?? '—' }})</span>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="students[{{ $test->student_id }}]"
                                           class="form-control form-control-sm obtained-input text-center mx-auto"
                                           style="max-width: 100px;"
                                           min="0" max="{{ $totalMarks }}"
                                           value="{{ $test->daily_test_obtained }}" required>
                                </td>
                                <td class="text-center pct-cell fw-semibold">{{ number_format($test->daily_test_percentage, 2) }}%</td>
                                <td>
                                    @if($test->whatsapp_status == 'pending')
                                    <span class="p-2 bg-warning text-white">Pending</span>
                                    @elseif($test->whatsapp_status == 'processing')
                                    <span class="p-2 bg-info text-white">Processing</span>
                                    @elseif($test->whatsapp_status == 'sent')
                                    <span class="p-2 bg-success text-white">Sent</span>
                                    @elseif($test->whatsapp_status == 'failed')
                                    <span class="p-2 bg-danger text-white">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $test->created_at ? \Illuminate\Support\Carbon::parse($test->created_at)->format('d M Y h:i A') : 'N/A' }}</td>
                                <td>{{ $test->updated_at ? \Illuminate\Support\Carbon::parse($test->updated_at)->format('d M Y h:i A') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update marks?')">
            <i class="bi bi-check-lg me-1"></i> Update Marks
        </button>
    </div>
</form>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var totalMarks = parseInt(document.querySelector('input[name="daily_test_total"]').value, 10) || 0;

        function calcPercentage(obtained) {
            if (totalMarks <= 0) return '0.00%';
            return ((obtained / totalMarks) * 100).toFixed(2) + '%';
        }

        document.querySelectorAll('.obtained-input').forEach(function (input) {
            input.addEventListener('input', function () {
                if (parseInt(input.value, 10) > totalMarks) input.value = totalMarks;
                var pctCell = input.closest('tr').querySelector('.pct-cell');
                if (pctCell) {
                    pctCell.textContent = calcPercentage(parseInt(input.value, 10) || 0);
                }
            });
        });
    });
</script>
@endpush

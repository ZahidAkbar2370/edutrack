@extends('adminlayout.layout')

@section('title', 'Attendance')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Attendance</h1>
        <p class="text-muted mb-0">Attendance records by class and section</p>
    </div>
    <a href="{{ url('attendance/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Mark Attendance
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-3">
    <div class="card-header bg-light">
        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Attendance</h2>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('attendance') }}" class="row g-3 align-items-end">
            <div class="col-md-3 col-lg-2">
                <label for="class_id" class="form-label">Class</label>
                <select name="class_id" id="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @if(!empty($classes))
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ isset($_GET['class_id']) && $_GET['class_id'] == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="section_id" class="form-label">Section</label>
                <select name="section_id" id="section_id" class="form-select">
                    <option value="">All Sections</option>
                    @if(!empty($sections))
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                            {{ isset($_GET['section_id']) && $_GET['section_id'] == $section->id ? 'selected' : '' }}>
                            {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                        </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="date" class="form-label">Specific Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}">
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}">
            </div>
            <div class="col-md-3 col-lg-2">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}">
            </div>
            <div class="col-md-12 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ url('attendance') }}" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
        <p class="form-text mb-0 mt-2">Use <strong>Specific Date</strong> for one day, or <strong>Date From / To</strong> for a range (leave specific date empty when using range).</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" data-js-paginate data-page-sizes="10,20,50,100,all" data-default-size="20">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class & Section</th>
                        <th class="text-center">Total Students</th>
                        <th class="text-center">Present</th>
                        <th class="text-center">Absent</th>
                        <th class="text-center">Leave</th>
                        <th>Attendance Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($attendanceGroups) && $attendanceGroups->count() > 0)
                        @foreach($attendanceGroups as $key => $group)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-medium">
                                    <span class="fw-medium">{{ $group->schoolClass->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $group->section->section_name ?? 'N/A' }})</span>
                                </td>
                                <td class="text-center">{{ $group->total_students }}</td>
                                <td class="text-center text-success fw-semibold">{{ $group->present_count }}</td>
                                <td class="text-center text-danger fw-semibold">{{ $group->absent_count }}</td>
                                <td class="text-center text-warning fw-semibold">{{ $group->leave_count }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($group->attendance_date)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('attendance.show', [
                                        'class_id' => $group->class_id,
                                        'section_id' => $group->section_id,
                                        'attendance_date' => $group->attendance_date,
                                    ]) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No attendance records found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var classSelect = document.getElementById('class_id');
        var sectionSelect = document.getElementById('section_id');
        if (!classSelect || !sectionSelect) return;

        var allOptions = Array.from(sectionSelect.querySelectorAll('option[data-class-id]'));

        function filterSections() {
            var classId = classSelect.value;
            var currentValue = sectionSelect.value;
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            if (!classId) {
                allOptions.forEach(function (opt) {
                    sectionSelect.appendChild(opt.cloneNode(true));
                });
            } else {
                allOptions.forEach(function (opt) {
                    if (opt.getAttribute('data-class-id') === classId) {
                        sectionSelect.appendChild(opt.cloneNode(true));
                    }
                });
            }
            if (currentValue && sectionSelect.querySelector('option[value="' + currentValue + '"]')) {
                sectionSelect.value = currentValue;
            }
        }

        classSelect.addEventListener('change', filterSections);
        filterSections();
    });
</script>
@endpush

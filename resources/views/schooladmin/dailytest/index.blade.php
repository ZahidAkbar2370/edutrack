@extends('schooladmin.layout.layout')

@section('title', 'Daily Tests')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Daily Tests</h1>
        <p class="text-muted mb-0">Test records by class and section</p>
    </div>
    <a href="{{ url('daily-test/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Daily Test
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
        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Daily Tests</h2>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('daily-test') }}" class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-2">
                <label for="class_id" class="form-label">Class</label>
                <select name="class_id" id="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($filterClasses as $class)
                        <option value="{{ $class->id }}" {{ ($filters['class_id'] ?? '') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label for="section_id" class="form-label">Section</label>
                <select name="section_id" id="section_id" class="form-select">
                    <option value="">All Sections</option>
                    @foreach($filterSections as $section)
                        <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                            {{ ($filters['section_id'] ?? '') == $section->id ? 'selected' : '' }}>
                            {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label for="teacher_name" class="form-label">Teacher</label>
                <select name="teacher_name" id="teacher_name" class="form-select">
                    <option value="">All Teachers</option>
                    @foreach($filterTeachers as $teacher)
                        <option value="{{ $teacher->teacher_name }}" {{ ($filters['teacher_name'] ?? '') == $teacher->teacher_name ? 'selected' : '' }}>
                            {{ $teacher->teacher_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label for="subject" class="form-label">Subject</label>
                <select name="subject" id="subject" class="form-select">
                    <option value="">All Subjects</option>
                    @foreach($filterSubjects as $sub)
                        <option value="{{ $sub->subject_name }}" {{ ($filters['subject'] ?? '') == $sub->subject_name ? 'selected' : '' }}>
                            {{ $sub->subject_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label for="date" class="form-label">Test Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
            </div>
            <div class="col-md-4 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ url('daily-test') }}" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
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
                        <th>Test Name</th>
                        <th>Test Date</th>
                        <th>Teacher Name</th>
                        <th>Subject</th>
                        <th class="text-center">Total Marks</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testGroups as $group)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $classes[$group->class_id]->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $sections[$group->section_id]->section_name ?? 'N/A' }})</span>
                            </td>
                            <td>{{ $group->daily_test_name }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($group->daily_test_date)->format('d M Y') }}</td>
                            <td>{{ $teachers[$group->teacher_id]->teacher_name ?? 'N/A' }}</td>
                            <td>{{ $group->subject ?? '—' }}</td>
                            <td class="text-center fw-semibold">{{ $group->total_marks }}</td>
                            <td class="text-center">
                                <a href="{{ route('daily-test.show', [
                                    'class_id' => $group->class_id,
                                    'section_id' => $group->section_id,
                                    'daily_test_date' => $group->daily_test_date,
                                    'daily_test_name' => $group->daily_test_name,
                                    'teacher_id' => $group->teacher_id,
                                    'subject' => $group->subject,
                                ]) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No daily test records found</td>
                        </tr>
                    @endforelse
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

@extends('schooladmin.layout.layout')

@section('title', 'Add Daily Test')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Add Daily Test</h1>
        <p class="text-muted mb-0">Select class and section, enter test details and marks</p>
    </div>
    <a href="{{ url('daily-test') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('daily-test/store') }}" method="POST" id="daily-test-form">
    @csrf

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Class &amp; Section</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                    <select name="section_id" id="section_id" class="form-select" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="daily_test_date" class="form-label">Test Date <span class="text-danger">*</span></label>
                    <input type="date" name="daily_test_date" id="daily_test_date" value="{{ old('daily_test_date', date('Y-m-d')) }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Test Information</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="daily_test_name" class="form-label">Test Name <span class="text-danger">*</span></label>
                    <input type="text" name="daily_test_name" id="daily_test_name" value="{{ old('daily_test_name') }}" class="form-control" placeholder="e.g. Unit Test 1" required>
                    @error('daily_test_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <select name="subject" id="subject" class="form-select" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->subject_name }}" {{ old('subject') == $subject->subject_name ? 'selected' : '' }}>
                                {{ $subject->subject_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @if($subjects->isEmpty())
                        <div class="form-text text-warning">No subjects found. Add subjects from Settings first.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="teacher_name" class="form-label">Teacher <span class="text-danger">*</span></label>
                    <select name="teacher_name" id="teacher_name" class="form-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->teacher_name }}" {{ old('teacher_name') == $teacher->teacher_name ? 'selected' : '' }}>
                                {{ $teacher->teacher_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @if($teachers->isEmpty())
                        <div class="form-text text-warning">No teachers found. Register teachers first.</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="daily_test_total" class="form-label">Total Marks <span class="text-danger">*</span></label>
                    <input type="number" name="daily_test_total" id="daily_test_total" value="{{ old('daily_test_total', 100) }}" class="form-control" min="1" required>
                    @error('daily_test_total')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm d-none" id="students-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students — Obtained Marks</h2>
            <span class="small text-muted" id="students-count"></span>
        </div>
        <div class="card-body p-0">
            <div id="students-loading" class="text-center text-muted py-4 d-none">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Loading students...
            </div>
            <div id="students-empty" class="text-center text-muted py-4 d-none">No students found for this class and section.</div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 d-none" id="students-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Roll #</th>
                            <th class="text-center" style="min-width: 140px;">Obtained Marks</th>
                            <th class="text-center" style="min-width: 100px;">Percentage</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="submit-wrap">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this daily test?')">
            <i class="bi bi-check-lg me-1"></i> Save Daily Test
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var classSelect = document.getElementById('class_id');
        var sectionSelect = document.getElementById('section_id');
        var totalMarksInput = document.getElementById('daily_test_total');
        var studentsCard = document.getElementById('students-card');
        var studentsTable = document.getElementById('students-table');
        var studentsTbody = document.getElementById('students-tbody');
        var studentsLoading = document.getElementById('students-loading');
        var studentsEmpty = document.getElementById('students-empty');
        var studentsCount = document.getElementById('students-count');
        var submitWrap = document.getElementById('submit-wrap');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var allSectionOptions = Array.from(sectionSelect.querySelectorAll('option[data-class-id]'));

        function getTotalMarks() {
            return parseInt(totalMarksInput.value, 10) || 0;
        }

        function calcPercentage(obtained) {
            var total = getTotalMarks();
            if (total <= 0) return '0%';
            return ((obtained / total) * 100).toFixed(2) + '%';
        }

        function updateRowPercentage(input) {
            var pctCell = input.closest('tr').querySelector('.pct-cell');
            if (pctCell) {
                pctCell.textContent = calcPercentage(parseInt(input.value, 10) || 0);
            }
        }

        function filterSections() {
            var classId = classSelect.value;
            var currentValue = sectionSelect.value;
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            if (!classId) return;
            allSectionOptions.forEach(function (opt) {
                if (opt.getAttribute('data-class-id') === classId) {
                    sectionSelect.appendChild(opt.cloneNode(true));
                }
            });
            if (currentValue && sectionSelect.querySelector('option[value="' + currentValue + '"]')) {
                sectionSelect.value = currentValue;
            }
            loadStudents();
        }

        function loadStudents() {
            var classId = classSelect.value;
            var sectionId = sectionSelect.value;

            studentsTbody.innerHTML = '';
            studentsTable.classList.add('d-none');
            studentsEmpty.classList.add('d-none');
            submitWrap.classList.add('d-none');

            if (!classId || !sectionId) {
                studentsCard.classList.add('d-none');
                return;
            }

            studentsCard.classList.remove('d-none');
            studentsLoading.classList.remove('d-none');

            fetch('{{ url('daily-test/students-list') }}?class_id=' + encodeURIComponent(classId) + '&section_id=' + encodeURIComponent(sectionId), {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(function (res) { return res.json(); })
            .then(function (students) {
                studentsLoading.classList.add('d-none');
                var total = getTotalMarks();

                if (!students.length) {
                    studentsEmpty.classList.remove('d-none');
                    studentsCount.textContent = '';
                    return;
                }

                students.forEach(function (student, index) {
                    var row = document.createElement('tr');
                    row.innerHTML =
                        '<td>' + (index + 1) + '</td>' +
                        '<td class="fw-medium">' + escapeHtml(student.student_name) + '</td>' +
                        '<td>' + escapeHtml(student.student_roll_number || '—') + '</td>' +
                        '<td class="text-center">' +
                            '<input type="number" name="students[' + student.id + ']" class="form-control form-control-sm obtained-input text-center mx-auto" style="max-width: 100px;" min="0" max="' + total + '" value="0" required>' +
                        '</td>' +
                        '<td class="text-center pct-cell text-muted">0%</td>';
                    studentsTbody.appendChild(row);
                });

                studentsTable.classList.remove('d-none');
                submitWrap.classList.remove('d-none');
                studentsCount.textContent = students.length + ' student(s)';

                document.querySelectorAll('.obtained-input').forEach(function (input) {
                    input.addEventListener('input', function () {
                        var total = getTotalMarks();
                        input.max = total;
                        if (parseInt(input.value, 10) > total) input.value = total;
                        updateRowPercentage(input);
                    });
                });
            })
            .catch(function () {
                studentsLoading.classList.add('d-none');
                studentsEmpty.textContent = 'Failed to load students.';
                studentsEmpty.classList.remove('d-none');
            });
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        totalMarksInput.addEventListener('input', function () {
            var total = getTotalMarks();
            document.querySelectorAll('.obtained-input').forEach(function (input) {
                input.max = total;
                if (parseInt(input.value, 10) > total) input.value = total;
                updateRowPercentage(input);
            });
        });

        classSelect.addEventListener('change', filterSections);
        sectionSelect.addEventListener('change', loadStudents);
        filterSections();
    });
</script>
@endpush

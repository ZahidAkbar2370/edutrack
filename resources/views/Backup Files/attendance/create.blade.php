@extends('adminlayout.layout')

@section('title', 'Mark Attendance')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Mark Attendance</h1>
        <p class="text-muted mb-0">Select class and section, then mark each student</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('attendance/store') }}" method="POST" id="attendance-form">
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
                    <label for="attendance_date" class="form-label">Attendance Date <span class="text-danger">*</span></label>
                    <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date', date('Y-m-d')) }}" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm d-none" id="students-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 fw-semibold">Students</h2>
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
                            <th class="text-center">Present / Absent</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 d-none" id="submit-wrap">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save attendance?')">
            <i class="bi bi-check-lg me-1"></i> Save Attendance
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var classSelect = document.getElementById('class_id');
        var sectionSelect = document.getElementById('section_id');
        var studentsCard = document.getElementById('students-card');
        var studentsTable = document.getElementById('students-table');
        var studentsTbody = document.getElementById('students-tbody');
        var studentsLoading = document.getElementById('students-loading');
        var studentsEmpty = document.getElementById('students-empty');
        var studentsCount = document.getElementById('students-count');
        var submitWrap = document.getElementById('submit-wrap');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var allSectionOptions = Array.from(sectionSelect.querySelectorAll('option[data-class-id]'));

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

            fetch('{{ url('attendance/students-list') }}?class_id=' + encodeURIComponent(classId) + '&section_id=' + encodeURIComponent(sectionId), {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(function (res) { return res.json(); })
            .then(function (students) {
                studentsLoading.classList.add('d-none');

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
                            '<input type="hidden" name="students[' + student.id + ']" value="absent">' +
                            '<div class="form-check form-switch d-inline-block mb-0">' +
                                '<input class="form-check-input attendance-toggle" type="checkbox" name="students[' + student.id + ']" value="present" id="student-' + student.id + '" checked role="switch">' +
                                '<label class="form-check-label small attendance-label" for="student-' + student.id + '">Present</label>' +
                            '</div>' +
                        '</td>';
                    studentsTbody.appendChild(row);
                });

                studentsTable.classList.remove('d-none');
                submitWrap.classList.remove('d-none');
                studentsCount.textContent = students.length + ' student(s)';

                document.querySelectorAll('.attendance-toggle').forEach(function (input) {
                    var label = input.closest('td').querySelector('.attendance-label');
                    function update() {
                        label.textContent = input.checked ? 'Present' : 'Absent';
                        label.className = 'form-check-label small attendance-label ' + (input.checked ? 'text-success' : 'text-danger');
                    }
                    update();
                    input.addEventListener('change', update);
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

        classSelect.addEventListener('change', filterSections);
        sectionSelect.addEventListener('change', loadStudents);
        filterSections();
    });
</script>
@endpush

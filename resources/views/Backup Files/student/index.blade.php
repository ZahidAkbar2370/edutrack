@extends('adminlayout.layout')

@section('title', 'Students')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Students</h1>
        <p class="text-muted mb-0">All registered students</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('student/export-csv') }}{{ ($q = http_build_query(array_filter(request()->only(['class_id', 'section_id', 'name', 'status'])))) ? '?' . $q : '' }}" class="btn btn-outline-success">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
        <a href="{{ url('student/import') }}" class="btn btn-outline-secondary">
            <i class="bi bi-upload me-1"></i> Import CSV
        </a>
        <a href="{{ url('student/upgrade-class') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-up-circle me-1"></i> Promote Class
        </a>
        <a href="{{ url('student/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Student
        </a>
    </div>
</div>



@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif



@if(session('import_errors'))
    <div class="alert alert-warning">
        <ul class="mb-0 small">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif



<div class="card shadow-sm mb-3">
    <div class="card-header bg-light">
        <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Students</h2>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('student') }}" class="row g-3 align-items-end">

        <div class="col-md-3">
                <label for="name" class="form-label">Name / Roll No</label>
                <input type="text" name="name_roll_number" id="name_roll_number" class="form-control" placeholder="Search by name or roll number"
                    value="{{ $filters['name_roll_number'] ?? '' }}">
            </div>

            <div class="col-md-2">
                <label for="class_id" class="form-label">Class</label>
                <select name="class_id" id="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @if(!empty($classes))
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ ($filters['class_id'] ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <label for="section_id" class="form-label">Section</label>
                <select name="section_id" id="section_id" class="form-select">
                    <option value="">All Sections</option>
                    @if(!empty($sections))
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                {{ ($filters['section_id'] ?? '') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="banned">Banned</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ url('student') }}" class="btn btn-outline-secondary" title="Clear filters">
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
                        <th>Student Name</th>
                        <th>Class & Section</th>
                        <th>Parent Name & Phone</th>
                        <th>Per Month Fee</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $student->student_name }}</span> <br>
                                <span class="text-muted small">(Roll No: {{ $student->student_roll_number ?? '—' }})</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $student->schoolClass->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $student->section->section_name ?? 'N/A' }})</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $student->parent->parent_name ?? 'N/A' }}</span> <br>
                                <span class="text-muted small">(Phone: {{ $student->parent->parent_phone_no ?? '—' }})</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $student->student_per_month_fee ?? '--' }}</span>
                            </td>
                            <td>
                            <form action="{{ url('student/status/' . $student->id) }}" method="POST" class="d-flex gap-1 align-items-center">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm   @if($student->status == 'active') bg-success @elseif($student->status == 'completed') bg-warning @elseif($student->status == 'banned') bg-danger @elseif($student->status == 'inactive') bg-secondary  @endif text-white" style="width:110px;" onchange="this.form.submit()">
                                        @foreach($statuses as $statusOption)
                                            <option value="{{ $statusOption }}" {{ $student->status === $statusOption ? 'selected' : '' }}>
                                                {{ ucfirst($statusOption) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                               
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('student/card/' . $student->id) }}" class="btn btn-outline-success" title="ID Card">
                                        <i class="bi bi-person-badge"></i>
                                    </a>
                                    <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('student/edit/' . $student->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No students found</td>
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

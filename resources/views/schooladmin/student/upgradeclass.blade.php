@extends('schooladmin.layout.layout')

@section('title', 'Promote Class')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Promote Class</h1>
        <p class="text-muted mb-0">Move all students from one class & section to the next (e.g. One A → Two A)</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ url('student') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('student/upgrade-class') }}" method="POST" id="promote-form">
    @csrf

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-primary border-opacity-25">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-box-arrow-right me-1 text-primary"></i> From (current)</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="from_class_id" class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="from_class_id" id="from_class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @if(!empty($classes))
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('from_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('from_class_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="from_section_id" class="form-label">Section <span class="text-danger">*</span></label>
                        <select name="from_section_id" id="from_section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @if(!empty($sections))
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                    {{ old('from_section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('from_section_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <p class="text-muted small mt-3 mb-0" id="from-student-hint">Select class and section to see student count.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-success border-opacity-25">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold"><i class="bi bi-box-arrow-in-right me-1 text-success"></i> To (promote to)</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="to_class_id" class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="to_class_id" id="to_class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @if(!empty($classes))
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('to_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('to_class_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="to_section_id" class="form-label">Section <span class="text-danger">*</span></label>
                        <select name="to_section_id" id="to_section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @if(!empty($sections))
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                    {{ old('to_section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('to_section_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" id="promote-btn"
        data-confirm-action
                    data-confirm-title="Promote Students"
                    data-confirm-message="Are you sure you want to promote students from the selected class & section to the new class & section?"
                    data-confirm-yes="Yes, Promote"
                    data-confirm-yes-class="btn-primary"
        >
            <i class="bi bi-arrow-up-circle me-1"></i> Promote Students
        </button>
        <a href="{{ url('student') }}" class="btn btn-outline-danger">Discard</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupSectionFilter(classSelectId, sectionSelectId) {
            var classSelect = document.getElementById(classSelectId);
            var sectionSelect = document.getElementById(sectionSelectId);
            if (!classSelect || !sectionSelect) return;

            var allOptions = Array.from(sectionSelect.querySelectorAll('option[data-class-id]'));

            function filterSections() {
                var classId = classSelect.value;
                var currentValue = sectionSelect.value;
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                if (!classId) return;
                allOptions.forEach(function (opt) {
                    if (opt.getAttribute('data-class-id') === classId) {
                        sectionSelect.appendChild(opt.cloneNode(true));
                    }
                });
                if (currentValue && sectionSelect.querySelector('option[value="' + currentValue + '"]')) {
                    sectionSelect.value = currentValue;
                }
            }

            classSelect.addEventListener('change', filterSections);
            filterSections();
        }

        setupSectionFilter('from_class_id', 'from_section_id');
        setupSectionFilter('to_class_id', 'to_section_id');
    });
</script>
@endpush

@extends('schooladmin.layout.layout')

@section('title', 'Register Student')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Register Student</h1>
        <p class="text-muted mb-0">Add student and parent information</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('student/store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Personal Information</h2>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label for="student_name" class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" name="student_name" id="student_name" value="{{ old('student_name') }}" class="form-control" placeholder="Enter Student Name" required>
                        @error('student_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="student_email" class="form-label">Student Email</label>
                        <input type="email" name="student_email" id="student_email" placeholder="student@example.com" value="{{ old('student_email') }}" class="form-control">
                        @error('student_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_phone_no" class="form-label">Student Phone <small class="text-danger">(With Country Code - Format: 923001234567)</small></label>
                        <input type="number" name="student_phone_no" id="student_phone_no" value="{{ old('student_phone_no') }}" class="form-control" placeholder="923001234567">
                        @error('student_phone_no')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" name="student_date_of_birth" id="student_date_of_birth" value="{{ old('student_date_of_birth') }}" class="form-control">
                        @error('student_date_of_birth')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>   

                    <div class="mb-3">
                        <label for="student_gender" class="form-label">Student Gender</label>
                        <select name="student_gender" id="student_gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('student_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('student_gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('student_gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('student_gender')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label for="student_photo" class="form-label">Profile Image</label>
                        <input type="file" name="student_photo" id="student_photo" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">Optional. If you do not choose a photo, the default profile image will be used.</div>
                        @error('student_photo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <img id="student_photo_preview" src="{{ asset('Admin/images/student/profiles/default.png') }}" alt="Preview" class="rounded border" style="width:120px;height:120px;object-fit:cover;">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Class and Section Information</h2>
                </div>
                <div class="card-body">

                <div class="mb-3">
                        <label for="student_admission_date" class="form-label">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" name="student_admission_date" id="student_admission_date" value="{{ old('student_admission_date') ?? date('Y-m-d') }}" class="form-control" required>
                        @error('student_admission_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">Select Class From List</option>
                            @if(!empty($classes))
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('class_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                        <select name="section_id" id="section_id" class="form-select" required>
                            <option value="">Select Section From List</option>
                            @if(!empty($sections))
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->section_name }} ({{ $section->schoolClass->class_name ?? '' }})
                                </option>
                            @endforeach
                            @endif
                        </select>
                        @error('section_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_per_month_fee" class="form-label">Per Month Fee <span class="text-danger">*</span></label>
                        <input type="number" name="student_per_month_fee" id="student_per_month_fee" placeholder="0" value="{{ old('student_per_month_fee') ?? 0 }}" class="form-control" required>
                        @error('student_per_month_fee')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="" disabled selected>Select Status</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }} selected>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="banned" {{ old('status') === 'banned' ? 'selected' : '' }}>
                                    Banned
                                </option>
                        </select>
                        @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Parent Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="parent_name" class="form-label">Parent Name <span class="text-danger">*</span></label>
                        <input type="text" name="parent_name" id="parent_name" placeholder="Enter Parent Name" value="{{ old('parent_name') }}" class="form-control" required>
                        @error('parent_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="parent_phone_no" class="form-label">Parent Phone <small class="text-danger">(With Country Code - Format: 923001234567)</small></label>
                        <input type="number" name="parent_phone_no" id="parent_phone_no" value="{{ old('parent_phone_no') }}" class="form-control" placeholder="923001234567">
                        @error('parent_phone_no')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="parent_email" class="form-label">Parent Email</label>
                        <input type="email" name="parent_email" id="parent_email" placeholder="parent@example.com" value="{{ old('parent_email') }}" class="form-control">
                        @error('parent_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="parent_address" class="form-label">Address</label>
                        <textarea name="parent_address" id="parent_address" class="form-control" rows="3" placeholder="Enter Address">{{ old('parent_address') }}</textarea>
                        @error('parent_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" 
        data-confirm-action
                    data-confirm-title="Register Student"
                    data-confirm-message="Are you sure you want to register this student?"
                    data-confirm-yes="Yes, Register"
                    data-confirm-yes-class="btn-primary"
        >
            <i class="bi bi-person-plus me-1"></i> Register Student
        </button>
    </div>
</form>

@endsection

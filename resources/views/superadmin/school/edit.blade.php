@extends('superadmin.layout.layout')

@section('title', 'Edit School')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit School</h1>
        <p class="text-muted mb-0">Update school and principal information</p>
    </div>

    <div class="d-flex flex-wrap justify-content-end gap-2">
        <a href="{{ url('school/show/' . $school->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to School
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ URL::to('school/update/' . $school->id) }}" method="POST" id="school-form">
    @csrf

    {{-- School col-6 | Principal col-6 --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">School Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="school_name" class="form-label">School Name <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" id="school_name" class="form-control" value="{{ old('school_name', $school->school_name) }}" placeholder="e.g. City Public School" required>
                        @error('school_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_no" class="form-label">School Phone No <span class="text-danger">*</span></label>
                        <input type="text" name="school_phone_no" id="school_phone_no" class="form-control" value="{{ old('school_phone_no', $school->school_phone_no) }}" placeholder="923001234567" required>
                        @error('school_phone_no')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="school_email" class="form-label">School Email</label>
                        <input type="email" name="school_email" id="school_email" class="form-control" value="{{ old('school_email', $school->school_email) }}" placeholder="example@edutrack.school">
                        @error('school_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $school->city) }}" placeholder="e.g. Lahore" required>
                        @error('city')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="address" class="form-label">School Address <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $school->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Principal Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="priciple_name" class="form-label">Principal Name <span class="text-danger">*</span></label>
                        <input type="text" name="priciple_name" id="priciple_name" class="form-control" value="{{ old('priciple_name', $school->priciple_name) }}" placeholder="Principal full name" required>
                        @error('priciple_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="priciple_phone_no" class="form-label">Principal Phone No</label>
                        <input type="text" name="priciple_phone_no" id="priciple_phone_no" class="form-control" value="{{ old('priciple_phone_no', $school->priciple_phone_no) }}" placeholder="+92 321 9876543">
                        @error('priciple_phone_no')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="priciple_email" class="form-label">Principal Email</label>
                        <input type="email" name="priciple_email" id="priciple_email" class="form-control" value="{{ old('priciple_email', $school->priciple_email) }}" placeholder="principal@example.com">
                        @error('priciple_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this school?')"><i class="bi bi-building me-1"></i> Update School</button>
    </div>

</form>

@endsection
@extends('adminlayout.layout')

@section('title', 'General Settings')

@section('content')


@include('adminlayout.setting_menu')


<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">General Settings</h1>
        <p class="text-muted mb-0">General settings for the school</p>
    </div>
</div>

    <div class="row g-3">
        <div class="col-lg-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">School Information</h2>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label for="student_name" class="form-label">School Name <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" class="form-control" placeholder="Enter School Name" required>
                        @error('school_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="school_email" class="form-label">School Email <span class="text-danger">*</span></label>
                        <input type="email" name="school_email" id="school_email" value="{{ old('school_email') }}" class="form-control" placeholder="Enter School Email" required>
                        @error('school_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="school_phone_no" class="form-label">School Phone No <span class="text-danger">*</span></label>
                        <input type="number" name="school_phone_no" id="school_phone_no" value="{{ old('school_phone_no') }}" class="form-control" placeholder="Enter School Phone No" required>
                        @error('school_phone_no')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="school_address" class="form-label">School Address <span class="text-danger">*</span></label>
                        <textarea name="school_address" id="school_address" class="form-control" rows="3">{{ old('school_address') }}</textarea>
                        @error('school_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="school_logo" class="form-label">School Logo</label>
                        <input type="file" name="school_logo" id="school_logo" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                        <div class="form-text">Optional. If you do not choose a photo, the default profile image will be used.</div>
                        @error('school_logo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <!-- <div class="mt-2">
                            <img id="school_logo_preview" src="asset(\App\Models\School::DEFAULT_LOGO) " alt="Preview" class="rounded border" style="width:120px;height:120px;object-fit:cover;">
                        </div> -->
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save the changes?')">
            <i class="bi bi-save me-1"></i> Save Changes
        </button>
    </div>
</form>

@endsection

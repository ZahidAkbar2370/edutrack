@extends('schooladmin.layout.layout')

@section('title', 'Pay Salary')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Pay Salary</h1>
        <p class="text-muted mb-0">Pay salary to a teacher</p>
    </div>
    <a href="{{ url('teacher') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ url('teacher/pay-salary') }}" method="POST">
    @csrf

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">Teacher Information</h2>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-2">
                    <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                    <select name="teacher_id" id="teacher_id" class="form-select">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->teacher_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="teacher_salary" class="form-label">Salary <span class="text-danger">*</span></label>
                    <input type="number" name="teacher_salary" id="teacher_salary" value="{{ old('teacher_salary') }}" class="form-control">
                    @error('teacher_salary')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="remaining_salary" class="form-label">Remaining Salary <span class="text-danger">*</span></label>
                    <input type="number" name="remaining_salary" id="remaining_salary" value="{{ old('remaining_salary') }}" class="form-control">
                    @error('remaining_salary')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="teacher_salary" class="form-label">Bonus <span class="text-danger">*</span></label>
                    <input type="number" name="bonus" id="bonus" value="{{ old('bonus') }}" class="form-control">
                    @error('bonus')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" value="{{ old('total_amount') }}" class="form-control">
                    @error('total_amount')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="teacher_address" class="form-label">Paid Amount</label>
                    <input type="number" name="teacher_salary" id="teacher_salary" value="{{ old('teacher_salary') }}" class="form-control">
                    @error('teacher_salary')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="submit" class="btn btn-primary" 
        data-confirm-action
                    data-confirm-title="Save Teacher"
                    data-confirm-message="Are you sure you want to save this teacher?"
                    data-confirm-yes="Yes, Save"
                    data-confirm-yes-class="btn-primary"
        >
            <i class="bi bi-check-lg me-1"></i> Pay Salary
        </button>
    </div>
</form>

@endsection

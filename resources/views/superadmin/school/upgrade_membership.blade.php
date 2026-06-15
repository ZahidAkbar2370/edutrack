@extends('adminlayout.layout')

@section('title', 'Register School')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Register School</h1>
        <p class="text-muted mb-0">Add a new school to EduTrack</p>
    </div>
</div>

<form action="{{ URL::to('school/store') }}" method="POST" id="school-form">
    @csrf

    {{-- School col-6 | Principal col-6 --}}
    <div class="row g-3 mb-3">

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Assign Membership</h2>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="priciple_email" class="form-label">Membership <span class="text-danger">*</span></label>
                        <select name="membership_id" id="membership_id" class="form-select" required>
                            <option value="">Select Membership</option>
                            @if(!empty($memberships))
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" {{ old('membership_id') == $membership->id ? 'selected' : '' }}>{{ $membership->membership_name }}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('membership_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Membership Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="Membership Expiry Date" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Transaction</h2>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="priciple_email" class="form-label">Purpose <span class="text-danger">*</span></label>
                        <select name="membership_id" id="membership_id" class="form-select" required>
                            <option value="">Select Transaction Purpose</option>
                            
                        </select>
                        @error('membership_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Transaction Prove Image <span class="text-danger">*</span></label>
                        <input type="file" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date') }}" placeholder="Membership Expiry Date" required>
                        @error('membership_expiry_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount') }}" placeholder="Paid Amount" required>
                        @error('paid_amount')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this school?')"><i class="bi bi-building me-1"></i> Update</button>
    </div>

</form>

@endsection
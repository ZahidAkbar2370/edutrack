@extends('adminlayout.layout')

@section('title', 'Upgrade Membership')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Upgrade Membership</h1>
        <p class="text-muted mb-0">Upgrade the membership of the school</p>
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

<form action="{{ URL::to('school/upgrade-membership/' . $school->id) }}" method="POST" id="school-form" enctype="multipart/form-data">
    @csrf

    {{-- School col-6 | Principal col-6 --}}
    <div class="row g-3 mb-3">

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Membership Information</h2>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="priciple_email" class="form-label">Membership <span class="text-danger">*</span></label>
                        <select name="membership_id" id="membership_id" class="form-select" required>
                            <option value="">Select Membership</option>
                            @if(!empty($memberships))
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" {{ old('membership_id') == $membership->id ? 'selected' : '' }} {{ $school->membership_id == $membership->id ? 'selected' : '' }}>{{ $membership->membership_name }}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('membership_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Membership Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="membership_expiry_date" id="membership_expiry_date" class="form-control" value="{{ old('membership_expiry_date', $school->user?->membership_expiry_date ? \Illuminate\Support\Carbon::parse($school->user->membership_expiry_date)->format('Y-m-d') : '') }}" placeholder="Membership Expiry Date" required>
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
                        <label for="transaction_purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                        <select name="transaction_purpose" id="transaction_purpose" class="form-select" required>
                            <option value="">Select Transaction Purpose</option>
                            <option value="membership_renew" {{ old('transaction_purpose') == 'membership_renew' ? 'selected' : '' }}>Membership Renew</option>
                            <option value="membership_upgrade" {{ old('transaction_purpose') == 'membership_upgrade' ? 'selected' : '' }}>Membership Upgrade</option>
                            <option value="membership_downgrade" {{ old('transaction_purpose') == 'membership_downgrade' ? 'selected' : '' }}>Membership Downgrade</option>
                            <option value="domain_hosting" {{ old('transaction_purpose') == 'domain_hosting' ? 'selected' : '' }}>Domain Hosting</option>
                            <option value="other" {{ old('transaction_purpose') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('transaction_purpose')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="transaction_prove_image" class="form-label">Transaction Prove Image</label>
                        <input type="file" name="transaction_prove_image" id="transaction_prove_image" class="form-control" accept="image/*">
                        @error('transaction_prove_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control" min="0" value="{{ old('paid_amount') }}" placeholder="Paid Amount" required>
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
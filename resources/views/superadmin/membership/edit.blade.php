@extends('superadmin.layout.layout')

@section('title', 'Edit Membership')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Edit Plan</h1>
        <p class="text-muted mb-0">Add a new membership plan to EduTrack</p>
    </div>
</div>



<form action="{{URL::to('membership/update', $membership->id)}}" method="POST" id="membership-form" class="need-validations">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Membership Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="membership_name" class="form-label">Membership Name <span class="text-danger">*</span></label>
                        <input type="text" name="membership_name" id="membership_name" value="{{ $membership->membership_name ?? old('membership_name') }}" class="form-control" placeholder="e.g. Basic, Standard, Premium, Diamond" required>
                    </div>
                    <div class="mb-3">
                        <label for="membership_price" class="form-label">Membership Price <span class="text-danger">*</span></label>
                        <input type="number" name="membership_price" id="membership_price" value="{{ $membership->membership_price ?? old('membership_price') }}" class="form-control" placeholder="Rs 1000, 1500, 2000, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="students_limit" class="form-label">Students Limit</label>
                        <input type="number" name="students_limit" id="students_limit" value="{{ $membership->students_limit ?? old('students_limit') }}" class="form-control" placeholder="50, 100, 150, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="teachers_limit" class="form-label">Teachers Limit</label>
                        <input type="number" name="teachers_limit" id="teachers_limit" value="{{ $membership->teachers_limit ?? old('teachers_limit') }}" class="form-control" placeholder="10, 20, 30, etc.">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h6 mb-0 fw-semibold">Features</h2>
                </div>
                <div class="card-body p-0">

                    @php
                        $allowed_student_card = $membership->allowed_student_card ?? 0;
                        $allowed_attendance = $membership->allowed_attendance ?? 0;
                        $allowed_daily_test = $membership->allowed_daily_test ?? 0;
                        $allowed_fee_management = $membership->allowed_fee_management ?? 0;
                        $allowed_whatsapp_message = $membership->allowed_whatsapp_message ?? 0;

                        $features = [
                            ['name' => 'allowed_student_card', 'title' => 'Student Card', 'value' => $allowed_student_card],
                            ['name' => 'allowed_attendance', 'title' => 'Attendance', 'value' => $allowed_attendance ],
                            ['name' => 'allowed_daily_test', 'title' => 'Daily Test', 'value' => $allowed_daily_test],
                            ['name' => 'allowed_fee_management', 'title' => 'Fee Management', 'value' => $allowed_fee_management],
                            ['name' => 'allowed_whatsapp_message', 'title' => 'WhatsApp Alert', 'value' => $allowed_whatsapp_message],
                        ];
                    @endphp

                    @if($features)
                        @foreach($features as $feature)
                            <div class="d-flex justify-content-between align-items-center px-3 py-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                                <div class="fw-semibold">{{ $feature['title'] }}</div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="{{ $feature['name'] }}" id="{{$feature['name']}}" value="0">
                                    <input class="form-check-input feature-toggle" type="checkbox" name="{{ $feature['name'] }}" value="1" id="{{ $feature['name'] }}" role="switch" 
                                    
                                    @if($feature['name'] == 'allowed_student_card' && $feature['value'] == 1) checked @endif
                                    @if($feature['name'] == 'allowed_attendance' && $feature['value'] == 1) checked @endif
                                    @if($feature['name'] == 'allowed_daily_test' && $feature['value'] == 1) checked @endif
                                    @if($feature['name'] == 'allowed_fee_management' && $feature['value'] == 1) checked @endif
                                    @if($feature['name'] == 'allowed_whatsapp_message' && $feature['value'] == 1) checked @endif
                                    
                                    >
                                    <label class="form-check-label small text-muted feature-label" for="{{ $feature['name'] }}">Off</label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary"
            
            data-confirm-action
                    data-confirm-title="Update Membership"
                    data-confirm-message="Are you sure you want to update this Membership Plan?"
                    data-confirm-yes="Yes, Update"
                    data-confirm-yes-class="btn-success"
            
            ><i class="bi bi-building me-1"></i> Update Membership</button>
        </div>

    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.feature-toggle').forEach(function(input) {
            var label = document.querySelector('label[for="' + input.id + '"].feature-label');
            if (!label) return;

            function update() {
                label.textContent = input.checked ? 'On' : 'Off';
                label.classList.toggle('text-success', input.checked);
                label.classList.toggle('text-muted', !input.checked);

                console.log(this);
            }
            update();
            input.addEventListener('change', update);
        });
    });
</script>
@endpush
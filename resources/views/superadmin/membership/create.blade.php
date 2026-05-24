@extends('adminlayout.layout')

@section('title', 'Create Membership')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Create New Plan</h1>
        <p class="text-muted mb-0">Add a new membership plan to EduTrack</p>
    </div>
</div>

<form action="#" method="POST" id="membership-form">
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
                        <input type="text" name="membership_name" id="membership_name" value="{{ old('membership_name') }}" class="form-control" placeholder="e.g. Basic, Standard, Premium, Diamond" required>
                    </div>
                    <div class="mb-3">
                        <label for="membership_price" class="form-label">Membership Price <span class="text-danger">*</span></label>
                        <input type="number" name="membership_price" id="membership_price" value="{{ old('membership_price') }}" class="form-control" placeholder="Rs 1000, 1500, 2000, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="students_limit" class="form-label">Students Limit</label>
                        <input type="number" name="students_limit" id="students_limit" value="{{ old('students_limit') }}" class="form-control" placeholder="50, 100, 150, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="teachers_limit" class="form-label">Teachers Limit</label>
                        <input type="number" name="teachers_limit" id="teachers_limit" value="{{ old('teachers_limit') }}" class="form-control" placeholder="10, 20, 30, etc.">
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
                        $features = [
                        ['name' => 'allowed_attendance', 'title' => 'Attendance'],
                        ['name' => 'allowed_daily_test', 'title' => 'Daily Test'],
                        ['name' => 'allowed_student_card', 'title' => 'Student Card'],
                        ['name' => 'allowed_whatsapp_message', 'title' => 'WhatsApp Message'],
                        ['name' => 'allowed_whatsapp_announcement', 'title' => 'WhatsApp Announcement'],
                        ];
                    @endphp

                    @if($features)
                        @foreach($features as $feature)
                            <div class="d-flex justify-content-between align-items-center px-3 py-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                                <div class="fw-semibold">{{ $feature['title'] }}</div>
                                <div class="form-check form-switch mb-0">
                                    <input type="hidden" name="{{ $feature['name'] }}" value="0">
                                    <input class="form-check-input feature-toggle" type="checkbox" name="{{ $feature['name'] }}" value="1" id="{{ $feature['name'] }}" role="switch">
                                    <label class="form-check-label small text-muted feature-label" for="{{ $feature['name'] }}">Off</label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this membership?')"><i class="bi bi-building me-1"></i> Save Membership</button>
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
            }
            update();
            input.addEventListener('change', update);
        });
    });
</script>
@endpush
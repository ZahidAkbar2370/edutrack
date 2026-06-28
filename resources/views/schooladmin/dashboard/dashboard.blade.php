@extends('schooladmin.layout.layout')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Dashboard</h1>
    <p class="text-muted mb-0">Overview of your school</p>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
        </div>
    @endif


    @php
       $membership = \App\Models\Membership::find(Auth::user()->membership_id);
    @endphp
    @if(!empty($membership) && $membership->membership_name == 'Free Trail')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    You are currently using the free trial version of the system.
    This trial will expire on
    <strong>{{ \Carbon\Carbon::parse(Auth::user()->membership_expiry_date)->format('Y-m-d') }}</strong>.
    Please upgrade to continue using all features. 
    <a href="https://wa.me/{{ $supportWhatsApp }}" target="_blank" rel="noopener noreferrer">Upgrade Now</a>
</div>
    @endif


<div class="row g-3 mb-4">

<div class="col-lg-6">

    <div class="row">
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-people fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-check fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Active</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['active_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-mortarboard fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Completed</div>
                    <div class="fs-4 fw-bold text-info">{{ number_format($stats['completed_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-slash-circle fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Banned</div>
                    <div class="fs-4 fw-bold text-danger">{{ number_format($stats['banned_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-dash fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Inactive</div>
                    <div class="fs-4 fw-bold text-secondary">{{ number_format($stats['inactive_students']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-journal-bookmark fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Classes</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_classes']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-clipboard-check fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Daily Tests</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_daily_tests']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="bi bi-person-badge fs-5"></i>
                </div>
                <div>
                    <div class="text-muted small">Teachers</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_teachers']) }}</div>
                </div>
            </div>
        </div>
    </div>


    </div>


</div>


<div class="col-lg-6">

<!-- <h2 class="h6 text-uppercase text-muted fw-semibold mb-3">WhatsApp Device</h2> -->
<div class="row g-3 mb-4">


<div class="col-lg-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                        <i class="bi bi-whatsapp"></i>
                    </span>
                    <div>
                        <h3 class="h6 fw-semibold mb-0">Device Information</h3>
                        <p class="text-muted small mb-0">Your registered WhatsApp device details</p>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">

                <form action="{{ URL::to('update-whatsapp-device') }}" method="POST">
                    @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-semibold mb-1">Device Number <small class="text-danger text-small">Whatsapp Number with country code (92xxxxxxxxxx). Example: 923200470584</small> </label>
                        <input type="number" name="whatsapp_device_number" id="whatsapp_device_number" class="form-control" placeholder="WhatsApp Number" required value="{{ old('whatsapp_device_number') ?? $whatsappDevice->wachat_device_number ?? '92' }}">

                        @error('whatsapp_device_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-outline-success btn-sm"
                    data-confirm-action
                    data-confirm-title="Update Device Information"
                    data-confirm-message="Are you sure you want to update this Device Information? Whatsapp Linked Device will be updated and you will need to scan the QR code again."
                    data-confirm-yes="Yes, Update"
                    data-confirm-yes-class="btn-success"
                    >
                        <i class="bi bi-save2 me-1"></i> Update Device Information
                    </button>
                </div>
                </form>

                <!-- <div class="alert alert-light border mb-0 d-flex gap-2 py-2 small">
                    <i class="bi bi-info-circle text-success flex-shrink-0 mt-1"></i>
                    <span>Use this device to send attendance, daily test, and fee alerts to parents via WhatsApp.</span>
                </div> -->
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                        <i class="bi bi-qr-code"></i>
                    </span>
                    <div>
                        <h3 class="h6 fw-semibold mb-0">Scan QR Code</h3>
                        <p class="text-muted small mb-0">Link your WhatsApp device to send alerts</p>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex flex-column align-items-center text-center py-4">
                <div class="whatsapp-qr-box mb-3 position-relative" id="whatsappQrBox">
                    <div class="whatsapp-qr-loader d-none" id="whatsappQrLoader">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted small mb-0 mt-2">Generating QR code...</p>
                    </div>
                    <div id="whatsappQrContent">
                        <div class="whatsapp-qr-placeholder">
                            <i class="bi bi-qr-code-scan fs-1 text-muted"></i>
                            <p class="text-muted small mb-0 mt-2">QR code will appear here</p>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mb-3 px-2" id="whatsappQrHelpText">
                    Open WhatsApp on your phone, go to <strong>Linked Devices</strong>, tap <strong>Link a Device</strong>, then scan this QR code.
                </p>
                <button type="button" class="btn btn-outline-danger btn-sm" id="refreshWhatsappQrBtn">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh QR Code
                </button>
            </div>
        </div>
    </div>

</div>

</div>


</div>

{{-- WhatsApp device: pass $whatsappQrCode, $whatsappDeviceId, $whatsappDeviceNumber from controller --}}


@endsection

@push('styles')
<style>
    .whatsapp-qr-box {
        width: 240px;
        height: 240px;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .whatsapp-qr-loader {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.92);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    #whatsappQrContent {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .whatsapp-qr-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        background: #fff;
    }

    .whatsapp-qr-placeholder {
        padding: 1rem;
    }

    .whatsapp-info-field {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        min-height: 48px;
        display: flex;
        align-items: center;
        word-break: break-all;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('[data-copy-text]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy-text');
            if (!text || !navigator.clipboard) return;

            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i class="bi bi-check2"></i>';
                setTimeout(function () {
                    btn.innerHTML = '<i class="bi bi-clipboard"></i>';
                }, 1500);
            });
        });
    });

    (function () {
        var qrContent = document.getElementById('whatsappQrContent');
        var qrLoader = document.getElementById('whatsappQrLoader');
        var qrHelpText = document.getElementById('whatsappQrHelpText');
        var refreshBtn = document.getElementById('refreshWhatsappQrBtn');
        var generateUrl = @json(url('ajax/generate-whatsapp-qr'));
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var hasDevice = @json(!empty($whatsappDevice?->wachat_device_id));

        function showLoader() {
            qrLoader.classList.remove('d-none');
        }

        function hideLoader() {
            qrLoader.classList.add('d-none');
        }

        function renderPlaceholder(message, iconClass, textClass) {
            qrContent.innerHTML =
                '<div class="whatsapp-qr-placeholder">' +
                    '<i class="bi ' + iconClass + ' fs-1 ' + textClass + '"></i>' +
                    '<p class="' + textClass + ' small mb-0 mt-2">' + message + '</p>' +
                '</div>';
        }

        function renderQrState(data) {
            if (data.success && data.connected) {
                renderPlaceholder(data.message, 'bi-check-circle-fill', 'text-success');
                qrHelpText.classList.add('d-none');
                return;
            }

            qrHelpText.classList.remove('d-none');

            if (data.success && data.qrcode) {
                qrContent.innerHTML =
                    '<img src="' + data.qrcode + '" alt="WhatsApp QR Code" class="whatsapp-qr-image">';
                return;
            }

            renderPlaceholder(data.message || 'Unable to generate QR code.', 'bi-exclamation-circle', 'text-warning');
        }

        function fetchWhatsappQr() {
            if (!hasDevice) {
                renderPlaceholder('Please save your WhatsApp device number first.', 'bi-info-circle', 'text-muted');
                qrHelpText.classList.add('d-none');
                return;
            }

            showLoader();
            refreshBtn.disabled = true;

            fetch(generateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    renderQrState(data);
                })
                .catch(function () {
                    renderPlaceholder('Something went wrong. Please try again.', 'bi-exclamation-triangle', 'text-danger');
                })
                .finally(function () {
                    hideLoader();
                    refreshBtn.disabled = false;
                });
        }

        refreshBtn.addEventListener('click', fetchWhatsappQr);

        if (hasDevice) {
            fetchWhatsappQr();
        }
    })();
</script>
@endpush

@extends('schooladmin.layout.layout')

@section('title', 'Student ID Card')

@section('content')
<link rel="stylesheet" href="{{ asset('css/student-cards.css') }}">

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Student ID Card</h1>
        <p class="text-muted mb-0">{{ $student->student_name }} — preview and download PNG</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" id="btnDownloadPng" class="btn btn-primary">
            <i class="bi bi-download me-1"></i> Download PNG
        </button>
        <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Student Detail
        </a>
    </div>
</div>

<div class="id-card-page-preview">
    <div id="studentCardPreview">
        @include('schooladmin.student.card.design', $cardData)
    </div>
</div>

{{-- Full card off-screen for clean PNG export --}}
<div class="id-card-export-hidden" aria-hidden="true">
    <div id="studentCardExport">
        @include('schooladmin.student.card.design', $cardData)
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.getElementById('btnDownloadPng').addEventListener('click', function () {
    var btn = this;
    var cardEl = document.getElementById('studentCardExport').firstElementChild;
    if (!cardEl) return;

    var original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';

    html2canvas(cardEl, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#fdf5f2'
    }).then(function (canvas) {
        var link = document.createElement('a');
        link.download = 'id-card-{{ Str::slug($student->student_name) }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        btn.disabled = false;
        btn.innerHTML = original;
    }).catch(function () {
        alert('Could not generate image. Please try again.');
        btn.disabled = false;
        btn.innerHTML = original;
    });
});
</script>
@endsection

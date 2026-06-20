@extends('schooladmin.layout.layout')

@section('title', 'Student Documents')

@push('styles')
<style>
    .document-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .document-preview {
        height: 220px;
        background: #f8f9fc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .document-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: pointer;
    }
    .document-preview .file-icon {
        font-size: 3.5rem;
        color: #adb5bd;
    }
</style>
@endpush

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Student Documents</h1>
        @if($student)
            <p class="text-muted mb-0">
                {{ $student->student_name }}
                @if($student->schoolClass || $student->section)
                    <span class="text-muted">—</span>
                    {{ $student->schoolClass->class_name ?? 'N/A' }}
                    @if($student->section)
                        ({{ $student->section->section_name }})
                    @endif
                @endif
            </p>
        @else
            <p class="text-muted mb-0">Uploaded files and images</p>
        @endif
    </div>
    

    @if($student)
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('student/show/' . $student->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Student Detail
        </a>
    </div>
    @endif
</div>


<div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h2 class="h6 mb-0 fw-semibold">
                <i class="bi bi-cloud-upload me-1"></i> Upload Document
            </h2>
        </div>
        <div class="card-body">
            <form action="{{ url('student/documents/' . $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="document_title" class="form-label">Document Title <span class="text-danger">*</span></label>
                        <input type="text"
                               name="document_title"
                               id="document_title"
                               class="form-control @error('document_title') is-invalid @enderror"
                               value="{{ old('document_title') }}"
                               placeholder="e.g. Birth Certificate, CNIC Copy"
                               required>
                        @error('document_title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="document_file" class="form-label">Select Document <span class="text-danger">*</span> <small>Allowed: JPG, PNG, JPEG</small></label>
                        <input type="file"
                               name="document_file"
                               id="document_file"
                               class="form-control @error('document_file') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/gif,image/webp,application/pdf"
                               required>
                        @error('document_file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload me-1"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@if(!$student)
    <div class="alert alert-danger">Student not found.</div>
@elseif($documents->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder2-open display-4 text-muted"></i>
            <p class="text-muted mt-3 mb-0">No documents uploaded for this student yet.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($documents as $document)
            @php
                $fileUrl = \Illuminate\Support\Str::startsWith($document->document_file, ['http://', 'https://'])
                    ? $document->document_file
                    : asset($document->document_file);

                $extension = strtolower(pathinfo($document->document_file, PATHINFO_EXTENSION));
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                $downloadName = \Illuminate\Support\Str::slug($document->document_title) . ($extension ? '.' . $extension : '');
            @endphp

            <div class="col-sm-6 col-lg-2 col-xl-2">
                <div class="card document-card shadow-sm h-100">
                    <div class="card-header bg-light py-2">
                        <h2 class="h6 mb-0 fw-semibold text-truncate" title="{{ $document->document_title }}">
                            <i class="bi bi-file-earmark-text me-1 text-primary"></i>
                            {{ $document->document_title }}
                        </h2>
                    </div>

                    <div class="card-body p-0 document-preview">
                            <a href="{{ $fileUrl }}" target="_blank"><img src="{{ $fileUrl }}"
                                 alt="{{ $document->document_title }}" class="img-fluid"></a>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between align-items-center gap-2 py-2">
                       <a href="{{ $fileUrl }}" target="_blank">
                        <button type="button"
                                    class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> View
                            </button>
                            </a>

                        <a href="{{ $fileUrl }}"
                           download="{{ $downloadName }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
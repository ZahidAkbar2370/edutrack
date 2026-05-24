@extends('adminlayout.layout')

@section('title', 'School Detail')

@section('content')

<div class="mb-4">
   <h1 class="h3 fw-bold">School Detail</h1>
    <p class="text-muted mb-0">School ID: {{ $id ?? 1 }} (demo)</p>
</div>

<div class="card shadow-sm">
    <div class="card-body text-muted">School detail page — connect to database later.</div>
</div>

@endsection

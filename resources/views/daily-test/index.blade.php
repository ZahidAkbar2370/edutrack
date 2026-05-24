@extends('adminlayout.layout')

@section('title', 'Daily Tests')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">Daily Tests</h1>
        <p class="text-muted mb-0">Test records by class and section</p>
    </div>
    <a href="{{ url('daily-test/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Daily Test
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Class & Section</th>
                        <th>Test Name</th>
                        <th>Test Date</th>
                        <th>Teacher Name</th>
                        <th>Subject</th>
                        <th class="text-center">Total Marks</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testGroups as $key => $group)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-medium">
                                <span class="fw-medium">{{ $classes[$group->class_id]->class_name ?? 'N/A' }}</span> - <span class="text-muted small">({{ $sections[$group->section_id]->section_name ?? 'N/A' }})</span>
                            </td>
                            <td>{{ $group->daily_test_name }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($group->daily_test_date)->format('d M Y') }}</td>
                            <td>{{ $teachers[$group->teacher_id]->teacher_name ?? 'N/A' }}</td>
                            <td>{{ $group->subject ?? '—' }}</td>
                            <td class="text-center fw-semibold">{{ $group->total_marks }}</td>
                            <td class="text-center">
                                <a href="{{ route('daily-test.show', [
                                    'class_id' => $group->class_id,
                                    'section_id' => $group->section_id,
                                    'daily_test_date' => $group->daily_test_date,
                                    'daily_test_name' => $group->daily_test_name,
                                    'teacher_id' => $group->teacher_id,
                                    'subject' => $group->subject,
                                ]) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No daily test records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

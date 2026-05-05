@extends('layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="page-heading mb-4">
    <div class="page-label">Analytics</div>
    <h1 class="page-title">HR Reports.</h1>
</div>

<p class="text-muted mb-4">Summary of leave balances and department requests for {{ $year }}.</p>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Employees by Department</h5>
                <ul class="list-group list-group-flush">
                    @forelse($departments as $department)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $department->department }}
                            <span class="badge bg-primary rounded-pill">{{ $department->employees }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No departments found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Leave Requests by Department</h5>
                <ul class="list-group list-group-flush">
                    @forelse($departmentRequests as $summary)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $summary->department }}
                            <span class="badge bg-secondary rounded-pill">{{ $summary->total_requests }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No requests found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="page-card">
    <div class="card-body">
        <h5 class="card-title mb-4">Leave Balances</h5>

        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="border-bottom">
                    <tr>
                        <th class="py-3">Employee</th>
                        <th class="py-3">Leave Type</th>
                        <th class="py-3">Year</th>
                        <th class="py-3">Used Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td class="fw-semibold">{{ optional($balance->user)->name ?? 'Unknown' }}</td>
                            <td>{{ optional($balance->leaveType)->name ?? 'Unknown' }}</td>
                            <td>{{ $balance->year }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $balance->used_days }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No leave balances available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

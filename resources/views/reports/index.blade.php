@extends('layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="mb-4">
    <h1 class="h3">HR Dashboard</h1>
    <p class="text-muted">Summary of leave balances and department requests for {{ $year }}.</p>
</div>

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

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Leave Balances</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Year</th>
                        <th>Used Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td>{{ optional($balance->user)->name ?? 'Unknown' }}</td>
                            <td>{{ optional($balance->leaveType)->name ?? 'Unknown' }}</td>
                            <td>{{ $balance->year }}</td>
                            <td>{{ $balance->used_days }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No leave balances available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

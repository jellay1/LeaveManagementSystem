@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <p class="text-uppercase text-muted small mb-1">Workspace</p>
            <h6 class="text-uppercase fw-semibold mb-2">Leave Management</h6>
            <p class="text-uppercase text-muted small mb-1">Hello, {{ strtoupper(auth()->user()->name) }}</p>
            <h1 class="display-5 fw-bold mb-2">HR Command Center.</h1>
            <p class="text-muted mb-0">Monitor leave approvals, balances, and team activity from one place.</p>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="btn btn-dark btn-lg px-4">Apply Leave</a>
    </div>

    <div class="card border shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-0 text-center text-md-start">
                <div class="col-12 col-md-6 col-xl-3 border-end border-bottom border-md-bottom-0 p-4">
                    <div class="text-uppercase text-muted small mb-2">Pending Approvals</div>
                    <h2 class="fw-bold mb-1">{{ $pendingApprovals ?? 0 }}</h2>
                    <div class="text-muted">across organization</div>
                </div>
                <div class="col-12 col-md-6 col-xl-3 border-end border-bottom border-md-bottom-0 p-4">
                    <div class="text-uppercase text-muted small mb-2">On Leave Today</div>
                    <h2 class="fw-bold mb-1">{{ $onLeaveToday ?? 0 }}</h2>
                    <div class="text-muted">approved absences</div>
                </div>
                <div class="col-12 col-md-6 col-xl-3 border-end border-bottom border-md-bottom-0 p-4">
                    <div class="text-uppercase text-muted small mb-2">Approved (YTD)</div>
                    <h2 class="fw-bold mb-1">{{ $approvedYtd ?? 0 }}</h2>
                    <div class="text-muted">cumulative</div>
                </div>
                <div class="col-12 col-md-6 col-xl-3 p-4">
                    <div class="text-uppercase text-muted small mb-2">Employees</div>
                    <h2 class="fw-bold mb-1">{{ $employees ?? 0 }}</h2>
                    <div class="text-muted">active headcount</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Your Leave Balance</h5>
                            <p class="text-muted small mb-0">Current year balance across leave categories.</p>
                        </div>
                        <div class="text-muted small">{{ $totalUsed ?? 0 }}/{{ $totalAllocated ?? 0 }} used</div>
                    </div>

                    @forelse($balances as $balance)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">{{ $balance['name'] }}</span>
                                <span class="small text-muted">{{ $balance['used'] }}/{{ $balance['allocation'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $balance['percent'] }}%;" aria-valuenow="{{ $balance['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No leave balance records found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100 bg-dark text-white">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-2">Year Summary</p>
                    <h1 class="display-4 fw-bold mb-3">{{ $remainingDays ?? 0 }}</h1>
                    <p class="text-white-50 mb-4">days remaining</p>

                    <div class="d-flex justify-content-between text-white-50 small">
                        <div>
                            <div class="fw-bold">Used</div>
                            <div>{{ $totalUsed ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="fw-bold">Allocation</div>
                            <div>{{ $totalAllocated ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
                <div>
                    <h5 class="mb-1">Recent Requests</h5>
                    <p class="text-muted small mb-0">Latest leave activity for your account or team.</p>
                </div>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary btn-sm">View all</a>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="text-uppercase text-muted small border-bottom">
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->leaveType?->name ?? '-' }}</td>
                                <td>{{ $request->start_date }} &rarr; {{ $request->end_date }}</td>
                                <td>{{ $request->days ?? 0 }}</td>
                                <td>{{ $request->reason ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }} text-capitalize">{{ $request->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No recent requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Department Summary</h5>
                    <p class="text-muted small mb-0">Leave activity broken down by department.</p>
                </div>
            </div>

            @if($departmentSummary->isEmpty())
                <div class="text-center text-muted py-4">No department summary available.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-uppercase text-muted small border-bottom">
                            <tr>
                                <th>Department</th>
                                <th>Pending</th>
                                <th>Approved</th>
                                <th>Rejected</th>
                                <th class="text-end">Total Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentSummary as $summary)
                                <tr>
                                    <td>{{ $summary['department'] }}</td>
                                    <td>{{ $summary['pending'] }}</td>
                                    <td>{{ $summary['approved'] }}</td>
                                    <td>{{ $summary['rejected'] }}</td>
                                    <td class="text-end">{{ $summary['total_days'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

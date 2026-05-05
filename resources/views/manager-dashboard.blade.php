@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 2.5rem;
    }
    .greeting {
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .main-title {
        font-size: 3rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
    }
    
    .stats-container {
        border: 1px solid #212529;
        margin-bottom: 3rem;
        display: flex;
        flex-wrap: wrap;
    }
    .stat-card {
        padding: 2rem;
        border-right: 1px solid #212529;
        flex: 1;
        min-width: 200px;
    }
    .stat-card:last-child {
        border-right: none;
    }
    .stat-card:nth-child(1), .stat-card:nth-child(2) {
        flex: 1.2;
    }
    .stat-label {
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #495057;
    }
    .stat-value {
        font-size: 3rem;
        font-weight: 400;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .stat-desc {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
    }

    .balance-section {
        display: flex;
        gap: 2rem;
        margin-bottom: 3rem;
        align-items: flex-start;
    }
    .balance-list-wrapper {
        flex: 2;
        border: 1px solid #dee2e6;
        padding: 2rem;
    }
    .balance-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .balance-title {
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
        margin: 0;
    }
    .balance-used-label {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .balance-item {
        display: flex;
        justify-content: space-between;
        padding: 1.5rem 0;
        border-bottom: 1px solid #e9ecef;
        align-items: center;
    }
    .balance-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .balance-name {
        font-weight: 500;
        color: #212529;
    }
    .balance-numbers {
        font-weight: 500;
        color: #495057;
    }

    .year-summary {
        flex: 1;
        background-color: #0f0f0f;
        color: #fff;
        padding: 2.5rem 2rem;
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }
    .summary-title {
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 2rem;
    }
    .remaining-value {
        font-size: 4rem;
        font-weight: 500;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .remaining-label {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 3rem;
    }
    .summary-stats {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #333;
        padding-top: 2rem;
        margin-top: auto;
    }
    .summary-stat-label {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.7rem;
        color: #888;
        margin-bottom: 0.5rem;
    }
    .summary-stat-value {
        font-size: 1.5rem;
        font-weight: 500;
    }

    .recent-requests-wrapper {
        border: 1px solid #dee2e6;
        padding: 2rem;
    }
    .recent-requests-title {
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 2rem;
    }
    
    .btn-apply-leave-top {
        background: #000;
        color: #fff;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }
    .btn-apply-leave-top:hover {
        background: #222;
        color: #fff;
    }
    
    @media (max-width: 991px) {
        .balance-section {
            flex-direction: column;
        }
        .year-summary {
            width: 100%;
        }
        .stat-card {
            border-right: none;
            border-bottom: 1px solid #212529;
            width: 100%;
        }
        .stat-card:last-child {
            border-bottom: none;
        }
    }
</style>

<div class="container-fluid px-0">
    @if ($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="dashboard-header d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <div class="greeting">Hello, {{ strtoupper(auth()->user()->name) }}</div>
            <h1 class="main-title">Team Overview.</h1>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="btn-apply-leave-top">
            Apply Leave &rarr;
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-label">Pending Approvals</div>
            <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
            <p class="stat-desc">across organization</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">On Leave Today</div>
            <div class="stat-value">{{ $onLeaveToday ?? 0 }}</div>
            <p class="stat-desc">approved absences</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved (YTD)</div>
            <div class="stat-value">{{ $approvedYtd ?? 0 }}</div>
            <p class="stat-desc">cumulative</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">Employees</div>
            <div class="stat-value">{{ $activeEmployees ?? 0 }}</div>
            <p class="stat-desc">active headcount</p>
        </div>
    </div>

    <div class="balance-section">
        <!-- Your Leave Balance -->
        <div class="balance-list-wrapper">
            <div class="balance-header">
                <h2 class="balance-title">Your Leave Balance</h2>
                <div class="balance-used-label">{{ $totalUsed ?? 0 }}/{{ $totalAllocation ?? 0 }} used</div>
            </div>
            
            @forelse($myBalances as $balance)
                <div class="balance-item">
                    <div class="balance-name">{{ $balance->leaveType->name ?? 'Unknown' }}</div>
                    <div class="balance-numbers">
                        @php
                            $allocation = $balance->leaveType->annual_allocation ?? 0;
                            $remaining = max(0, $allocation - $balance->used_days);
                        @endphp
                        {{ $remaining }}/{{ $allocation }}
                    </div>
                </div>
            @empty
                <div class="balance-item">
                    <div class="balance-name text-muted">No leave balances found.</div>
                </div>
            @endforelse
        </div>

        <!-- Year Summary -->
        <div class="year-summary">
            <div class="summary-title">Year Summary</div>
            
            @php
                $overallRemaining = max(0, ($totalAllocation ?? 0) - ($totalUsed ?? 0));
            @endphp
            
            <div class="remaining-value">{{ $overallRemaining }}</div>
            <div class="remaining-label">days remaining</div>
            
            <div class="summary-stats">
                <div>
                    <div class="summary-stat-label">Used</div>
                    <div class="summary-stat-value">{{ $totalUsed ?? 0 }}</div>
                </div>
                <div>
                    <div class="summary-stat-label">Allocation</div>
                    <div class="summary-stat-value">{{ $totalAllocation ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="recent-requests-wrapper">
        <h2 class="recent-requests-title">Recent Requests</h2>
        
        @forelse($pendingRequests->take(5) as $request)
            <div class="border-bottom py-3 last:border-bottom-0">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-2">
                            <strong>{{ $request->user->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small">
                            {{ Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - {{ Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                        </span>
                        <span class="ms-2 badge bg-light text-dark border">{{ $request->leaveType->name ?? 'Leave' }}</span>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <form action="{{ route('manager-dashboard.approve', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-dark">Approve</button>
                            </form>
                            <form action="{{ route('manager-dashboard.reject', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-dark">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted py-3">No pending leave requests.</div>
        @endforelse
    </div>
</div>

<style>
    .last\:border-bottom-0:last-child {
        border-bottom: none !important;
    }
</style>
@endsection

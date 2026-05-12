@extends('layouts.app')

@section('title', 'Dashboard - Your Time Off')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <p class="text-uppercase text-muted small mb-1">Hello, {{ strtoupper(auth()->user()->name) }}</p>
            <h1 class="display-5 fw-bold mb-2">Your Time Off.</h1>
            <p class="text-muted mb-0">Manage your leave balance and submit requests effortlessly.</p>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="btn btn-dark btn-lg px-4">
            <i class="bi bi-plus-lg me-2"></i>Apply Leave
        </a>
    </div>

    <!-- Leave Balance and Year Summary -->
    <div class="row g-3 mb-4">
        <!-- Your Leave Balance -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-1">Your Leave Balance</h5>
                            <p class="text-muted small mb-0">Current year allocation and usage.</p>
                        </div>
                        <div class="badge bg-light text-dark">{{ $totalUsed ?? 0 }}/{{ $totalAllocated ?? 0 }} used</div>
                    </div>

                    @forelse($balances as $balance)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0" style="font-size: 0.95rem;">{{ $balance['name'] }}</h6>
                                <span class="text-muted small">{{ $balance['used'] }}/{{ $balance['allocation'] }}</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: {{ $balance['percent'] }}%; background-color: {{ $balance['percent'] > 80 ? '#dc3545' : ($balance['percent'] > 50 ? '#ffc107' : '#28a745') }};"
                                     aria-valuenow="{{ $balance['percent'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No leave balance records found. Please contact HR.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Year Summary -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white;">
                <div class="card-body d-flex flex-column justify-content-between" style="min-height: 300px;">
                    <div>
                        <p class="text-uppercase text-white-50 small mb-3" style="letter-spacing: 0.05em;">Year Summary</p>
                        <h1 class="display-3 fw-bold mb-3">{{ $remainingDays ?? 0 }}</h1>
                        <p class="text-white-50 mb-0">days remaining</p>
                    </div>

                    <div class="row g-3 pt-3 border-top border-white-10">
                        <div class="col-6">
                            <div class="text-white-50 small mb-1" style="font-size: 0.85rem;">USED</div>
                            <div class="h5 fw-bold mb-0">{{ $totalUsed ?? 0 }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-white-50 small mb-1" style="font-size: 0.85rem;">ALLOCATION</div>
                            <div class="h5 fw-bold mb-0">{{ $totalAllocated ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <div>
                    <h5 class="card-title mb-1">Recent Requests</h5>
                    <p class="text-muted small mb-0">Your latest leave applications and their statuses.</p>
                </div>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary btn-sm">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="text-uppercase text-muted small border-bottom">
                        <tr>
                            <th style="font-weight: 600;">Type</th>
                            <th style="font-weight: 600;">Dates</th>
                            <th style="font-weight: 600;">Days</th>
                            <th style="font-weight: 600;">Reason</th>
                            <th style="font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>
                                    <span class="fw-500">{{ $request->leaveType?->name ?? '-' }}</span>
                                </td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} 
                                    <i class="bi bi-arrow-right mx-2" style="font-size: 0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $request->days ?? 0 }} day{{ $request->days != 1 ? 's' : '' }}</span>
                                </td>
                                <td class="text-muted small">{{ Str::limit($request->reason ?? '—', 25) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'pending' => 'warning',
                                            'cancelled' => 'secondary',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }} text-capitalize">
                                        {{ $request->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <p class="mb-0">No leave requests yet.</p>
                                    <small>Start by clicking the "Apply Leave" button above.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recentRequests->count() > 0)
                <div class="d-flex justify-content-center pt-3 border-top">
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-link btn-sm">View all requests →</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .display-3 {
        font-size: 3.5rem;
    }
    
    .card {
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection

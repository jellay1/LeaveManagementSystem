@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="page-heading mb-4 d-flex justify-content-between align-items-center">
    <div>
        <div class="page-label">Analytics</div>
        <h1 class="page-title">Reports.</h1>
    </div>
    <a href="{{ route('reports.export-csv') }}" class="btn btn-dark">
        <i class="bi bi-download me-2"></i>Export CSV
    </a>
</div>

{{-- ── Stat Cards ─────────────────────────────────────────────── --}}
<div class="card border mb-4" style="border-radius:.85rem;overflow:hidden;">
    <div class="row g-0 text-start" id="report-stats">

        <div class="col-6 col-md-3 p-4 border-end border-bottom border-md-bottom-0">
            <div class="text-uppercase text-muted small mb-2" style="letter-spacing:.18em;font-size:.72rem;">Pending</div>
            <div class="fw-bold" style="font-size:2rem;line-height:1;">{{ $pending }}</div>
        </div>

        <div class="col-6 col-md-3 p-4 border-end border-bottom border-md-bottom-0">
            <div class="text-uppercase text-muted small mb-2" style="letter-spacing:.18em;font-size:.72rem;">Approved</div>
            <div class="fw-bold" style="font-size:2rem;line-height:1;">{{ $approved }}</div>
        </div>

        <div class="col-6 col-md-3 p-4 border-end">
            <div class="text-uppercase text-muted small mb-2" style="letter-spacing:.18em;font-size:.72rem;">Rejected</div>
            <div class="fw-bold" style="font-size:2rem;line-height:1;">{{ $rejected }}</div>
        </div>

        <div class="col-6 col-md-3 p-4">
            <div class="text-uppercase text-muted small mb-2" style="letter-spacing:.18em;font-size:.72rem;">On Leave Today</div>
            <div class="fw-bold" style="font-size:2rem;line-height:1;">{{ $onLeaveToday }}</div>
        </div>

    </div>
</div>

{{-- ── Department Breakdown Chart ─────────────────────────────── --}}
<div class="card border mb-4" style="border-radius:.85rem;overflow:hidden;">
    <div class="card-body p-4">
        <div class="text-uppercase text-muted small mb-3" style="letter-spacing:.18em;font-size:.72rem;">Department Breakdown</div>
        <div style="position:relative;height:240px;">
            <canvas id="deptChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Department Summary Table ──────────────────────────────── --}}
<div class="card border" style="border-radius:.85rem;overflow:hidden;">
    <div class="card-body p-0">
        <div class="px-4 pt-4 pb-2">
            <div class="text-uppercase text-muted small" style="letter-spacing:.18em;font-size:.72rem;">Department Summary</div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.88rem;">
                <thead>
                    <tr class="border-top border-bottom">
                        <th class="px-4 py-3 text-uppercase text-muted fw-semibold" style="letter-spacing:.14em;font-size:.72rem;font-weight:600;">Department</th>
                        <th class="py-3 text-uppercase text-muted fw-semibold" style="letter-spacing:.14em;font-size:.72rem;">Pending</th>
                        <th class="py-3 text-uppercase text-muted fw-semibold" style="letter-spacing:.14em;font-size:.72rem;">Approved</th>
                        <th class="py-3 text-uppercase text-muted fw-semibold" style="letter-spacing:.14em;font-size:.72rem;">Rejected</th>
                        <th class="py-3 text-uppercase text-muted fw-semibold" style="letter-spacing:.14em;font-size:.72rem;">Total Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentSummary as $row)
                        <tr>
                            <td class="px-4 py-3 fw-semibold">{{ $row['department'] }}</td>
                            <td class="py-3">
                                @if($row['pending'] > 0)
                                    <span style="color:#d97706;">{{ $row['pending'] }}</span>
                                @else
                                    {{ $row['pending'] }}
                                @endif
                            </td>
                            <td class="py-3">{{ $row['approved'] }}</td>
                            <td class="py-3">
                                @if($row['rejected'] > 0)
                                    <span style="color:#dc3545;">{{ $row['rejected'] }}</span>
                                @else
                                    {{ $row['rejected'] }}
                                @endif
                            </td>
                            <td class="py-3">
                                @if($row['total_days'] > 0)
                                    <span style="color:#dc3545;">{{ $row['total_days'] }}</span>
                                @else
                                    {{ $row['total_days'] }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No department data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Chart.js ──────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels   = @json($deptChart->pluck('department'));
    const pending  = @json($deptChart->pluck('pending'));
    const approved = @json($deptChart->pluck('approved'));
    const rejected = @json($deptChart->pluck('rejected'));

    const ctx = document.getElementById('deptChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Approved',
                    data: approved,
                    backgroundColor: '#22c55e',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
                {
                    label: 'Pending',
                    data: pending,
                    backgroundColor: '#eab308',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
                {
                    label: 'Rejected',
                    data: rejected,
                    backgroundColor: '#ef4444',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#6c757d',
                        font: { size: 11 },
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,.05)',
                        drawBorder: false,
                    },
                    border: { display: false, dash: [4, 4] },
                    ticks: {
                        color: '#6c757d',
                        precision: 0,
                        font: { size: 11 },
                    },
                },
            },
        },
    });
})();
</script>
@endsection

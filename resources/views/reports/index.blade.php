@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    {{-- ── Page Header ────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-1">Analytics</div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Reports.</h1>
        </div>
        @if(auth()->user()->hasRole('hr_admin'))
            <a href="{{ route('reports.export-csv') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-slate-800 transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export CSV</span>
            </a>
        @endif
    </div>

    {{-- ── Stat Cards Grid ───────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        {{-- Pending Approvals --}}
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Pending Approvals</div>
                <div
                    class="p-2.5 bg-amber-50 rounded-xl text-black group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $pending }}</div>
            <div class="text-[11px] text-slate-500 font-medium">
                @if(auth()->user()->hasRole('manager'))
                    in your department
                @else
                    across organization
                @endif
            </div>
            <div
                class="absolute -bottom-6 -right-6 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors duration-500">
            </div>
        </div>

        {{-- On Leave Today --}}
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">On Leave Today</div>
                <div
                    class="p-2.5 bg-emerald-50 rounded-xl text-black group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $onLeaveToday }}</div>
            <div class="text-[11px] text-slate-500 font-medium">
                @if(auth()->user()->hasRole('manager'))
                    department absences
                @else
                    approved absences
                @endif
            </div>
            <div
                class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors duration-500">
            </div>
        </div>

        {{-- Approved (YTD) --}}
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Approved (YTD)</div>
                <div class="p-2.5 bg-blue-50 rounded-xl text-black group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $approved }}</div>
            <div class="text-[11px] text-slate-500 font-medium">
                @if(auth()->user()->hasRole('manager'))
                    department cumulative
                @else
                    cumulative total
                @endif
            </div>
            <div
                class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors duration-500">
            </div>
        </div>

        {{-- Total Employees --}}
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total Employees</div>
                <div
                    class="p-2.5 bg-purple-50 rounded-xl text-black group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $totalEmployees }}</div>
            <div class="text-[11px] text-slate-500 font-medium">
                @if(auth()->user()->hasRole('manager'))
                    department headcount
                @else
                    active headcount
                @endif
            </div>
            <div
                class="absolute -bottom-6 -right-6 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-colors duration-500">
            </div>
        </div>
    </div>

    {{-- ── Department Breakdown Chart ─────────────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden mb-8 shadow-sm">
        <div class="p-8">
            <div class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em] mb-8">Department Breakdown</div>
            <div class="h-[320px] w-full">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Department Summary Table ──────────────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="px-8 py-6 border-b border-slate-100">
            <div class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em]">Department Summary</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th
                            class="px-8 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] border-b border-slate-200">
                            Department</th>
                        <th
                            class="px-8 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] border-b border-slate-200">
                            Pending</th>
                        <th
                            class="px-8 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] border-b border-slate-200">
                            Approved</th>
                        <th
                            class="px-8 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] border-b border-slate-200">
                            Rejected</th>
                        <th
                            class="px-8 py-4 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em] border-b border-slate-200">
                            Total Days</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($departmentSummary as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-8 py-5 font-bold text-slate-900">{{ $row['department'] }}</td>
                            <td
                                class="px-8 py-5 text-sm font-medium {{ $row['pending'] > 0 ? 'text-amber-600' : 'text-slate-600' }}">
                                {{ $row['pending'] }}
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-slate-600">
                                {{ $row['approved'] }}
                            </td>
                            <td
                                class="px-8 py-5 text-sm font-medium {{ $row['rejected'] > 0 ? 'text-red-600' : 'text-slate-600' }}">
                                {{ $row['rejected'] }}
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-900">
                                {{ $row['total_days'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 text-sm italic">
                                No department data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Chart.js Implementation ────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const labels = @json($deptChart->pluck('department'));
            const pending = @json($deptChart->pluck('pending'));
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
                            backgroundColor: '#10b981', // Emerald 500
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.6,
                        },
                        {
                            label: 'Pending',
                            data: pending,
                            backgroundColor: '#f59e0b', // Amber 500
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.6,
                        },
                        {
                            label: 'Rejected',
                            data: rejected,
                            backgroundColor: '#ef4444', // Red 500
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.6,
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
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 8,
                            displayColors: true,
                        },
                    },
                    scales: {
                        x: {
                            stacked: true, // Use stacking to center the label under the bars even if only one bar exists
                            grid: { display: false },
                            border: {
                                display: true,
                                color: '#e2e8f0', // Slate 200
                                width: 1
                            },
                            ticks: {
                                color: '#475569', // Darker Slate 600
                                font: { size: 11, weight: '600' },
                                padding: 12
                            },
                        },
                        y: {
                            stacked: true, // Keep stacking consistent
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            border: {
                                display: true,
                                color: '#e2e8f0', // Slate 200
                                width: 1
                            },
                            ticks: {
                                color: '#94a3b8',
                                precision: 0,
                                font: { size: 10, weight: '600' },
                                padding: 10
                            },
                        },
                    },
                },
            });
        })();
    </script>
@endsection
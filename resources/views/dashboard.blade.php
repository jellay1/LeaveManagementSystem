@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Workspace</p>
            <h1 class="text-4xl font-bold text-slate-900 mb-3">HR Command Center</h1>
            <p class="text-slate-600">Monitor leave approvals, balances, and team activity from one place.</p>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
            Apply Leave
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Pending Approvals --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Pending Approvals</div>
                <div class="p-2.5 bg-amber-50 rounded-xl text-amber-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $pendingApprovals ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 font-medium">across organization</div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors duration-500"></div>
        </div>

        {{-- On Leave Today --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">On Leave Today</div>
                <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $onLeaveToday ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 font-medium">approved absences</div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-colors duration-500"></div>
        </div>

        {{-- Approved (YTD) --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Approved (YTD)</div>
                <div class="p-2.5 bg-blue-50 rounded-xl text-blue-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $approvedYtd ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 font-medium">cumulative total</div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors duration-500"></div>
        </div>

        {{-- Total Employees --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total Employees</div>
                <div class="p-2.5 bg-purple-50 rounded-xl text-purple-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $employees ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 font-medium">active headcount</div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-colors duration-500"></div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Leave Balance Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Your Leave Balance</h2>
                    <p class="text-sm text-slate-600">Current year balance across leave categories.</p>
                </div>

                <div class="space-y-4">
                    @forelse($balances as $balance)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-slate-700">{{ $balance['name'] }}</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $balance['used'] }}/{{ $balance['allocation'] }} days</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all" style="width: {{ $balance['percent'] }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-8 text-slate-600">No leave balance records found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Year Summary Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-6 shadow-lg text-white">
            <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest mb-3">Year Summary</p>
            <div class="mb-6">
                <p class="text-4xl font-bold">{{ $remainingDays ?? 0 }}</p>
                <p class="text-slate-400 text-sm mt-2">days remaining</p>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-700">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Used</p>
                    <p class="text-2xl font-bold">{{ $totalUsed ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Allocation</p>
                    <p class="text-2xl font-bold">{{ $totalAllocated ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-1">Recent Requests</h2>
                    <p class="text-sm text-slate-600">Latest leave activity for your account or team.</p>
                </div>
                <a href="{{ route('leave-requests.index') }}" class="text-sm font-semibold text-slate-900 hover:text-black">View all →</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentRequests as $request)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $request->leaveType?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->start_date }} → {{ $request->end_date }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $request->days ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->reason ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($request->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                @elseif($request->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No recent requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Department Summary Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-1">Department Summary</h2>
                    <p class="text-sm text-slate-600">Leave activity broken down by department.</p>
                </div>
                <a href="{{ route('reports.export-csv') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV
                </a>
            </div>
        </div>

        @if($departmentSummary->isEmpty())
            <div class="p-8 text-center text-slate-500">No department summary available.</div>
        @else
            <!-- Stats Cards -->
            <div class="px-6 py-4 border-b border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Pending</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $departmentSummary->sum('pending') }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Approved</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $departmentSummary->sum('approved') }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Rejected</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $departmentSummary->sum('rejected') }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">On Leave</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $onLeaveToday ?? 0 }}</p>
                </div>
            </div>

            <!-- Department Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Pending</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Approved</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Rejected</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Total Days</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($departmentSummary as $summary)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $summary['department'] }}</td>
                                <td class="px-6 py-4 text-sm text-amber-700 font-semibold">{{ $summary['pending'] }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-700 font-semibold">{{ $summary['approved'] }}</td>
                                <td class="px-6 py-4 text-sm text-red-700 font-semibold">{{ $summary['rejected'] }}</td>
                                <td class="px-6 py-4 text-sm text-right font-semibold text-slate-900">{{ $summary['total_days'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

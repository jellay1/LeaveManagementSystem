@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Hello, {{ auth()->user()->name }}</p>
            <h1 class="text-4xl font-bold text-slate-900">Team Overview</h1>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
            Apply Leave →
        </a>
    </div>

    <!-- Stats Cards Grid -->
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
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $pendingCount ?? 0 }}</div>
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
            <div class="text-4xl font-bold text-slate-900 leading-none mb-2 tracking-tight">{{ $activeEmployees ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 font-medium">active headcount</div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-colors duration-500"></div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Your Leave Balance -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Your Leave Balance</h2>
                    <p class="text-sm text-slate-600">{{ $totalUsed ?? 0 }}/{{ $totalAllocation ?? 0 }} days used</p>
                </div>

                <div class="space-y-4">
                    @forelse($myBalances as $balance)
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200 last:border-b-0 last:pb-0">
                            <span class="text-sm font-medium text-slate-700">{{ $balance->leaveType->name ?? 'Unknown' }}</span>
                            <span class="text-sm font-semibold text-slate-900">
                                @php
                                    $allocation = $balance->leaveType->annual_allocation ?? 0;
                                    $remaining = max(0, $allocation - $balance->used_days);
                                @endphp
                                {{ $balance->used_days ?? 0 }}/{{ $allocation }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-slate-600 py-8">No leave balances found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Year Summary -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-6 shadow-lg text-white">
            <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest mb-4">Year Summary</p>
            <div class="mb-6">
                @php
                    $overallRemaining = max(0, ($totalAllocation ?? 0) - ($totalUsed ?? 0));
                @endphp
                <p class="text-4xl font-bold">{{ $overallRemaining }}</p>
                <p class="text-slate-400 text-sm mt-2">days remaining</p>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-700">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Used</p>
                    <p class="text-2xl font-bold">{{ $totalUsed ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Allocation</p>
                    <p class="text-2xl font-bold">{{ $totalAllocation ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Recent Requests</h2>
            <p class="text-sm text-slate-600 mt-1">Pending leave requests from your team.</p>
        </div>

        <div class="divide-y divide-slate-200">
            @forelse($pendingRequests->take(5) as $request)
                <div class="p-6 hover:bg-slate-50 transition">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
                        <div class="sm:col-span-1">
                            <p class="text-sm font-semibold text-slate-900">{{ $request->user->name }}</p>
                            <p class="text-xs text-slate-600 mt-1">{{ $request->leaveType->name ?? 'Leave' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-sm text-slate-700">
                                {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-slate-600 mt-1">{{ $request->days ?? 0 }} days</p>
                        </div>
                        <div class="flex gap-2 justify-start sm:justify-end">
                            <form action="{{ route('manager-dashboard.approve', $request) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('manager-dashboard.reject', $request) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold rounded-lg transition">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-600">
                    <p class="text-sm">No pending leave requests.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

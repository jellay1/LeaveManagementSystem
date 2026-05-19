@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">
            Hello, {{ auth()->user()->name }}
            @if(auth()->user()->employee && auth()->user()->employee->position)
                • {{ auth()->user()->employee->position }}
            @endif
        </p>
        <h1 class="text-4xl font-bold text-slate-900">Your Time Off</h1>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Leave Balance Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Your Leave Balance</h2>
                
                <div class="space-y-5">
                    @forelse($balances as $balance)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-slate-700">{{ $balance['name'] }}</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $balance['used'] }}/{{ $balance['allocation'] }} used</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                                @php
                                    $colors = [
                                        'Vacation' => 'bg-slate-900',
                                        'Sick Leave' => 'bg-emerald-500',
                                        'Emergency' => 'bg-red-500',
                                        'Personal' => 'bg-amber-500',
                                    ];
                                    $colorClass = $colors[$balance['name']] ?? 'bg-slate-800';
                                @endphp
                                <div class="{{ $colorClass }} h-full transition-all" style="width: {{ $balance['percent'] }}%;"></div>
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
            <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest mb-4">Year Summary</p>
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
                    <h2 class="text-xl font-bold text-slate-900">Recent Requests</h2>
                    <p class="text-sm text-slate-600 mt-1">Your latest leave requests and statuses.</p>
                </div>
                <a href="{{ route('leave-requests.index') }}" class="text-sm font-semibold text-slate-900 hover:text-black">View all →</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-t border-slate-200">
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
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $request->leaveType->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $request->days ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->reason ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($request->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                                @elseif($request->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @elseif($request->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                                @elseif($request->status === 'cancelled')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-600">
                                <p class="text-sm">No leave requests yet.</p>
                                <a href="{{ route('leave-requests.create') }}" class="text-slate-900 hover:text-black font-semibold text-sm mt-2 inline-block">Create your first request</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

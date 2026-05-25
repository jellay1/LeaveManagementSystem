@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header/Back Nav -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-900 transition gap-1.5 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to List
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Leave Request Details</h1>
        </div>
    </div>

    <!-- Leave Balance Context Logic -->
    @php
        $year = \Carbon\Carbon::parse($leaveRequest->start_date)->year;
        $balance = $leaveRequest->user->leaveBalances()
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->where('year', $year)
            ->first();
        $usedDays = $balance ? $balance->used_days : 0;
        $allocation = $leaveRequest->leaveType->annual_allocation;
        $remaining = $allocation - $usedDays;
    @endphp

    <!-- Main Card -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <!-- Top Banner / Quick Info -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-950 text-white rounded-full flex items-center justify-center font-bold text-lg">
                    {{ substr($leaveRequest->user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $leaveRequest->user->name }}</h2>
                    <p class="text-xs text-slate-500 font-medium">{{ optional($leaveRequest->user->employee)->position ?? 'Employee' }} • {{ optional($leaveRequest->user->employee)->department ?? '' }}</p>
                </div>
            </div>
            <div>
                @if($leaveRequest->status === 'approved')
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Approved</span>
                @elseif($leaveRequest->status === 'pending')
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending Review</span>
                @elseif($leaveRequest->status === 'rejected')
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                @elseif($leaveRequest->status === 'cancelled')
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">Cancelled</span>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Info Column -->
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Leave Type</label>
                    <span class="text-sm font-semibold text-slate-900 bg-slate-100 px-3 py-1.5 rounded-lg inline-block">{{ $leaveRequest->leaveType->name }}</span>
                </div>

                <!-- Balance Context Widget -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                    <div class="flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <span>Leave Balance Context</span>
                        <span>{{ $year }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-bold text-slate-900">
                        <span>{{ $leaveRequest->leaveType->name }}</span>
                        <span>{{ $usedDays }} / {{ $allocation }} Days Used</span>
                    </div>
                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                        @php
                            $percent = $allocation > 0 ? min(100, ($usedDays / $allocation) * 100) : 0;
                        @endphp
                        <div class="h-full bg-slate-950 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-500">
                        {{ $remaining }} {{ Str::plural('day', $remaining) }} available for the rest of the year.
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Requested Dates</label>
                    <div class="flex items-center gap-3 mt-1.5">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-center min-w-[110px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Start</span>
                            <span class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('M d, Y') }}</span>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-center min-w-[110px]">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">End</span>
                            <span class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Duration</label>
                    <div class="text-sm font-bold text-slate-900">
                        @php
                            $days = \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1;
                        @endphp
                        {{ $days }} {{ Str::plural('Day', $days) }}
                    </div>
                </div>
            </div>

            <!-- Right Info Column -->
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Submitted On</label>
                    <span class="text-sm font-semibold text-slate-800 block">{{ $leaveRequest->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Reviewed By</label>
                    <span class="text-sm font-semibold text-slate-800 block">{{ optional($leaveRequest->approver)->name ?? 'Not yet reviewed' }}</span>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Approver Remarks</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-700 italic mt-1">
                        {{ $leaveRequest->remarks ?? 'No remarks provided.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Full-Width Bottom Section: Reason -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/20">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Reason for Leave</label>
            <div class="bg-white border border-slate-200 rounded-xl p-5 text-sm text-slate-800 leading-relaxed shadow-sm">
                {{ $leaveRequest->reason ?? 'No reason provided.' }}
            </div>
        </div>
    </div>

    <!-- Manager/HR Approval Decision Form -->
    @if($leaveRequest->status === 'pending' && (auth()->user()->hasRole('hr_admin') || (auth()->user()->hasRole('manager') && $leaveRequest->user_id !== auth()->id())))
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Approval Decision</h3>
            <form method="POST" action="{{ route('leave-requests.update', $leaveRequest) }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="decision-status" name="status" value="approved">

                <div>
                    <label for="remarks" class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Remarks (Optional)</label>
                    <textarea id="remarks" name="remarks" rows="3" placeholder="Add remarks or feedback..." class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-transparent transition text-sm"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" onclick="document.getElementById('decision-status').value='approved'" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition shadow-sm text-sm cursor-pointer">
                        Approve Request
                    </button>
                    <button type="submit" onclick="document.getElementById('decision-status').value='rejected'" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition shadow-sm text-sm cursor-pointer">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
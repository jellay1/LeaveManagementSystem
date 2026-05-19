@extends('layouts.app')

@section('title', 'Update Leave Request')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header/Back Nav -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-slate-900 transition gap-1.5 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to List
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Update Leave Request</h1>
        </div>
    </div>

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

        <form method="POST" action="{{ route('leave-requests.update', $leaveRequest) }}">
            @csrf
            @method('PUT')

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Request Details Info Column -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Leave Type</label>
                        <span class="text-sm font-semibold text-slate-900 bg-slate-100 px-3 py-1.5 rounded-lg inline-block">{{ $leaveRequest->leaveType->name }}</span>
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
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Reason for Leave</label>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-800 leading-relaxed shadow-sm">
                            {{ $leaveRequest->reason ?? 'No reason provided.' }}
                        </div>
                    </div>
                </div>

                <!-- Update Form Column -->
                <div class="space-y-6">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-5">Decision</h3>

                        <div class="space-y-5">
                            <div>
                                <label for="status" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Update Status</label>
                                <div class="relative">
                                    <select id="status" name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-transparent transition text-sm appearance-none bg-white font-medium @error('status') border-red-500 @enderror" required>
                                        <option value="pending" {{ old('status', $leaveRequest->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="approved" {{ old('status', $leaveRequest->status) === 'approved' ? 'selected' : '' }}>Approve Request</option>
                                        <option value="rejected" {{ old('status', $leaveRequest->status) === 'rejected' ? 'selected' : '' }}>Reject Request</option>
                                        <option value="cancelled" {{ old('status', $leaveRequest->status) === 'cancelled' ? 'selected' : '' }}>Cancel Request</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                @error('status')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="remarks" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Remarks (Optional)</label>
                                <textarea id="remarks" name="remarks" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-transparent transition text-sm @error('remarks') border-red-500 @enderror" placeholder="Add remarks or feedback...">{{ old('remarks', $leaveRequest->remarks) }}</textarea>
                                @error('remarks')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer / Actions -->
            <div class="p-6 border-t border-slate-100 bg-slate-50/20 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('leave-requests.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition shadow-sm text-sm">
                    Save Decision
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

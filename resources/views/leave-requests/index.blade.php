@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">History</p>
            <h1 class="text-4xl font-bold text-slate-900">My Leaves</h1>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
            Apply Leave
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $key => $label)
            <a href="{{ route('leave-requests.index', $key === 'all' ? [] : ['status' => $key]) }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ $status === $key ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Leave Requests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        @if(auth()->user()->hasRole('hr_admin') || auth()->user()->hasRole('manager'))
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Employee</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Start</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">End</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Remarks</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($leaveRequests as $request)
                        <tr class="hover:bg-slate-50 transition">
                            @if(auth()->user()->hasRole('hr_admin') || auth()->user()->hasRole('manager'))
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $request->user->name }}</td>
                            @endif
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $request->leaveType->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->start_date }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->end_date }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $request->reason }}</td>
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
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $request->remarks ?? '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-1 justify-end">
                                    <a href="{{ route('leave-requests.show', $request) }}" class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">View</a>
                                    @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_admin'))
                                        @if($request->status === 'pending')
                                            <form action="{{ route('leave-requests.update', $request) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1 text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded transition">Approve</button>
                                            </form>
                                            <form action="{{ route('leave-requests.update', $request) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded transition">Reject</button>
                                            </form>
                                        @else
                                            <a href="{{ route('leave-requests.edit', $request) }}" class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">Edit</a>
                                        @endif
                                    @endif
                                    @if(auth()->id() === $request->user_id && $request->status === 'pending')
                                        <form action="{{ route('leave-requests.destroy', $request) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this leave request?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded transition">Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-slate-600">
                                <p class="text-sm">No leave requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $leaveRequests->links() }}
    </div>
</div>
@endsection

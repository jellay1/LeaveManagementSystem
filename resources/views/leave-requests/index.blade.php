@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="page-card">
    <div class="card-header">
        <div>
            <div class="page-heading">
                <div class="page-label">History</div>
                <h1 class="page-title">My Leaves.</h1>
            </div>
            <div class="filter-tabs">
                @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $key => $label)
                    <a href="{{ route('leave-requests.index', $key === 'all' ? [] : ['status' => $key]) }}" class="btn btn-sm {{ $status === $key ? 'active' : 'btn-outline-secondary' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
        <a href="{{ route('leave-requests.create') }}" class="btn btn-apply-leave">Apply Leave</a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        @if(auth()->user()->hasRole('hr_admin') || auth()->user()->hasRole('manager'))
                            <th>Employee</th>
                        @endif
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $request)
                        <tr>
                            @if(auth()->user()->hasRole('hr_admin') || auth()->user()->hasRole('manager'))
                                <td>{{ $request->user->name }}</td>
                            @endif
                            <td>{{ $request->leaveType->name }}</td>
                            <td>{{ $request->start_date }}</td>
                            <td>{{ $request->end_date }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>
                                <span class="badge-status {{ $request->status }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td>{{ $request->remarks ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('leave-requests.show', $request) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_admin'))
                                    @if($request->status === 'pending')
                                        <form action="{{ route('leave-requests.update', $request) }}" method="POST" class="d-inline-block ms-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                        </form>
                                        <form action="{{ route('leave-requests.update', $request) }}" method="POST" class="d-inline-block ms-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                        </form>
                                    @else
                                        <a href="{{ route('leave-requests.edit', $request) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @endif
                                @endif
                                @if(auth()->id() === $request->user_id && $request->status === 'pending')
                                    <form action="{{ route('leave-requests.destroy', $request) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Cancel this leave request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No leave requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $leaveRequests->links() }}
</div>
@endsection

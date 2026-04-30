@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Leave Requests</h1>
        <p class="text-muted">Manage leave applications and approval status.</p>
    </div>
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">Request Leave</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Type</th>
                <th>Dates</th>
                <th>Status</th>
                <th>Requested</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveRequests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->leaveType->name }}</td>
                    <td>{{ $request->start_date }} → {{ $request->end_date }}</td>
                    <td>
                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }} text-capitalize">{{ $request->status }}</span>
                    </td>
                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                    <td class="text-end">
                        <a href="{{ route('leave-requests.show', $request) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_admin'))
                            <a href="{{ route('leave-requests.edit', $request) }}" class="btn btn-sm btn-outline-primary">Edit</a>
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
                    <td colspan="6" class="text-center text-muted">No leave requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $leaveRequests->links() }}
@endsection

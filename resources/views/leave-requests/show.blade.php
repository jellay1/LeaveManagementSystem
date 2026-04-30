@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Leave Request Details</h1>
                <p class="text-muted">Review the request submitted by {{ $leaveRequest->user->name }}.</p>
            </div>
            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Employee</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->user->name }}</dd>

                    <dt class="col-sm-4">Leave Type</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->leaveType->name }}</dd>

                    <dt class="col-sm-4">Dates</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->start_date }} → {{ $leaveRequest->end_date }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8 text-capitalize">{{ $leaveRequest->status }}</dd>

                    <dt class="col-sm-4">Requested</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->created_at->format('Y-m-d') }}</dd>

                    <dt class="col-sm-4">Approved By</dt>
                    <dd class="col-sm-8">{{ optional($leaveRequest->approver)->name ?? 'Not yet reviewed' }}</dd>

                    <dt class="col-sm-4">Remarks</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->remarks ?? 'None' }}</dd>

                    <dt class="col-sm-4">Reason</dt>
                    <dd class="col-sm-8">{{ $leaveRequest->reason ?? 'No reason provided.' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

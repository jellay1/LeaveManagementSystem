@extends('layouts.app')

@section('title', 'Leave Type Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Leave Type Details</h1>
                <p class="text-muted">Details for {{ $leaveType->name }}.</p>
            </div>
            <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $leaveType->name }}</dd>

                    <dt class="col-sm-4">Annual Allocation</dt>
                    <dd class="col-sm-8">{{ $leaveType->annual_allocation }} days</dd>

                    <dt class="col-sm-4">Requires Approval</dt>
                    <dd class="col-sm-8">{{ $leaveType->requires_approval ? 'Yes' : 'No' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

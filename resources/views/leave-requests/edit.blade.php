@extends('layouts.app')

@section('title', 'Update Leave Request')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h1 class="h3 mb-3">Update Leave Request</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('leave-requests.update', $leaveRequest) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Employee</label>
                        <input type="text" class="form-control" value="{{ $leaveRequest->user->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Leave Type</label>
                        <input type="text" class="form-control" value="{{ $leaveRequest->leaveType->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <input type="text" class="form-control" value="{{ $leaveRequest->start_date }} → {{ $leaveRequest->end_date }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="approved" {{ old('status', $leaveRequest->status) === 'approved' ? 'selected' : '' }}>Approve</option>
                            <option value="rejected" {{ old('status', $leaveRequest->status) === 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="4" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $leaveRequest->remarks) }}</textarea>
                        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Decision</button>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

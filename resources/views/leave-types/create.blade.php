@extends('layouts.app')

@section('title', 'Add Leave Type')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <h1 class="h3 mb-3">Add Leave Type</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('leave-types.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Leave Type</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="annual_allocation" class="form-label">Annual Allocation (days)</label>
                        <input type="number" id="annual_allocation" name="annual_allocation" value="{{ old('annual_allocation') }}" class="form-control @error('annual_allocation') is-invalid @enderror" required>
                        @error('annual_allocation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="hidden" name="requires_approval" value="0">
                        <input class="form-check-input" type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_approval">Requires manager approval</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Leave Type</button>
                    <a href="{{ route('leave-types.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

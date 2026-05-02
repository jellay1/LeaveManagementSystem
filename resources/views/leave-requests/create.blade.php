@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<div class="page-card">
    <div class="card-header">
        <div>
            <div class="form-heading">New request</div>
            <h1 class="form-title">Apply for leave.</h1>
        </div>
    </div>

    <div class="card-body">
        <div class="row gx-4">
            <div class="col-xl-8">
                <form method="POST" action="{{ route('leave-requests.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="leave_type_id" class="field-label">Leave Type</label>
                        <select id="leave_type_id" name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                            <option value="">Select a leave type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} ({{ $type->annual_allocation }} days)</option>
                            @endforeach
                        </select>
                        @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div>
                                <label for="start_date" class="field-label">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="end_date" class="field-label">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control @error('end_date') is-invalid @enderror" required>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="field-label">Reason</label>
                        <textarea id="reason" name="reason" rows="5" class="form-control @error('reason') is-invalid @enderror">{{ old('reason') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-submit-request">Submit request</button>
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-outline-secondary btn-cancel-request">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="col-xl-4">
                <div class="summary-panel">
                    <h3>Request summary</h3>
                    <div class="summary-item">
                        <span>Days requested</span>
                        <span id="daysRequested" class="summary-value">0</span>
                    </div>
                    <div class="summary-item">
                        <span>Request type</span>
                        <span id="summaryType" class="summary-value">—</span>
                    </div>
                    <div class="summary-item">
                        <div>
                            <div class="summary-note">Choose dates and type to preview</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const typeSelect = document.getElementById('leave_type_id');
        const daysElement = document.getElementById('daysRequested');
        const typeElement = document.getElementById('summaryType');

        function updateSummary() {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            const type = typeSelect.options[typeSelect.selectedIndex]?.text || '—';

            typeElement.textContent = typeSelect.value ? type : '—';

            if (startInput.value && endInput.value && start <= end) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
                daysElement.textContent = diffDays;
            } else {
                daysElement.textContent = '0';
            }
        }

        startInput.addEventListener('change', updateSummary);
        endInput.addEventListener('change', updateSummary);
        typeSelect.addEventListener('change', updateSummary);
        updateSummary();
    });
</script>
@endsection

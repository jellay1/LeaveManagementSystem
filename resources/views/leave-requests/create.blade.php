@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">New request</p>
        <h1 class="text-4xl font-bold text-slate-900">Apply for Leave</h1>
    </div>

    <!-- Form and Summary Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('leave-requests.store') }}" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                @csrf

                <!-- Leave Type Field -->
                <div class="mb-6">
                    <label for="leave_type_id" class="block text-sm font-semibold text-slate-700 mb-2">Leave Type</label>
                    <select id="leave_type_id" name="leave_type_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('leave_type_id') border-red-500 @enderror" required>
                        <option value="">Select a leave type</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->annual_allocation }} days)
                            </option>
                        @endforeach
                    </select>
                    @error('leave_type_id')
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-2">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('start_date') border-red-500 @enderror" required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-2">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('end_date') border-red-500 @enderror" required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason Field -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-semibold text-slate-700 mb-2">Reason</label>
                    <textarea id="reason" name="reason" rows="5" placeholder="Please provide a reason for your leave request..." class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
                        Submit Request
                    </button>
                    <a href="{{ route('leave-requests.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Request Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 sticky top-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Request Summary</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm text-slate-600">Days requested</span>
                        <span id="daysRequested" class="text-2xl font-bold text-slate-900">0</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm text-slate-600">Request type</span>
                        <span id="summaryType" class="font-semibold text-slate-900">—</span>
                    </div>

                    <div class="pt-2 text-xs text-slate-600">
                        <p>Choose dates and type to preview your request summary</p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-slate-100 rounded-lg border border-slate-200">
                    <p class="text-xs text-slate-700">
                        <strong>Tip:</strong> Make sure your selected dates are within the working calendar and you have sufficient leave balance.
                    </p>
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

@extends('layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Configuration</p>
        <h1 class="text-4xl font-bold text-slate-900">Edit Leave Type</h1>
        <p class="text-slate-600 mt-2">Update the leave category and control whether requests require manager approval.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('leave-types.update', $leaveType) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Leave Type & Annual Allocation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Leave Type</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $leaveType->name) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="annual_allocation" class="block text-sm font-semibold text-slate-700 mb-2">Annual Allocation (days)</label>
                    <input type="number" id="annual_allocation" name="annual_allocation" value="{{ old('annual_allocation', $leaveType->annual_allocation) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('annual_allocation') border-red-500 @enderror" required>
                    @error('annual_allocation')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Approval Checkbox -->
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 flex gap-3">
                <input type="hidden" name="requires_approval" value="0">
                <input type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval', $leaveType->requires_approval) ? 'checked' : '' }} class="mt-1 w-5 h-5 text-slate-900 border-slate-300 rounded focus:ring-2 focus:ring-slate-900">
                <label for="requires_approval" class="text-sm font-medium text-slate-700">Requires manager approval for leave requests</label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('leave-types.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
                    Update Leave Type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Leave Type Details')

@section('content')
<div class="max-w-2xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Configuration</p>
            <h1 class="text-4xl font-bold text-slate-900">{{ $leaveType->name }}</h1>
            <p class="text-slate-600 mt-2">Leave type configuration details</p>
        </div>
        <a href="{{ route('leave-types.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
            Back to List
        </a>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="space-y-6">
            <div class="pb-6 border-b border-slate-200">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Annual Allocation</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $leaveType->annual_allocation }} <span class="text-xl font-semibold text-slate-600">days</span></p>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Approval Requirement</p>
                @if($leaveType->requires_approval)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                        Requires Manager Approval
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                        Automatically Approved
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">
        <a href="{{ route('leave-types.edit', $leaveType) }}" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
            Edit
        </a>
        <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition shadow-md">
                Delete
            </button>
        </form>
    </div>
</div>
@endsection

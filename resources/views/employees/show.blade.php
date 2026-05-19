@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Directory</p>
            <h1 class="text-4xl font-bold text-slate-900">{{ $employee->user->name }}</h1>
            <p class="text-slate-600 mt-2">Employee profile information</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
                Edit
            </a>
            <a href="{{ route('employees.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
                Back
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-200">
            <!-- Left Column -->
            <div class="p-6 space-y-6">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">{{ $employee->user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">{{ $employee->department }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Position</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">{{ $employee->position }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="p-6 space-y-6">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Hired</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">
                        {{ $employee->date_hired ? \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Phone</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">{{ $employee->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Address</p>
                    <p class="text-sm text-slate-700 mt-1">{{ $employee->address ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

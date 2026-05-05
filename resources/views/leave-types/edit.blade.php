@extends('layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
<div class="space-y-6">
    <div class="space-y-2">
        <div class="text-xs uppercase tracking-[0.4em] text-slate-500">Configuration</div>
        <h1 class="text-4xl font-semibold tracking-tight text-slate-950">Edit Leave Type</h1>
        <p class="max-w-2xl text-sm text-slate-600">Update the leave category and control whether requests require manager approval.</p>
    </div>

    <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
        <div class="p-8">
            <form method="POST" action="{{ route('leave-types.update', $leaveType) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-slate-700">Leave Type</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $leaveType->name) }}" class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none ring-1 ring-transparent transition focus:border-black focus:ring-black @error('name') border-red-500 ring-red-200 @enderror" required>
                        @error('name')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label for="annual_allocation" class="text-sm font-semibold text-slate-700">Annual Allocation (days)</label>
                        <input type="number" id="annual_allocation" name="annual_allocation" value="{{ old('annual_allocation', $leaveType->annual_allocation) }}" class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none ring-1 ring-transparent transition focus:border-black focus:ring-black @error('annual_allocation') border-red-500 ring-red-200 @enderror" required>
                        @error('annual_allocation')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <input type="hidden" name="requires_approval" value="0">
                    <input type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval', $leaveType->requires_approval) ? 'checked' : '' }} class="h-5 w-5 rounded-md border-slate-300 text-black focus:ring-black">
                    <label for="requires_approval" class="text-sm font-medium text-slate-700">Requires manager approval</label>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('leave-types.index') }}" class="inline-flex justify-center rounded-3xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex justify-center rounded-3xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">Update Leave Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

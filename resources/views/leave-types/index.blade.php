@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="mb-8">
    <div class="text-xs uppercase tracking-[0.4em] text-slate-500 mb-3">Configuration</div>
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-950">Leave Types.</h1>
            <p class="mt-3 text-sm text-slate-600">Manage the leave categories employees can request and control approval flow.</p>
        </div>
        <a href="{{ route('leave-types.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-black px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">+ New Type</a>
    </div>
</div>

<div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full border-separate border-spacing-0 text-left">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-sm uppercase tracking-[0.18em] text-slate-500">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Annual Allocation</th>
                    <th class="px-6 py-4">Approval Required</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                @forelse($leaveTypes as $leaveType)
                    @php
                        $colorClass = match(strtolower($leaveType->name)) {
                            'emergency' => 'bg-red-500',
                            'personal' => 'bg-amber-400',
                            'sick leave' => 'bg-emerald-500',
                            'vacation' => 'bg-blue-600',
                            default => 'bg-slate-400',
                        };
                    @endphp
                    <tr class="border-b border-slate-200 last:border-none hover:bg-slate-50">
                        <td class="px-6 py-5 align-middle">
                            <div class="flex items-center gap-3">
                                <span class="h-3.5 w-3.5 rounded-sm {{ $colorClass }}"></span>
                                <span class="font-semibold text-slate-900">{{ $leaveType->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle text-slate-700">{{ $leaveType->annual_allocation }} days</td>
                        <td class="px-6 py-5 align-middle text-slate-700">{{ $leaveType->requires_approval ? 'Yes' : 'No (auto-approved)' }}</td>
                        <td class="px-6 py-5 align-middle text-right">
                            <a href="{{ route('leave-types.edit', $leaveType) }}" class="text-slate-900 hover:text-black">Edit</a>
                            <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Delete this leave type?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No leave types available yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $leaveTypes->links() }}
</div>
@endsection

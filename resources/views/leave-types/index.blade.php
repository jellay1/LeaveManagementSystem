@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Configuration</p>
            <h1 class="text-4xl font-bold text-slate-900">Leave Types</h1>
            <p class="text-slate-600 mt-2">Manage the leave categories employees can request and control approval flow.</p>
        </div>
        <a href="{{ route('leave-types.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
            + New Type
        </a>
    </div>

    <!-- Leave Types Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Annual Allocation</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Approval Required</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($leaveTypes as $leaveType)
                        @php
                            $colorClass = match(strtolower($leaveType->name)) {
                                'emergency' => 'bg-red-500',
                                'personal' => 'bg-amber-400',
                                'sick leave' => 'bg-emerald-500',
                                'vacation' => 'bg-slate-900',
                                default => 'bg-slate-400',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded {{ $colorClass }}"></span>
                                    <span class="text-sm font-semibold text-slate-900">{{ $leaveType->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $leaveType->annual_allocation }} days</td>
                            <td class="px-6 py-4">
                                @if($leaveType->requires_approval)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Yes</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Auto-approved</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('leave-types.edit', $leaveType) }}" class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">Edit</a>
                                    <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="inline" onsubmit="return confirm('Delete this leave type?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-600">
                                <p class="text-sm">No leave types available yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $leaveTypes->links() }}
    </div>
</div>
@endsection

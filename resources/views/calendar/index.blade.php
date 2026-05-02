@extends('layouts.app')

@section('title', 'Leave Calendar')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="flex justify-between items-center border-b border-gray-200 p-6">
        <div>
            <div class="text-xs uppercase tracking-wider text-gray-500 mb-2">Schedule</div>
            <h1 class="text-4xl font-bold">Leave Calendar.</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="?month={{ $currentMonth->copy()->subMonth()->format('Y-m') }}" class="border border-gray-800 hover:bg-gray-100 px-3 py-1.5 rounded text-sm">←</a>
            <span class="font-bold tracking-tight text-sm">{{ $currentMonth->format('F Y') }}</span>
            <a href="?month={{ $currentMonth->copy()->addMonth()->format('Y-m') }}" class="border border-gray-800 hover:bg-gray-100 px-3 py-1.5 rounded text-sm">→</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse" style="table-layout: fixed;">
            <colgroup>
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
                <col style="width: 14.285714%;" />
            </colgroup>
            <thead>
                <tr>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">MON</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">TUE</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">WED</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">THU</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">FRI</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">SAT</th>
                    <th class="bg-black text-white font-bold text-center py-4 px-2 text-sm border border-gray-300 tracking-wider">SUN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weeks as $week)
                    <tr>
                        @foreach ($week as $date)
                            <td class="min-h-32 p-4 border border-gray-200 bg-white align-top">
                                @if ($date)
                                    <div class="text-xs font-bold text-gray-700 mb-2">{{ $date->format('d') }}</div>
                                    @php $dayKey = $date->format('Y-m-d'); @endphp
                                    @if (!empty($events[$dayKey]))
                                        <div class="space-y-1.5">
                                            @foreach ($events[$dayKey] as $event)
                                                <a href="{{ $event['url'] }}" class="block px-2.5 py-1.5 rounded-lg text-xs font-bold leading-tight no-underline overflow-hidden {{ 
                                                    $event['status'] === 'approved' ? 'bg-green-100 border-l-4 border-green-500 text-gray-900' : (
                                                    $event['status'] === 'pending' ? 'bg-amber-100 border-l-4 border-amber-500 text-gray-900' : (
                                                    $event['status'] === 'rejected' ? 'bg-red-100 border-l-4 border-red-500 text-gray-900' : 'bg-gray-100 border-l-4 border-gray-500 text-gray-900')) }}">
                                                    <div class="overflow-hidden text-ellipsis whitespace-nowrap">{{ $event['title'] }}</div>
                                                    @if (!empty($event['subtitle']))
                                                        <div class="text-xs font-medium opacity-85 overflow-hidden text-ellipsis whitespace-nowrap">{{ $event['subtitle'] }}</div>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

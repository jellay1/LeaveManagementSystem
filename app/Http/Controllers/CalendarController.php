<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->query('month')
            ? Carbon::parse($request->query('month') . '-01')
            : Carbon::now();

        $currentMonth = $currentMonth->copy()->startOfMonth();
        $daysInMonth = $currentMonth->daysInMonth;
        $startWeekday = ($currentMonth->dayOfWeek + 6) % 7;

        $slots = [];
        for ($i = 0; $i < $startWeekday; $i++) {
            $slots[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $slots[] = $currentMonth->copy()->day($day);
        }

        while (count($slots) % 7 !== 0) {
            $slots[] = null;
        }

        $weeks = array_chunk($slots, 7);

        $user = auth()->user();

        if ($user->hasRole('hr_admin')) {
            $query = LeaveRequest::with(['user', 'leaveType']);
        } elseif ($user->hasRole('manager')) {
            $department = optional($user->employee)->department;
            $query = LeaveRequest::with(['user', 'leaveType'])
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department));
        } else {
            $query = $user->leaveRequests()->with('leaveType');
        }

        $events = [];
        foreach ($query->get() as $leave) {
            $status = $leave->status;
            $color = match ($status) {
                'approved' => '#22c55e',
                'pending' => '#f59e0b',
                'rejected' => '#dc3545',
                'cancelled' => '#6c757d',
                default => '#6c757d',
            };

            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $events[$date->format('Y-m-d')][] = [
                    'title' => $leave->user->name . ' · ' . $leave->leaveType->name,
                    'subtitle' => $leave->reason ?: $leave->leaveType->name,
                    'status' => $status,
                    'color' => $color,
                    'date' => $date->format('Y-m-d'),
                    'url' => route('leave-requests.show', $leave),
                ];
            }
        }

        return view('calendar.index', [
            'currentMonth' => $currentMonth,
            'weeks' => $weeks,
            'events' => $events,
        ]);
    }

    public function events(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('hr_admin')) {
            $query = LeaveRequest::with(['user', 'leaveType']);
        } elseif ($user->hasRole('manager')) {
            $department = optional($user->employee)->department;
            $query = LeaveRequest::with(['user', 'leaveType'])
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department));
        } else {
            $query = $user->leaveRequests()->with('leaveType');
        }

        $events = $query->get()->map(function ($request) {
            $status = $request->status;
            $colors = [
                'approved' => '#22c55e',
                'pending' => '#f59e0b',
                'rejected' => '#dc3545',
                'cancelled' => '#6c757d',
            ];

            return [
                'title' => $request->leaveType->name,
                'start' => $request->start_date,
                'end' => Carbon::parse($request->end_date)->addDay()->format('Y-m-d'),
                'color' => $colors[$status] ?? '#6c757d',
                'url' => route('leave-requests.show', $request),
            ];
        });

        return response()->json($events);
    }
}

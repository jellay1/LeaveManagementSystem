<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $year = Carbon::now()->year;

        if ($user->hasRole('hr_admin')) {
            $pendingApprovals = LeaveRequest::where('status', 'pending')->count();
            $approvedYtd = LeaveRequest::where('status', 'approved')
                ->whereYear('created_at', $year)
                ->count();
            $employees = Employee::count();
            $recentRequests = LeaveRequest::with(['user', 'leaveType'])
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->hasRole('manager')) {
            $department = optional($user->employee)->department;
            $pendingApprovals = LeaveRequest::where('status', 'pending')
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department))
                ->count();
            $approvedYtd = LeaveRequest::where('status', 'approved')
                ->whereYear('created_at', $year)
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department))
                ->count();
            $employees = Employee::where('department', $department)->count();
            $recentRequests = LeaveRequest::with(['user', 'leaveType'])
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department))
                ->latest()
                ->take(5)
                ->get();
        } else {
            $pendingApprovals = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $approvedYtd = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereYear('created_at', $year)
                ->count();
            $employees = 1;
            $recentRequests = LeaveRequest::with(['user', 'leaveType'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $leaveTypes = LeaveType::orderBy('name')->get();
        $balanceRecords = LeaveBalance::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', $year)
            ->get();

        $balances = $leaveTypes->map(function ($type) use ($balanceRecords) {
            $record = $balanceRecords->firstWhere('leave_type_id', $type->id);
            $used = $record?->used_days ?? 0;
            $allocation = $type->annual_allocation;

            return [
                'name' => $type->name,
                'used' => $used,
                'allocation' => $allocation,
                'percent' => $allocation > 0 ? min(100, round(($used / $allocation) * 100)) : 0,
            ];
        });

        $totalAllocated = $balances->sum('allocation');
        $totalUsed = $balances->sum('used');
        $remainingDays = max(0, $totalAllocated - $totalUsed);

        return view('dashboard', compact(
            'pendingApprovals',
            'onLeaveToday',
            'approvedYtd',
            'employees',
            'balances',
            'remainingDays',
            'recentRequests'
        ));
    }
}

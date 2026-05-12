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

        // Check if user is a regular employee
        if (!$user->hasRole('manager') && !$user->hasRole('hr_admin')) {
            return $this->employeeDashboard($user, $year);
        }

        $department = optional($user->employee)->department;

        $requestsScope = LeaveRequest::query()
            ->when($user->hasRole('manager'), fn ($query) => $query->whereHas('user.employee', fn ($query) => $query->where('department', $department)))
            ->when(!$user->hasRole('hr_admin') && !$user->hasRole('manager'), fn ($query) => $query->where('user_id', $user->id));

        $pendingApprovals = (clone $requestsScope)
            ->where('status', 'pending')
            ->count();

        $approvedYtd = (clone $requestsScope)
            ->where('status', 'approved')
            ->whereYear('created_at', $year)
            ->count();

        $employees = $user->hasRole('hr_admin')
            ? Employee::count()
            : ($user->hasRole('manager') ? Employee::where('department', $department)->count() : 1);

        $recentRequests = (clone $requestsScope)
            ->with(['user', 'user.employee', 'leaveType'])
            ->latest()
            ->take(5)
            ->get();

        $recentRequests->each(function ($request) {
            $request->days = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        });

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

        $departmentNames = Employee::query()
            ->when($user->hasRole('manager'), fn ($query) => $query->where('department', $department))
            ->when(!$user->hasRole('hr_admin') && !$user->hasRole('manager'), fn ($query) => $query->where('user_id', $user->id))
            ->distinct()
            ->pluck('department')
            ->filter()
            ->values();

        $allDepartmentRequests = (clone $requestsScope)
            ->with('user.employee')
            ->get();

        $departmentSummary = $departmentNames->map(function ($department) use ($allDepartmentRequests) {
            $requests = $allDepartmentRequests->filter(fn ($request) => optional($request->user->employee)->department === $department);

            return [
                'department' => $department,
                'pending' => $requests->where('status', 'pending')->count(),
                'approved' => $requests->where('status', 'approved')->count(),
                'rejected' => $requests->where('status', 'rejected')->count(),
                'total_days' => $requests->sum(fn ($request) => Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1),
            ];
        })->values();

        return view('dashboard', compact(
            'pendingApprovals',
            'onLeaveToday',
            'approvedYtd',
            'employees',
            'balances',
            'remainingDays',
            'recentRequests',
            'departmentSummary',
            'totalUsed',
            'totalAllocated'
        ));
    }

    /**
     * Get the employee dashboard view
     */
    private function employeeDashboard($user, $year)
    {
        $leaveTypes = LeaveType::orderBy('name')->get();
        $balanceRecords = LeaveBalance::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', $year)
            ->get();

        // Calculate leave balances
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

        // Calculate totals
        $totalAllocated = $balances->sum('allocation');
        $totalUsed = $balances->sum('used');
        $remainingDays = max(0, $totalAllocated - $totalUsed);

        // Get recent requests
        $recentRequests = $user->leaveRequests()
            ->with(['leaveType', 'approver'])
            ->latest()
            ->take(10)
            ->get();

        $recentRequests->each(function ($request) {
            $request->days = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        });

        return view('dashboard-employee', compact(
            'balances',
            'remainingDays',
            'recentRequests',
            'totalUsed',
            'totalAllocated'
        ));
    }
}

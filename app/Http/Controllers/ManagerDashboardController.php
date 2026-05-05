<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $department = optional($user->employee)->department;

        // Ensure user is a manager
        if (!$user->hasRole('manager')) {
            abort(403, 'Unauthorized');
        }

        $today = Carbon::today()->toDateString();
        $year = Carbon::now()->year;

        // Get all leave requests from the manager's department
        $allDepartmentRequests = LeaveRequest::query()
            ->whereHas('user.employee', fn ($query) => $query->where('department', $department))
            ->with(['user', 'user.employee', 'leaveType', 'approver'])
            ->get();

        // Pending requests awaiting approval
        $pendingRequests = $allDepartmentRequests->filter(fn ($request) => $request->status === 'pending')->values();

        // Summary statistics
        $pendingCount = $pendingRequests->count();
        $approvedCount = $allDepartmentRequests->filter(fn ($request) => $request->status === 'approved')->count();
        $rejectedCount = $allDepartmentRequests->filter(fn ($request) => $request->status === 'rejected')->count();

        // Calculate leave days for pending requests
        $pendingRequests->each(function ($request) {
            $request->days = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
        });

        // Total leave days by status
        $totalPendingDays = $pendingRequests->sum('days');
        $approvedDays = $allDepartmentRequests
            ->filter(fn ($request) => $request->status === 'approved')
            ->sum(fn ($request) => Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1);

        // Employees on leave today
        $onLeaveToday = $allDepartmentRequests
            ->filter(fn ($request) => $request->status === 'approved')
            ->filter(fn ($request) => 
                Carbon::parse($request->start_date)->lte($today) && 
                Carbon::parse($request->end_date)->gte($today)
            )
            ->count();

        // Year-to-date approved requests
        $approvedYtd = $allDepartmentRequests
            ->filter(fn ($request) => $request->status === 'approved')
            ->filter(fn ($request) => Carbon::parse($request->created_at)->year === $year)
            ->count();

        // Active Headcount in department
        $activeEmployees = \App\Models\Employee::where('department', $department)->count();

        // Manager's own leave balances
        $myBalances = \App\Models\LeaveBalance::with('leaveType')
            ->where('user_id', $user->id)
            ->where('year', $year)
            ->get();
        
        $totalUsed = $myBalances->sum('used_days');
        $totalAllocation = $myBalances->sum(fn ($balance) => $balance->leaveType->annual_allocation ?? 0);

        return view('manager-dashboard', compact(
            'department',
            'pendingRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalPendingDays',
            'approvedDays',
            'onLeaveToday',
            'approvedYtd',
            'activeEmployees',
            'myBalances',
            'totalUsed',
            'totalAllocation'
        ));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        // Check authorization
        if (!$user->hasRole('manager') || optional($user->employee)->department !== optional($leaveRequest->user->employee)->department) {
            abort(403, 'Unauthorized');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'remarks' => request('remarks'),
        ]);

        return redirect()->route('manager-dashboard')->with('success', 'Leave request approved successfully.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        // Check authorization
        if (!$user->hasRole('manager') || optional($user->employee)->department !== optional($leaveRequest->user->employee)->department) {
            abort(403, 'Unauthorized');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'remarks' => request('remarks'),
        ]);

        return redirect()->route('manager-dashboard')->with('success', 'Leave request rejected.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Services\NotificationService;
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

        // Get all employees managed by this manager
        $managedEmployeeIds = $user->managedEmployees()->pluck('user_id');

        // Get all leave requests from employees managed by this manager
        $allDepartmentRequests = LeaveRequest::query()
            ->whereIn('user_id', $managedEmployeeIds)
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
        $activeEmployees = $user->managedEmployees()->count();

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
        $managedEmployeeIds = $user->managedEmployees()->pluck('user_id');

        // Check authorization - can only approve requests from managed employees
        if (!$user->hasRole('manager') || !$managedEmployeeIds->contains($leaveRequest->user_id)) {
            abort(403, 'Unauthorized');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'remarks' => request('remarks'),
        ]);

        // Increment the leave balance for the employee
        $days = Carbon::parse($leaveRequest->start_date)->diffInDays(Carbon::parse($leaveRequest->end_date)) + 1;
        $balance = LeaveBalance::firstOrCreate([
            'user_id' => $leaveRequest->user_id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'year' => Carbon::parse($leaveRequest->start_date)->year,
        ]);
        $balance->increment('used_days', $days);

        // Send approval notification
        NotificationService::leaveRequestApproved($leaveRequest);

        return redirect()->route('manager-dashboard')->with('success', 'Leave request approved successfully.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();
        $managedEmployeeIds = $user->managedEmployees()->pluck('user_id');

        // Check authorization - can only reject requests from managed employees
        if (!$user->hasRole('manager') || !$managedEmployeeIds->contains($leaveRequest->user_id)) {
            abort(403, 'Unauthorized');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'remarks' => request('remarks'),
        ]);

        // Send rejection notification
        NotificationService::leaveRequestRejected($leaveRequest);

        return redirect()->route('manager-dashboard')->with('success', 'Leave request rejected.');
    }
}

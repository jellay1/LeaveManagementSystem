<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $year  = now()->year;
        $today = Carbon::today();
        $user  = auth()->user();
        $isManager = $user->hasRole('manager');
        $department = $isManager ? optional($user->employee)->department : null;

        // ── Top stat cards ──────────────────────────────────────────────
        $pendingQuery = LeaveRequest::where('status', 'pending');
        $approvedQuery = LeaveRequest::where('status', 'approved');
        $rejectedQuery = LeaveRequest::where('status', 'rejected');
        $employeeQuery = Employee::query();
        $onLeaveTodayQuery = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date',   '>=', $today);

        if ($isManager && $department) {
            $pendingQuery->whereHas('user.employee', fn($q) => $q->where('department', $department));
            $approvedQuery->whereHas('user.employee', fn($q) => $q->where('department', $department));
            $rejectedQuery->whereHas('user.employee', fn($q) => $q->where('department', $department));
            $employeeQuery->where('department', $department);
            $onLeaveTodayQuery->whereHas('user.employee', fn($q) => $q->where('department', $department));
        }

        $pending = $pendingQuery->count();
        $approved = $approvedQuery->count();
        $rejected = $rejectedQuery->count();
        $totalEmployees = $employeeQuery->count();
        $onLeaveToday = $onLeaveTodayQuery->count();

        // ── Department bar chart data ────────────────────────────────────
        $deptChartQuery = LeaveRequest::select(
                'employees.department',
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending'  THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'rejected' THEN 1 ELSE 0 END) as rejected"),
                DB::raw('COUNT(*) as total')
            )
            ->join('users',     'leave_requests.user_id', '=', 'users.id')
            ->join('employees', 'employees.user_id',      '=', 'users.id');

        if ($isManager && $department) {
            $deptChartQuery->where('employees.department', $department);
        }

        $deptChart = $deptChartQuery->groupBy('employees.department')
            ->orderBy('employees.department')
            ->get();

        // ── Department summary table (same data) ─────────────────────────
        $departmentSummary = $deptChart->map(function ($row) {
            return [
                'department' => $row->department,
                'pending'    => $row->pending,
                'approved'   => $row->approved,
                'rejected'   => $row->rejected,
                'total_days' => LeaveRequest::whereHas('user.employee', function ($q) use ($row) {
                                    $q->where('department', $row->department);
                                })
                                ->where('status', 'approved')
                                ->get()
                                ->sum(function ($request) {
                                    return Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
                                }),
            ];
        });

        return view('reports.index', compact(
            'year',
            'pending',
            'approved',
            'rejected',
            'totalEmployees',
            'onLeaveToday',
            'deptChart',
            'departmentSummary'
        ));
    }

    public function exportCsv()
    {
        $user = auth()->user();
        if (!$user->hasRole('hr_admin')) {
            abort(403, 'Unauthorized');
        }

        // Get leave requests scoped by role/department
        $leaveRecords = LeaveRequest::with(['user', 'user.employee', 'leaveType', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Create CSV content
        $filename = 'leave_records_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Write headers
        fputcsv($handle, [
            'Leave ID',
            'Employee',
            'Email',
            'Department',
            'Leave Type',
            'Start',
            'End',
            'Days',
            'Status',
            'Reason',
            'Remarks',
            'Decided At',
            'Created At',
        ]);

        // Write data rows
        foreach ($leaveRecords as $record) {
            $days = Carbon::parse($record->start_date)->diffInDays(Carbon::parse($record->end_date)) + 1;
            
            fputcsv($handle, [
                $record->id,
                $record->user->name ?? '',
                $record->user->email ?? '',
                $record->user->employee->department ?? '',
                $record->leaveType->name ?? '',
                $record->start_date,
                $record->end_date,
                $days,
                $record->status,
                $record->reason ?? '',
                $record->remarks ?? '',
                $record->approver->name ?? '',
                $record->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}

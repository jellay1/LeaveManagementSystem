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

        // ── Top stat cards ──────────────────────────────────────────────
        $pending  = LeaveRequest::where('status', 'pending')->count();
        $approved = LeaveRequest::where('status', 'approved')->count();
        $rejected = LeaveRequest::where('status', 'rejected')->count();

        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date',   '>=', $today)
            ->count();

        // ── Department bar chart data ────────────────────────────────────
        $deptChart = LeaveRequest::select(
                'employees.department',
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending'  THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'rejected' THEN 1 ELSE 0 END) as rejected"),
                DB::raw('COUNT(*) as total')
            )
            ->join('users',     'leave_requests.user_id', '=', 'users.id')
            ->join('employees', 'employees.user_id',      '=', 'users.id')
            ->groupBy('employees.department')
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
            'onLeaveToday',
            'deptChart',
            'departmentSummary'
        ));
    }

    public function exportCsv()
    {
        $year  = now()->year;
        $today = Carbon::today();

        // Get department summary data
        $deptChart = LeaveRequest::select(
                'employees.department',
                DB::raw("SUM(CASE WHEN leave_requests.status = 'pending'  THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN leave_requests.status = 'rejected' THEN 1 ELSE 0 END) as rejected"),
                DB::raw('COUNT(*) as total')
            )
            ->join('users',     'leave_requests.user_id', '=', 'users.id')
            ->join('employees', 'employees.user_id',      '=', 'users.id')
            ->groupBy('employees.department')
            ->orderBy('employees.department')
            ->get();

        // Build department summary
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

        // Create CSV content
        $filename = 'leave_report_' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Write headers
        fputcsv($handle, ['Department', 'Pending', 'Approved', 'Rejected', 'Total Days']);

        // Write data rows
        foreach ($departmentSummary as $row) {
            fputcsv($handle, [
                $row['department'],
                $row['pending'],
                $row['approved'],
                $row['rejected'],
                $row['total_days'],
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}

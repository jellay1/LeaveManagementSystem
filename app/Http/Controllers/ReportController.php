<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $year = now()->year;

        $departments = Employee::select('department', DB::raw('count(*) as employees'))
            ->groupBy('department')
            ->orderBy('department')
            ->get();

        $departmentRequests = LeaveRequest::select('employees.department', DB::raw('count(*) as total_requests'))
            ->join('users', 'leave_requests.user_id', '=', 'users.id')
            ->join('employees', 'employees.user_id', '=', 'users.id')
            ->groupBy('employees.department')
            ->get();

        $balances = LeaveBalance::with(['leaveType', 'user.employee'])
            ->where('year', $year)
            ->orderBy('id', 'desc')
            ->get();

        return view('reports.index', compact('year', 'departments', 'departmentRequests', 'balances'));
    }
}

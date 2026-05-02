<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $status = request('status');
        $allowedStatuses = ['pending', 'approved', 'rejected', 'cancelled'];

        if ($user->hasRole('hr_admin')) {
            $query = LeaveRequest::with(['user.employee', 'leaveType', 'approver']);
        } elseif ($user->hasRole('manager')) {
            $department = optional($user->employee)->department;
            $query = LeaveRequest::with(['user.employee', 'leaveType', 'approver'])
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department));
        } else {
            $query = $user->leaveRequests()->with(['leaveType', 'approver']);
        }

        if (in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        } else {
            $status = 'all';
        }

        $leaveRequests = $query->latest()->paginate(12)->withQueryString();

        return view('leave-requests.index', compact('leaveRequests', 'status'));
    }

    public function approvals()
    {
        $user = auth()->user();

        if ($user->hasRole('hr_admin')) {
            $query = LeaveRequest::with(['user.employee', 'leaveType', 'approver']);
        } elseif ($user->hasRole('manager')) {
            $department = optional($user->employee)->department;
            $query = LeaveRequest::with(['user.employee', 'leaveType', 'approver'])
                ->whereHas('user.employee', fn ($query) => $query->where('department', $department));
        } else {
            return redirect()->route('leave-requests.index')->with('warning', 'Approvals are only available for managers and HR.');
        }

        $leaveRequests = $query->where('status', 'pending')->latest()->paginate(12)->withQueryString();
        $status = 'pending';

        return view('leave-requests.index', compact('leaveRequests', 'status'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('leave-requests.create', compact('leaveTypes'));
    }

    public function store(StoreLeaveRequestRequest $request)
    {
        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $requestedDays = $start->diffInDays($end) + 1;
        $year = $start->year;

        $leaveBalance = LeaveBalance::firstOrCreate([
            'user_id' => auth()->id(),
            'leave_type_id' => $leaveType->id,
            'year' => $year,
        ]);

        if ($leaveBalance->used_days + $requestedDays > $leaveType->annual_allocation) {
            return back()->withInput()->with('error', 'You do not have enough leave balance for this request.');
        }

        $status = $leaveType->requires_approval ? 'pending' : 'approved';
        $approvedBy = $leaveType->requires_approval ? null : auth()->id();
        $remarks = $leaveType->requires_approval ? null : 'Auto-approved';

        $leaveRequest = LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type_id' => $leaveType->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => $status,
            'approved_by' => $approvedBy,
            'remarks' => $remarks,
        ]);

        if ($status === 'approved') {
            $leaveBalance->increment('used_days', $requestedDays);
        }

        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorizeRequestAccess($leaveRequest);

        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        if (!auth()->user()->hasRole('manager') && !auth()->user()->hasRole('hr_admin')) {
            abort(403);
        }

        $this->authorizeRequestAccess($leaveRequest);

        return view('leave-requests.edit', compact('leaveRequest'));
    }

    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        if (!auth()->user()->hasRole('manager') && !auth()->user()->hasRole('hr_admin')) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave-requests.index')->with('warning', 'Only pending requests can be updated.');
        }

        $data = $request->validated();
        $leaveRequest->status = $data['status'];
        $leaveRequest->approved_by = auth()->id();
        $leaveRequest->remarks = $data['remarks'] ?? null;
        $leaveRequest->save();

        if ($data['status'] === 'approved') {
            $days = Carbon::parse($leaveRequest->start_date)->diffInDays(Carbon::parse($leaveRequest->end_date)) + 1;
            $balance = LeaveBalance::firstOrCreate([
                'user_id' => $leaveRequest->user_id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'year' => Carbon::parse($leaveRequest->start_date)->year,
            ]);
            $balance->increment('used_days', $days);
        }

        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if (auth()->id() !== $leaveRequest->user_id && !auth()->user()->hasRole('hr_admin')) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending' && !auth()->user()->hasRole('hr_admin')) {
            return redirect()->route('leave-requests.index')->with('warning', 'Only pending requests can be removed.');
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Leave request deleted successfully.');
    }

    protected function authorizeRequestAccess(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        if ($user->hasRole('hr_admin')) {
            return;
        }

        if ($user->hasRole('manager') && optional($leaveRequest->user->employee)->department === optional($user->employee)->department) {
            return;
        }

        if ($leaveRequest->user_id === $user->id) {
            return;
        }

        abort(403);
    }
}

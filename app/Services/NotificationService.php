<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Notify user when their leave request is created.
     */
    public static function leaveRequestCreated(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $title = 'Leave Request Submitted';
        $message = "Your {$leaveRequest->leaveType->name} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been submitted.";

        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'leave_created',
            'leave_request_id' => $leaveRequest->id,
        ]);

        // Notify managers and HR if approval is required
        if ($leaveRequest->leaveType->requires_approval) {
            self::notifyApprovers($leaveRequest);
        }
    }

    /**
     * Notify approvers (managers and HR) when there's a pending leave request.
     */
    public static function notifyApprovers(LeaveRequest $leaveRequest)
    {
        $employeeDepartment = optional($leaveRequest->user->employee)->department;
        $employeeName = $leaveRequest->user->name;

        // Notify HR admins
        $hrAdmins = User::where('role', 'hr_admin')->get();
        foreach ($hrAdmins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'New Leave Request for Approval',
                'message' => "{$employeeName} has submitted a {$leaveRequest->leaveType->name} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} for approval.",
                'type' => 'leave_pending_approval',
                'leave_request_id' => $leaveRequest->id,
            ]);
        }

        // Notify managers in the same department
        if ($employeeDepartment) {
            $managers = User::whereHas('employee', function ($query) use ($employeeDepartment) {
                $query->where('department', $employeeDepartment);
            })
                ->where('role', 'manager')
                ->get();

            foreach ($managers as $manager) {
                Notification::create([
                    'user_id' => $manager->id,
                    'title' => 'New Leave Request for Approval',
                    'message' => "{$employeeName} has submitted a {$leaveRequest->leaveType->name} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} for approval.",
                    'type' => 'leave_pending_approval',
                    'leave_request_id' => $leaveRequest->id,
                ]);
            }
        }
    }

    /**
     * Notify user when their leave request is approved.
     */
    public static function leaveRequestApproved(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $approverName = optional($leaveRequest->approver)->name ?? 'Admin';
        $title = 'Leave Request Approved';
        $message = "Your {$leaveRequest->leaveType->name} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been approved by {$approverName}.";
        if ($leaveRequest->remarks) {
            $message .= " Remarks: {$leaveRequest->remarks}";
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'leave_approved',
            'leave_request_id' => $leaveRequest->id,
        ]);
    }

    /**
     * Notify user when their leave request is rejected.
     */
    public static function leaveRequestRejected(LeaveRequest $leaveRequest)
    {
        $user = $leaveRequest->user;
        $approverName = optional($leaveRequest->approver)->name ?? 'Admin';
        $title = 'Leave Request Rejected';
        $message = "Your {$leaveRequest->leaveType->name} leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been rejected by {$approverName}.";
        if ($leaveRequest->remarks) {
            $message .= " Reason: {$leaveRequest->remarks}";
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'leave_rejected',
            'leave_request_id' => $leaveRequest->id,
        ]);
    }
}

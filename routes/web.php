<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('hr_admin')) {
        return redirect()->route('dashboard');
    }

    if ($user->hasRole('manager')) {
        return redirect()->route('manager-dashboard');
    }

    // default: employee
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('role:hr_admin,employee,manager')
        ->name('dashboard');

    // Notifications (All authenticated users)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');

    // Manager Dashboard
    Route::get('manager-dashboard', [\App\Http\Controllers\ManagerDashboardController::class, 'index'])->middleware('role:manager')->name('manager-dashboard');
    Route::post('manager-dashboard/approve/{leaveRequest}', [\App\Http\Controllers\ManagerDashboardController::class, 'approve'])->middleware('role:manager')->name('manager-dashboard.approve');
    Route::post('manager-dashboard/reject/{leaveRequest}', [\App\Http\Controllers\ManagerDashboardController::class, 'reject'])->middleware('role:manager')->name('manager-dashboard.reject');

    // Employee Management (HR Admin)
    Route::get('employees/search', [EmployeeController::class, 'search'])->middleware('role:hr_admin')->name('employees.search');
    Route::resource('employees', EmployeeController::class)->middleware('role:hr_admin');

    // Leave Types (HR Admin)
    Route::resource('leave-types', LeaveTypeController::class)->middleware('role:hr_admin');

    // Leave Requests (All authenticated users)
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::get('approvals', [LeaveRequestController::class, 'approvals'])->name('approvals');
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Reports (HR Admin & Manager)
    Route::get('reports', [ReportController::class, 'index'])->middleware('role:hr_admin,manager')->name('reports');
    Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->middleware('role:hr_admin,manager')->name('reports.export-csv');
});

<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

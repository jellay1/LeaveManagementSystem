<?php

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

    // Employee Management (HR Admin)
    Route::resource('employees', EmployeeController::class)->middleware('role:hr_admin');

    // Leave Types (HR Admin)
    Route::resource('leave-types', LeaveTypeController::class)->middleware('role:hr_admin');

    // Leave Requests (All authenticated users)
    Route::resource('leave-requests', LeaveRequestController::class);

    // Reports (HR Admin)
    Route::get('reports', [ReportController::class, 'index'])->middleware('role:hr_admin')->name('reports');
});

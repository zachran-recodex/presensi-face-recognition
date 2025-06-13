<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings routes
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    // Employee management routes (Admin only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::post('employees/{employee}/register-face', [EmployeeController::class, 'registerFace'])->name('employees.register-face');
        Route::delete('employees/{employee}/delete-face', [EmployeeController::class, 'deleteFace'])->name('employees.delete-face');

        Route::resource('locations', LocationController::class);
        Route::post('locations/{location}/check-radius', [LocationController::class, 'checkRadius'])->name('locations.check-radius');

        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');
        Route::get('attendances/export', [AttendanceController::class, 'exportReport'])->name('attendances.export');
    });

    // Employee attendance routes
    Route::get('attendance', [AttendanceController::class, 'employee'])->name('attendance.employee');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

require __DIR__.'/auth.php';

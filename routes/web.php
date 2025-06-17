<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FaceEnrollmentController;
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
    // Settings Routes
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    // Face Enrollment Routes
    Route::get('face/enroll', [FaceEnrollmentController::class, 'create'])->name('face.enroll');
    Route::post('face/enroll', [FaceEnrollmentController::class, 'store'])->name('face.enroll.store');
    Route::get('face/edit', [FaceEnrollmentController::class, 'edit'])->name('face.edit');
    Route::post('face/update', [FaceEnrollmentController::class, 'update'])->name('face.update');
    Route::delete('face/delete', [FaceEnrollmentController::class, 'destroy'])->name('face.delete');
    Route::post('face/test-verification', [FaceEnrollmentController::class, 'testVerification'])->name('face.test');

    // Attendance Routes (for all users)
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::get('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('attendance/process', [AttendanceController::class, 'processAttendance'])->name('attendance.process');
    Route::get('attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Location Management
        Route::resource('locations', LocationController::class);
        Route::post('locations/{location}/toggle-status', [LocationController::class, 'toggleStatus'])->name('locations.toggle-status');
        Route::post('locations/validate-coordinates', [LocationController::class, 'validateCoordinates'])->name('locations.validate-coordinates');

        // User Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/reset-face', [App\Http\Controllers\Admin\UserController::class, 'resetFaceEnrollment'])->name('users.reset-face');

        // Attendance Management
        Route::get('attendance/history', [AttendanceController::class, 'adminHistory'])->name('attendance.history');

    });
});

require __DIR__.'/auth.php';

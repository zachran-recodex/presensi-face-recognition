<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaceApiTestController;
use App\Http\Controllers\FaceEnrollmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings Routes
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');

    // Face Enrollment Routes
    Route::get('face/enroll', [FaceEnrollmentController::class, 'create'])->name('face.enroll');
    Route::post('face/enroll', [FaceEnrollmentController::class, 'store'])->name('face.enroll.store');
    Route::get('face/edit', [FaceEnrollmentController::class, 'edit'])->name('face.edit');
    Route::post('face/update', [FaceEnrollmentController::class, 'update'])->name('face.update');
    Route::delete('face/delete', [FaceEnrollmentController::class, 'destroy'])->name('face.delete');
    Route::post('face/test-verification', [FaceEnrollmentController::class, 'testVerification'])->name('face.test');

    // Attendance Routes
    Route::get('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::get('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('attendance/process', [AttendanceController::class, 'processAttendance'])->name('attendance.process');
    Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::post('users/{user}/reset-face', [App\Http\Controllers\UserController::class, 'resetFaceEnrollment'])->name('users.reset-face');

        // Location Management
        Route::resource('locations', LocationController::class);
        Route::post('locations/{location}/toggle-status', [LocationController::class, 'toggleStatus'])->name('locations.toggle-status');
        Route::post('locations/validate-coordinates', [LocationController::class, 'validateCoordinates'])->name('locations.validate-coordinates');

        // Attendance Management
        Route::get('attendance/history', [AttendanceController::class, 'adminHistory'])->name('attendance.history');

    });

    // Super Admin Only Routes
    Route::middleware(['super_admin'])->prefix('admin')->name('admin.')->group(function () {
        // Face API Testing Routes (Super Admin Only)
        Route::get('face-api-test', [FaceApiTestController::class, 'index'])->name('face-api-test.index');
        Route::post('face-api-test/connection', [FaceApiTestController::class, 'testConnection'])->name('face-api-test.connection');
        Route::post('face-api-test/counters', [FaceApiTestController::class, 'getCounters'])->name('face-api-test.counters');
        Route::post('face-api-test/create-gallery', [FaceApiTestController::class, 'createGallery'])->name('face-api-test.create-gallery');
        Route::post('face-api-test/my-galleries', [FaceApiTestController::class, 'getMyGalleries'])->name('face-api-test.my-galleries');
        Route::post('face-api-test/list-faces', [FaceApiTestController::class, 'listFaces'])->name('face-api-test.list-faces');
        Route::post('face-api-test/test-enrollment', [FaceApiTestController::class, 'testEnrollment'])->name('face-api-test.test-enrollment');
        Route::post('face-api-test/test-verification', [FaceApiTestController::class, 'testVerification'])->name('face-api-test.test-verification');
        Route::post('face-api-test/test-identification', [FaceApiTestController::class, 'testIdentification'])->name('face-api-test.test-identification');
        Route::post('face-api-test/test-comparison', [FaceApiTestController::class, 'testComparison'])->name('face-api-test.test-comparison');
        Route::post('face-api-test/test-deletion', [FaceApiTestController::class, 'testDeletion'])->name('face-api-test.test-deletion');
        Route::post('face-api-test/update-gallery-id', [FaceApiTestController::class, 'updateGalleryId'])->name('face-api-test.update-gallery-id');
    });
});

require __DIR__.'/auth.php';

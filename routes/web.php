<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\AttendanceController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Application Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function() { return redirect('/dashboard'); });
    Route::get('/dashboard', [SchoolClassController::class, 'index'])->name('dashboard');
    
    // Resource Controller for Class CRUD
    Route::resource('classes', SchoolClassController::class);
    
    // Standard Controllers for handling Attendance forms
    Route::get('/classes/{schoolClass}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/classes/{schoolClass}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/analytics', [AttendanceController::class, 'analytics'])->name('analytics');
});
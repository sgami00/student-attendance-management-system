<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\AttendanceController;

// Guest Routes (Login Screen)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Application Routes (Kailangang Naka-login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function() { return redirect('/dashboard'); });
    Route::get('/dashboard', [SchoolClassController::class, 'index'])->name('dashboard');
    
    // Resource Controller for Class CRUD (index, create, store, show, edit, update, destroy)
    Route::resource('classes', SchoolClassController::class);
    
    // Custom method sa loob ng SchoolClassController para sa pag-add ng bagong estudyante
    Route::post('/classes/students', [SchoolClassController::class, 'addStudent'])->name('students.store');
    
    // Standard Controllers for handling Attendance forms
    Route::get('/classes/{schoolClass}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/classes/{schoolClass}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/analytics', [AttendanceController::class, 'analytics'])->name('analytics');

    // Individual Attendance Logs management (Edit and Delete per student record row)
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});
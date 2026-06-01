<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, SchoolClassController, AttendanceController};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [SchoolClassController::class, 'index'])->name('dashboard');
    Route::resource('classes', SchoolClassController::class);
    Route::post('/classes/students', [SchoolClassController::class, 'addStudent'])->name('students.store');
    Route::put('/students/{id}', [SchoolClassController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [SchoolClassController::class, 'destroyStudent'])->name('students.destroy');
    
    Route::get('/classes/{schoolClass}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/classes/{schoolClass}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance-log/{id}', [AttendanceController::class, 'updateInline'])->name('attendance.updateInline');
    Route::delete('/attendance-log/{id}', [AttendanceController::class, 'destroyInline'])->name('attendance.destroyInline');
});
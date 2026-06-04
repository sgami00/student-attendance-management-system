<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, SchoolClassController, AttendanceController, StudentAuthController};

Route::get('/', function () { return redirect()->route('login'); });

// ─── Teacher Auth ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Student Auth ─────────────────────────────────────────────────────────────
Route::get('/student/login', [StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login.submit');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
Route::get('/student/attendance', [StudentAuthController::class, 'attendance'])->name('student.attendance');

// ─── Teacher Protected Routes ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [SchoolClassController::class, 'index'])->name('dashboard');

    // ── All Students (across all classes ng teacher) ──
    Route::get('/my-students', [SchoolClassController::class, 'allStudents'])->name('students.all');

    // Analytics
    Route::get('/analytics', [AttendanceController::class, 'analytics'])->name('analytics');

    // Classes Resource
    Route::resource('classes', SchoolClassController::class);
    Route::post('/classes/students', [SchoolClassController::class, 'addStudent'])->name('students.store');
    Route::put('/students/{id}', [SchoolClassController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [SchoolClassController::class, 'destroyStudent'])->name('students.destroy');

    // Student Attendance Log (teacher view)
    Route::get('/students/{student}/log', [AttendanceController::class, 'studentLog'])->name('students.log');

    // Attendance Routes
    Route::get('/classes/{schoolClass}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/classes/{schoolClass}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance-log/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance-log/{id}', [AttendanceController::class, 'updateInline'])->name('attendance.update');
    Route::delete('/attendance-log/{id}', [AttendanceController::class, 'destroyInline'])->name('attendance.destroy');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\SchoolClassApiController;

Route::withoutMiddleware(['auth', 'auth:sanctum', 'auth:web'])->group(function () {

    // ─── CLASSES API (prefix: /api/school-classes para hindi mag-conflict sa web /classes) ───
    Route::get('/school-classes',              [SchoolClassApiController::class, 'index']);
    Route::post('/school-classes',             [SchoolClassApiController::class, 'store']);
    Route::get('/school-classes/{id}',         [SchoolClassApiController::class, 'show']);
    Route::put('/school-classes/{id}',         [SchoolClassApiController::class, 'update']);
    Route::delete('/school-classes/{id}',      [SchoolClassApiController::class, 'destroy']);
    Route::post('/school-classes/{id}/students', [SchoolClassApiController::class, 'addStudent']);
    Route::get('/school-classes/{id}/students',  [SchoolClassApiController::class, 'getStudents']);

    // Teacher students overview
    Route::get('/teachers/{teacher_id}/students', [SchoolClassApiController::class, 'getTeacherStudents']);

    // ─── ATTENDANCE ───────────────────────────────────────────────────────────
    // IMPORTANT: static routes MUST be above wildcard {id} routes
    Route::get('/attendance/students', [AttendanceApiController::class, 'getAllStudents']);

    Route::get('/attendance',          [AttendanceApiController::class, 'index']);
    Route::post('/attendance',         [AttendanceApiController::class, 'store']);
    Route::put('/attendance/{id}',     [AttendanceApiController::class, 'update']);
    Route::delete('/attendance/{id}',  [AttendanceApiController::class, 'destroy']);

});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\SchoolClassApiController;

// Lahat ng routes dito ay walang auth middleware
Route::withoutMiddleware(['auth', 'auth:sanctum', 'auth:web'])->group(function () {

    // ─── CLASSES ──────────────────────────────────────────────────────────────
    Route::apiResource('classes', SchoolClassApiController::class);
    Route::post('/classes/{id}/students', [SchoolClassApiController::class, 'addStudent']);
    Route::get('/classes/{id}/students',  [SchoolClassApiController::class, 'getStudents']);

    // ─── ATTENDANCE ───────────────────────────────────────────────────────────
    // IMPORTANT: static routes MUST be above wildcard {id} routes
    Route::get('/attendance/students', [AttendanceApiController::class, 'getAllStudents']);

    Route::get('/attendance',         [AttendanceApiController::class, 'index']);
    Route::post('/attendance',        [AttendanceApiController::class, 'store']);
    Route::put('/attendance/{id}',    [AttendanceApiController::class, 'update']);
    Route::delete('/attendance/{id}', [AttendanceApiController::class, 'destroy']);

});
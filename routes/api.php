<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\SchoolClassApiController;
use App\Http\Controllers\Api\StudentApiController;

Route::withoutMiddleware(['auth', 'auth:sanctum', 'auth:web'])->group(function () {

    // ─── STUDENTS API ─────────────────────────────────────────────────────────
    Route::get('/students',          [StudentApiController::class, 'index']);
    Route::post('/students',         [StudentApiController::class, 'store']);
    Route::get('/students/{id}',     [StudentApiController::class, 'show']);
    Route::put('/students/{id}',     [StudentApiController::class, 'update']);
    Route::patch('/students/{id}',   [StudentApiController::class, 'update']);
    Route::delete('/students/{id}',  [StudentApiController::class, 'destroy']);

    // ─── CLASSES API ─────────────────────────────────────────────────────────
    Route::get('/classes',                 [SchoolClassApiController::class, 'index']);
    Route::post('/classes',                [SchoolClassApiController::class, 'store']);
    Route::get('/classes/{id}',            [SchoolClassApiController::class, 'show']);
    Route::put('/classes/{id}',            [SchoolClassApiController::class, 'update']);
    Route::patch('/classes/{id}',          [SchoolClassApiController::class, 'update']);
    Route::delete('/classes/{id}',         [SchoolClassApiController::class, 'destroy']);

    // Students in a class
    Route::get('/classes/{id}/students',                    [SchoolClassApiController::class, 'getStudents']);
    Route::post('/classes/{id}/students',                   [SchoolClassApiController::class, 'addStudent']);
    Route::get('/classes/{id}/students/{studentId}',        [SchoolClassApiController::class, 'getStudent']);
    Route::put('/classes/{id}/students/{studentId}',        [SchoolClassApiController::class, 'updateStudent']);
    Route::patch('/classes/{id}/students/{studentId}',      [SchoolClassApiController::class, 'updateStudent']);
    Route::delete('/classes/{id}/students/{studentId}',     [SchoolClassApiController::class, 'removeStudent']);

    // ─── SCHOOL-CLASSES API — backwards compat ───────────────────────────────
    Route::get('/school-classes',                [SchoolClassApiController::class, 'index']);
    Route::post('/school-classes',               [SchoolClassApiController::class, 'store']);
    Route::get('/school-classes/{id}',           [SchoolClassApiController::class, 'show']);
    Route::put('/school-classes/{id}',           [SchoolClassApiController::class, 'update']);
    Route::patch('/school-classes/{id}',         [SchoolClassApiController::class, 'update']);
    Route::delete('/school-classes/{id}',        [SchoolClassApiController::class, 'destroy']);
    Route::post('/school-classes/{id}/students', [SchoolClassApiController::class, 'addStudent']);
    Route::get('/school-classes/{id}/students',  [SchoolClassApiController::class, 'getStudents']);

    // Teacher students overview
    Route::get('/teachers/{teacher_id}/students', [SchoolClassApiController::class, 'getTeacherStudents']);

    // ─── ATTENDANCE ───────────────────────────────────────────────────────────
    Route::get('/attendance/student/{id}',    [AttendanceApiController::class, 'showStudent']);
    Route::put('/attendance/student/{id}',    [AttendanceApiController::class, 'updateStudent']);
    Route::patch('/attendance/student/{id}',  [AttendanceApiController::class, 'updateStudent']);
    Route::delete('/attendance/student/{id}', [AttendanceApiController::class, 'destroyStudent']);
    Route::get('/attendance/students',        [AttendanceApiController::class, 'getAllStudents']);
    Route::get('/attendance',                 [AttendanceApiController::class, 'index']);
    Route::post('/attendance',                [AttendanceApiController::class, 'store']);
    Route::get('/attendance/{id}',            [AttendanceApiController::class, 'show']);
    Route::put('/attendance/{id}',            [AttendanceApiController::class, 'update']);
    Route::patch('/attendance/{id}',          [AttendanceApiController::class, 'update']);
    Route::delete('/attendance/{id}',         [AttendanceApiController::class, 'destroy']);

});
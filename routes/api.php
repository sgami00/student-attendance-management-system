<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceApiController;

// Tinanggal ang prefix('v1') group kaya direkta na ang mga daan natin
Route::apiResource('attendance', AttendanceApiController::class);

// Custom Explicit Routes (Naka-base na agad pagkatapos ng /api/)
Route::get('/attendance', [AttendanceApiController::class, 'index']);
Route::post('/attendance', [AttendanceApiController::class, 'store']);
Route::put('/attendance/{id}', [AttendanceApiController::class, 'update']);
Route::delete('/attendance/{id}', [AttendanceApiController::class, 'destroy']);
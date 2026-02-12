<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\MilkingSessionController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ──────────────── Public Routes (No Auth Required) ────────────────
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// ──────────────── Protected Routes (Auth Required) ────────────────
Route::middleware('auth:sanctum')->group(function () {
    
    // ─── Auth Routes ───
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // ─── Animals Module ───
    Route::apiResource('animals', AnimalController::class);

    // ─── Milking Sessions Module ───
    Route::apiResource('milking-sessions', MilkingSessionController::class);

    // ─── Alerts Module ───
    Route::apiResource('alerts', AlertController::class);
    Route::patch('/alerts/{alert}/resolve', [AlertController::class, 'resolve']);

    // ─── Reports Module ───
    Route::get('/reports', ReportsController::class);
    Route::get('/reports/export/pdf', [ReportsController::class, 'exportPdf']);
    Route::get('/reports/export/csv', [ReportsController::class, 'exportCsv']);

    // ─── Users Module ───
    Route::apiResource('users', UserController::class);
    Route::patch('/users/{id}/status', [UserController::class, 'toggleStatus']);

    // ─── Permissions Module ───
    Route::get('/permissions', [PermissionController::class, 'index']);

    // ─── User Activity Module ───
    Route::get('/user-activity', [UserActivityController::class, 'index']);
    Route::post('/user-activity', [UserActivityController::class, 'store']);
});

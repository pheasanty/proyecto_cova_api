<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdenoController;
use App\Http\Controllers\VacaController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\MilkingSessionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\Api\AlertController;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use App\Http\Controllers\Api\ReportsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Rutas públicas
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
    Route::apiResource('animals', AnimalController::class);
    Route::apiResource('milking-sessions', MilkingSessionController::class);
    Route::apiResource('alerts', AlertController::class);
    Route::get('/reports',ReportsController::class);
// Rutas protegidas por Sanctum
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::post('/logout', [AuthController::class, 'logout']);

//     // Ejemplo de ruta protegida por rol
//     Route::middleware('role:admin')->get('/admin/dashboard', function () {
//         return response()->json(['message' => 'Bienvenido, Admin']);
//     });

//     Route::post('/ordenos', [OrdenoController::class, 'store']);

//     Route::get('/vacas', [VacaController::class, 'index']);
//     Route::post('/vacas', [VacaController::class, 'store']);
// });



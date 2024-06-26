<?php

use App\Enums\UserRolesEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Resources\LineController;
use App\Http\Controllers\Resources\UserController;
use App\Http\Controllers\Resources\OperatorController;
use App\Http\Controllers\Resources\ShiftManagerController;
use App\Http\Controllers\Resources\AdministrativeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {

    // User routes
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Administrative routes
    Route::get('administratives', [AdministrativeController::class, 'index']);
    Route::post('administratives', [AdministrativeController::class, 'store']);
    Route::get('administratives/{id}', [AdministrativeController::class, 'show']);
    Route::put('administratives/{id}', [AdministrativeController::class, 'update']);
    Route::delete('administratives/{id}', [AdministrativeController::class, 'destroy']);


    // ShiftManager routes
    Route::get('shiftmanagers', [ShiftManagerController::class, 'index']);
    Route::post('shiftmanagers', [ShiftManagerController::class, 'store']);
    Route::get('shiftmanagers/{id}', [ShiftManagerController::class, 'show']);
    Route::put('shiftmanagers/{id}', [ShiftManagerController::class, 'update']);
    Route::delete('shiftmanagers/{id}', [ShiftManagerController::class, 'destroy']);


    // Line routes
    Route::get('lines', [LineController::class, 'index']);
    Route::post('lines', [LineController::class, 'store']);
    Route::get('lines/{id}', [LineController::class, 'show']);
    Route::put('lines/{id}', [LineController::class, 'update']);
    Route::delete('lines/{id}', [LineController::class, 'destroy']);

    // Operator routes

    Route::get('operators', [OperatorController::class, 'index']);
    Route::post('operators', [OperatorController::class, 'store']);
    Route::get('operators/{id}', [OperatorController::class, 'show']);
    Route::put('operators/{id}', [OperatorController::class, 'update']);
    Route::delete('operators/{id}', [OperatorController::class, 'destroy']);
});

// Route::middleware(['auth:api', 'role:' . UserRolesEnum::SHIFTMANAGER->value])->group(function () {
//     Route::get('/shift-manager', function () {
//         return response()->json(['data' => 'This is a shift manager route']);
//     });
// });

// Auth routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

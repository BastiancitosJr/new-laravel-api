<?php

use App\Enums\UserRolesEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\Resources\LineController;
use App\Http\Controllers\Resources\UserController;
use App\Http\Controllers\Resources\OperatorController;
use App\Http\Controllers\Resources\Kpi\SecurityController;
use App\Http\Controllers\Resources\ShiftManagerController;
use App\Http\Controllers\Resources\AdministrativeController;
use App\Http\Controllers\Resources\Kpi\CleanlinessController;
use App\Http\Controllers\Resources\Kpi\ProductivityController;
use App\Http\Controllers\Resources\Kpi\LabelingQualityController;
use App\Http\Controllers\Resources\Kpi\PeerObservationsController;
use App\Http\Controllers\Resources\Kpi\MonthlyProgrammingProgressController;

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

Route::middleware(['auth:api', 'role:' . UserRolesEnum::SHIFTMANAGER->value])->group(function () {

    Route::get('productivities', [ProductivityController::class, 'index']);
    Route::get('productivities-line/{id}', [ProductivityController::class, 'indexLine']);
    Route::get('productivities-shift/{id}', [ProductivityController::class, 'indexShift']);
    Route::post('productivities/{id}', [ProductivityController::class, 'store']);

    Route::get('labeling-qualities', [LabelingQualityController::class, 'index']);
    Route::get('labeling-qualities-line/{id}', [LabelingQualityController::class, 'indexLine']);
    Route::get('labeling-qualities-shift/{id}', [LabelingQualityController::class, 'indexShift']);
    Route::post('labeling-qualities/{id}', [LabelingQualityController::class, 'store']);

    Route::get('monthly-pps', [MonthlyProgrammingProgressController::class, 'index']);
    Route::get('monthly-pps-line/{id}', [MonthlyProgrammingProgressController::class, 'indexLine']);
    Route::get('monthly-pps-shift/{id}', [MonthlyProgrammingProgressController::class, 'indexShift']);
    Route::get('monthly-pps/verify-month', [MonthlyProgrammingProgressController::class, 'verifyMonth']);
    Route::post('monthly-pps/{id}', [MonthlyProgrammingProgressController::class, 'store']);
    Route::put('monthly-pps/{id}', [MonthlyProgrammingProgressController::class, 'update']);


    Route::get('peer-observations', [PeerObservationsController::class, 'index']);
    Route::get('peer-observations-line/{id}', [PeerObservationsController::class, 'indexLine']);
    Route::get('peer-observations-shift/{id}', [PeerObservationsController::class, 'indexShift']);
    Route::post('peer-observations/{id}', [PeerObservationsController::class, 'store']);

    Route::get('securities', [SecurityController::class, 'index']);
    Route::get('securities-line/{id}', [SecurityController::class, 'indexLine']);
    Route::get('securities-shift/{id}', [SecurityController::class, 'indexShift']);
    Route::post('securities/{id}', [SecurityController::class, 'store']);

    Route::get('cleanlinesses', [CleanlinessController::class, 'index']);
    Route::get('cleanlinesses-line/{id}', [CleanlinessController::class, 'indexLine']);
    Route::get('cleanlinesses-shift/{id}', [CleanlinessController::class, 'indexShift']);
    Route::post('cleanlinesses/{id}', [CleanlinessController::class, 'store']);

    Route::get('shifts', [ShiftController::class, 'index']);
    Route::get('shifts/active', [ShiftController::class, 'active']);
    Route::get('shifts/{id}', [ShiftController::class, 'show']);
    Route::post('shifts/open', [ShiftController::class, 'store']);
    Route::post('shifts/close', [ShiftController::class, 'close']);
});

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

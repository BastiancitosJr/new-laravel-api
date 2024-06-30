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

    Route::get('productivity', [ProductivityController::class, 'index']);
    Route::get('productivity-line/{id}', [ProductivityController::class, 'indexLine']);
    Route::get('productivity-shift/{id}', [ProductivityController::class, 'indexShift']);
    Route::post('productivity/{id}', [ProductivityController::class, 'store']);

    Route::get('labeling-quality', [LabelingQualityController::class, 'index']);
    Route::get('labeling-quality-line/{id}', [LabelingQualityController::class, 'indexLine']);
    Route::get('labeling-quality-shift/{id}', [LabelingQualityController::class, 'indexShift']);
    Route::post('labeling-quality/{id}', [LabelingQualityController::class, 'store']);

    Route::get('monthly-pp', [MonthlyProgrammingProgressController::class, 'index']);
    Route::get('monthly-pp-line/{id}', [MonthlyProgrammingProgressController::class, 'indexLine']);
    Route::get('monthly-pp-shift/{id}', [MonthlyProgrammingProgressController::class, 'indexShift']);
    Route::post('monthly-pp/{id}', [MonthlyProgrammingProgressController::class, 'store']);

    Route::get('peer-observations', [PeerObservationsController::class, 'index']);
    Route::get('peer-observations-line/{id}', [PeerObservationsController::class, 'indexLine']);
    Route::get('peer-observations-shift/{id}', [PeerObservationsController::class, 'indexShift']);
    Route::post('peer-observations/{id}', [PeerObservationsController::class, 'store']);

    Route::get('security', [SecurityController::class, 'index']);
    Route::get('security-line/{id}', [SecurityController::class, 'indexLine']);
    Route::get('security-shift/{id}', [SecurityController::class, 'indexShift']);
    Route::post('security/{id}', [SecurityController::class, 'store']);

    Route::get('cleanliness', [CleanlinessController::class, 'index']);
    Route::get('cleanliness-line/{id}', [CleanlinessController::class, 'indexLine']);
    Route::get('cleanliness-shift/{id}', [CleanlinessController::class, 'indexShift']);
    Route::post('cleanliness/{id}', [CleanlinessController::class, 'store']);

    Route::get('shift', [ShiftController::class, 'index']);
    Route::get('shift/active', [ShiftController::class, 'active']);
    Route::get('shift/{id}', [ShiftController::class, 'show']);
    Route::post('shift/open', [ShiftController::class, 'store']);
    Route::post('shift/close', [ShiftController::class, 'close']);
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

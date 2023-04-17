<?php

use App\Constants\GlobalConstant;
use App\Http\Controllers\APIs\V1\AuthController;
use App\Http\Controllers\APIs\V1\PackageController;
use App\Http\Controllers\APIs\V1\UserController;
use Illuminate\Support\Facades\Route;

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


/**
 * API version 1
 */
Route::group([
    'prefix' => 'v1',
    // 'namespace' => ' App\Http\Controllers\APIs\V1'
], function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/verify-email', [AuthController::class, 'verifyEmail']);
    Route::middleware(GlobalConstant::$AUTH_MIDDLEWARE)
        ->get('auth/me', [AuthController::class, 'me']);
    Route::post(
        'auth/forgot-password',
        [AuthController::class, 'forgotPassword']
    );
    Route::middleware(GlobalConstant::$AUTH_MIDDLEWARE)
        ->patch(
            'auth/change-password',
            [AuthController::class, 'changePassword']
        );
    // Route::post('auth/update-me', [AuthController::class, 'updateMe']);

    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });
    Route::middleware(GlobalConstant::$AUTH_MIDDLEWARE)->apiResource('users', UserController::class);
    Route::apiResource('packages', PackageController::class);
});

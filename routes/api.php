<?php

use App\Http\Controllers\APIs\V1\AuthController;
use App\Http\Controllers\APIs\V1\PackageController;
use App\Http\Controllers\APIs\V1\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * API version 1
 */
Route::group([
    'prefix'=>'v1',
    // 'namespace'=>' App\Http\Controllers\APIs\V1'
], function() {
    Route::apiResource('/auth', AuthController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/packages', PackageController::class);
});
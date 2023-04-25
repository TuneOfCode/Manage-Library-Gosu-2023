<?php

use App\Constants\GlobalConstant;
use App\Http\Controllers\APIs\V1\AuthController;
use App\Http\Controllers\APIs\V1\BookController;
use App\Http\Controllers\APIs\V1\CategoryController;
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
    #region Auth
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/verify-email', [AuthController::class, 'verifyEmail']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post(
        'auth/forgot-password',
        [AuthController::class, 'forgotPassword']
    );
    Route::patch(
        'auth/change-password',
        [AuthController::class, 'changePassword']
    );
    Route::patch(
        'auth/update-me',
        [AuthController::class, 'updateMe']
    );
    Route::post(
        'auth/upload-avatar',
        [AuthController::class, 'uploadAvatar']
    );
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
    #endregion

    #region Users
    Route::middleware(GlobalConstant::$AUTH_MIDDLEWARE)->apiResource('users', UserController::class);
    #endregion

    #region Packages
    Route::apiResource('packages', PackageController::class);
    #endregion

    #region Categories
    Route::apiResource('categories', CategoryController::class);
    #endregion

    #region Books
    Route::apiResource('books', BookController::class);
    // //Lọc sách mới.
    // Route::get('/books/new', function () {
    //     $new_books = App\Models\Book::where('created_At', '>=', now()->subDays(30))->get();
    //     return response()->json($new_books);
    // });
    // //lọc sách cũ.
    // Route::get('/books/old', function () {
    //     $old_books = App\Models\Book::where('created_At', '<', now()->subDays(30))->get();
    //     return response()->json($old_books);
    // });
    #endregion
        
});
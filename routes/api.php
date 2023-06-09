<?php

use App\Constants\GlobalConstant;
use App\Constants\PermissionConstant;
use App\Http\Controllers\APIs\V1\AuthController;
use App\Http\Controllers\APIs\V1\BookController;
use App\Http\Controllers\APIs\V1\BookUserController;
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
    'prefix' => 'v1'
], function () {
    #region Auth
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('register', [
            AuthController::class,
            'register'
        ]);
        Route::post('login', [
            AuthController::class,
            'login'
        ]);
        Route::post('resend-otp-email', [
            AuthController::class,
            'resendOtpEmail'
        ]);
        Route::post('verify-email', [
            AuthController::class,
            'verifyEmail'
        ]);
        Route::get('me', [AuthController::class, 'me']);
        Route::post(
            'forgot-password',
            [AuthController::class, 'forgotPassword']
        );
        Route::patch(
            'change-password',
            [AuthController::class, 'changePassword']
        );
        Route::patch(
            'update-me',
            [AuthController::class, 'updateMe']
        );
        Route::post(
            'upload-avatar',
            [AuthController::class, 'uploadAvatar']
        );
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    });
    #endregion

    #region Users
    Route::group([
        'prefix' => 'users',
        'middleware' => [
            GlobalConstant::$AUTH_MIDDLEWARE,
            GlobalConstant::$ROLE_ADMIN
        ]
    ], function () {
        Route::get('/', [
            UserController::class,
            'index'
        ])->middleware('permission:' . PermissionConstant::$READ_ALL_USER);
        Route::get('/{id}', [
            UserController::class,
            'show'
        ])->middleware('permission:' . PermissionConstant::$READ_A_USER);;
        Route::delete('/{id}', [
            UserController::class,
            'destroy'
        ])->middleware('permission:' . PermissionConstant::$DELETE_USER);;
        Route::patch('/lock/{id}', [
            UserController::class,
            'lock'
        ])->middleware('permission:' . PermissionConstant::$LOCK_USER);;
        Route::patch('/unlock/{id}', [
            UserController::class,
            'unlock'
        ])->middleware('permission:' . PermissionConstant::$UNLOCK_USER);;
    });
    #endregion

    #region Packages
    Route::group([
        'prefix' => 'packages',
        'middleware' => [
            GlobalConstant::$AUTH_MIDDLEWARE
        ]
    ], function () {
        Route::get('/', [
            PackageController::class,
            'index'
        ])->middleware('permission:' . PermissionConstant::$READ_ALL_PACKAGE);
        Route::get('/{id}', [
            PackageController::class,
            'show'
        ])->middleware('permission:' . PermissionConstant::$READ_A_PACKAGE);
        Route::post('/', [
            PackageController::class,
            'store'
        ])->middleware('permission:' . PermissionConstant::$CREATE_PACKAGE);
        Route::patch('/{id}', [
            PackageController::class,
            'update'
        ])->middleware('permission:' . PermissionConstant::$UPDATE_PACKAGE);
        Route::put('/{id}', [
            PackageController::class,
            'update'
        ])->middleware('permission:' . PermissionConstant::$UPDATE_PACKAGE);
        Route::delete('/{id}', [
            PackageController::class,
            'destroy'
        ])->middleware('permission:' . PermissionConstant::$DELETE_PACKAGE);
        Route::patch('/{id}/register', [
            PackageController::class,
            'register'
        ])->middleware('permission:' . PermissionConstant::$REGISTER_PACKAGE);
    });
    #endregion

    #region Category
    Route::group([
        'prefix' => 'categories',
        'middleware' => [
            GlobalConstant::$AUTH_MIDDLEWARE
        ]
    ], function () {
        Route::get('/', [
            CategoryController::class,
            'index'
        ])->middleware('permission:' . PermissionConstant::$READ_ALL_CATEGORY_BOOK);
        Route::get('/{id}', [
            CategoryController::class,
            'show'
        ])->middleware('permission:' . PermissionConstant::$READ_A_CATEGORY_BOOK);
        Route::post('/', [
            CategoryController::class,
            'store'
        ])->middleware('permission:' . PermissionConstant::$CREATE_CATEGORY_BOOK);
        Route::put('/{id}', [
            CategoryController::class,
            'update'
        ])->middleware('permission:' . PermissionConstant::$UPDATE_CATEGORY_BOOK);
        Route::delete('/{id}', [
            CategoryController::class,
            'destroy'
        ])->middleware('permission:' . PermissionConstant::$DELETE_CATEGORY_BOOK);
    });
    #endregion

    #region Book
    Route::group([
        'prefix' => 'books',
        'middleware' => [
            GlobalConstant::$AUTH_MIDDLEWARE
        ]
    ], function () {
        Route::get('/', [
            BookController::class,
            'index'
        ])->middleware('permission:' . PermissionConstant::$READ_ALL_BOOK);
        Route::get('/{id}', [
            BookController::class,
            'show'
        ])->middleware('permission:' . PermissionConstant::$READ_A_BOOK);
        Route::post('/', [
            BookController::class,
            'store'
        ])->middleware('permission:' . PermissionConstant::$CREATE_BOOK);
        Route::post('/{id}', [
            BookController::class,
            'update'
        ])->middleware('permission:' . PermissionConstant::$UPDATE_BOOK);
        Route::delete('/{id}', [
            BookController::class,
            'destroy'
        ])->middleware('permission:' . PermissionConstant::$DELETE_BOOK);
    });
    #endregion

    #region BookUser
    Route::group([
        'prefix' => 'book-user',
        'middleware' => [
            GlobalConstant::$AUTH_MIDDLEWARE
        ]
    ], function () {
        Route::get('/', [
            BookUserController::class,
            'index'
        ])->middleware('permission:' . PermissionConstant::$READ_STATISTIC);
        Route::get('/{id}', [
            BookUserController::class,
            'show'
        ])->middleware('permission:' . PermissionConstant::$READ_STATISTIC);
        Route::post('/borrow', [
            BookUserController::class,
            'store'
        ])->middleware('permission:' . PermissionConstant::$BORROW_BOOK);
        Route::patch('/approve', [
            BookUserController::class,
            'approve'
        ])->middleware('permission:' . PermissionConstant::$APPROVE_BOOK);
        Route::patch('/reject', [
            BookUserController::class,
            'reject'
        ])->middleware('permission:' . PermissionConstant::$REJECT_BOOK);
        Route::patch('/cancel', [
            BookUserController::class,
            'cancel'
        ])->middleware('permission:' . PermissionConstant::$CANCEL_BOOK);
        Route::patch('/pay', [
            BookUserController::class,
            'pay'
        ])->middleware('permission:' . PermissionConstant::$PAY_MONEY);
        Route::patch('/confirm-received', [
            BookUserController::class,
            'confirmReceived'
        ])->middleware('permission:' . PermissionConstant::$CONFIRM_RECEIVED_BOOK);
        Route::patch('/confirm-returned', [
            BookUserController::class,
            'confirmReturned'
        ])->middleware('permission:' . PermissionConstant::$CONFIRM_RETURNED_BOOK);
        Route::patch('/pay-extra-money', [
            BookUserController::class,
            'payExtraMoney'
        ])->middleware('permission:' . PermissionConstant::$PAY_MONEY);
        Route::delete('/', [
            BookUserController::class,
            'destroy'
        ])->middleware('permission:' . PermissionConstant::$DELETE_STATISTIC);
    });
    #endregion
});

<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function ($router) {
    Route::prefix('auth')->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function () {

        Route::prefix('auth')->group(function () {
            Route::get('/', [UserAuthController::class, 'show']);
            Route::put('/', [UserAuthController::class, 'update']);
        });
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AdminAuthController::class, 'login']);
        });

        Route::middleware('auth:admin')->group(function () {

            Route::prefix('auth')->group(function () {
                Route::get('/', [AdminAuthController::class, 'show']);
                Route::put('/', [AdminAuthController::class, 'update']);
            });

            Route::prefix('user')->group(function () {
                Route::post('/', [UserController::class, 'store']);
                Route::get('/', [UserController::class, 'index']);
                Route::get('/{id}', [UserController::class, 'show']);
                Route::put('/{id}', [UserController::class, 'update']);
                Route::delete('/{id}', [UserController::class, 'destroy']);
            });

            Route::get('/', [AdminController::class, 'index']);
            Route::get('/{id}', [AdminController::class, 'show']);
            Route::put('/{id}', [AdminController::class, 'update']);
            Route::post('create', [AdminController::class, 'store']);
            Route::delete('/{id}', [AdminController::class, 'destroy']);

        });
    });
});

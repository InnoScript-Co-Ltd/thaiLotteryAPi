<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function ($router) {
    Route::prefix('auth')->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
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

            Route::get('/', [AdminController::class, 'index']);
            Route::get('/{id}', [AdminController::class, 'show']);
            Route::put('/{id}', [AdminController::class, 'update']);
            Route::post('create', [AdminController::class, 'store']);
            Route::delete('/{id}', [AdminController::class, 'destroy']);
        });
    });
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoggerController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->withoutMiddleware(['auth:api', 'api', 'isActive'])
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('refresh', 'refresh');
        Route::post('logout', 'logout');
    });

Route::group(['prefix' => 'admin'], function () {
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('me', 'me');
            Route::patch('me', 'updateProfile');
        });
    //Route::get('me', [UserController::class, 'me']);
    //Route::patch('me', [UserController::class, 'updateProfile']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);

    #new Resource to here

    Route::prefix('logger')
        ->middleware(['auth:api', 'isActive', 'permission:system|logger-read'])
        ->controller(LoggerController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{logger}', 'show');
        });
});

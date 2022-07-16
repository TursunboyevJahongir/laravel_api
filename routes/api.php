<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoggerController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->withoutMiddleware(['auth:api', 'api'])
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

    Route::get('roles', [RoleController::class, 'index'])->middleware('can:read role');
    Route::get('permissions', [RoleController::class, 'permissions'])->middleware('can:read role');
    Route::get('role/{name}', [RoleController::class, 'show'])->middleware('can:read role');
    Route::post('role', [RoleController::class, 'create'])->middleware('can:create role');
    Route::patch('role/{name}', [RoleController::class, 'update'])->middleware('can:update role');
    Route::delete('role/{name}', [RoleController::class, 'delete'])->middleware('can:delete role');

    #new Resource to here

    Route::prefix('logger')
        ->middleware(['auth:api', 'isActive', 'permission:system|logger-read'])
        ->controller(LoggerController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{logger}', 'show');
        });
});

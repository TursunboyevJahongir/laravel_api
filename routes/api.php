<?php

use App\Http\Controllers\Api\{AuthController,
    CategoryController,
    LoggerController,
    PermissionController,
    ProductController,
    RoleController,
    UserController
};
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
        ->withoutMiddleware('isActive')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('me', 'me');
            Route::patch('me', 'updateProfile');
        });

    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    Route::apiResource('roles', RoleController::class);
    Route::prefix('roles')
        ->controller(RoleController::class)
        ->group(function () {
            Route::post('/{role}/permissions/add', 'assignPermission');
            Route::post('/{role}/permissions/sub', 'revokePermission');
            Route::post('/{role}/permissions/sync', 'syncPermissions');
        });

    Route::apiResource('permissions', PermissionController::class);
    Route::get('permissions/check-permission', [PermissionController::class, 'hasAllPermissions']);//todo

    #new Resource to here

    Route::prefix('logger')
        ->middleware(['auth:api', 'isActive', 'permission:system|logger-read'])
        ->controller(LoggerController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{logger}', 'show');
        });
});

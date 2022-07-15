<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('refresh', 'refresh');
        Route::post('logout', 'logout');
    });

Route::group(['middleware' => ['auth:api'], 'prefix' => 'admin'], function () {
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('me', 'me');
            Route::patch('me', 'updateProfile');
            Route::get('/', 'index')->permission('read-user');
            Route::get('/{user}', 'show')->permission('read-user');
            Route::post('/', 'create')->permission('create-user');
            Route::patch('/{user}', 'update')->permission('update-user')->can('update,user');
            Route::delete('/{user}', 'delete')->permission('delete-user')->can('delete,user');
        });

    Route::prefix('categories')
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get('/', 'index')->permission('read-category');
            Route::get('/{category}', 'show')->permission('read-category');
            Route::post('/', 'create')->permission('create-category');
            Route::patch('/{category}', 'update')->permission('update-category');
            Route::delete('/{category}', 'delete')->permission('delete-category');
        });

    Route::get('roles', [RoleController::class, 'index'])->middleware('can:read role');
    Route::get('permissions', [RoleController::class, 'permissions'])->middleware('can:read role');
    Route::get('role/{name}', [RoleController::class, 'show'])->middleware('can:read role');
    Route::post('role', [RoleController::class, 'create'])->middleware('can:create role');
    Route::patch('role/{name}', [RoleController::class, 'update'])->middleware('can:update role');
    Route::delete('role/{name}', [RoleController::class, 'delete'])->middleware('can:delete role');

    Route::get('products', [ProductController::class, 'products'])->middleware('can:read product');

    #new Resource to here
});

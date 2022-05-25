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

Route::get('category', [CategoryController::class, 'index']);
Route::get('category-products/{categoryProducts}/products', [CategoryController::class, 'products']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('product/{id}/similar', [ProductController::class, 'similar']);
Route::get('search/{string}', [ProductController::class, 'search']);

Route::group(['middleware' => ['auth:api'], 'prefix' => 'admin'], function () {
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('me', 'me');
            Route::put('me', 'updateProfile');
            Route::get('/', 'index')->permission('read-user');
            Route::get('/{user}', 'show')->permission('read-user');
            Route::post('/', 'create')->permission('create-user');
            Route::put('/{user}', 'update')->permission('update-user')->can('update,user');
            Route::delete('/{user}', 'delete')->permission('delete-user')->can('delete,user');
        });

    Route::get('roles', [RoleController::class, 'index'])->middleware('can:read role');
    Route::get('permissions', [RoleController::class, 'permissions'])->middleware('can:read role');
    Route::get('role/{name}', [RoleController::class, 'show'])->middleware('can:read role');
    Route::post('role', [RoleController::class, 'create'])->middleware('can:create role');
    Route::put('role/{name}', [RoleController::class, 'update'])->middleware('can:update role');
    Route::delete('role/{name}', [RoleController::class, 'delete'])->middleware('can:delete role');

    Route::get('categories', [CategoryController::class, 'categories'])->middleware('can:read category');
    Route::post('category', [CategoryController::class, 'create'])->middleware('can:create category');
    Route::put('category/update', [CategoryController::class, 'update'])->middleware('can:update category');
    Route::delete('category/{id}', [CategoryController::class, 'delete'])->middleware('can:delete category');

    Route::get('products', [ProductController::class, 'products'])->middleware('can:read product');
    Route::get('category/{id}/products', [CategoryController::class, 'AdminCategoryProducts'])->middleware('can:read product');
    Route::get('product/{id}', [ProductController::class, 'AdminShow'])->middleware('can:read product');
    Route::post('product', [ProductController::class, 'create'])->middleware('can:create product');
    Route::put('product/{id}', [ProductController::class, 'update'])->middleware('can:update product');
    Route::delete('product/{id}', [ProductController::class, 'delete'])->middleware('can:delete product');

    #new Resource to here
});

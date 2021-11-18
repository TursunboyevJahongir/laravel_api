<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * Login / Register
 */
Route::prefix('auth')->group(static function () {
    Route::post('register', [AuthController::class, 'registration']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('category', [CategoryController::class, 'index']);
Route::get('category/{id}/products', [CategoryController::class, 'products']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('product/{id}/similar', [ProductController::class, 'similar']);
Route::get('search/{string}', [ProductController::class, 'search']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('me', [UserController::class, 'me']);
    Route::put('me', [UserController::class, 'update']);
    
    Route::get('roles', [RoleController::class, 'index'])->middleware('can:read role');
    Route::get('permissions', [RoleController::class, 'permissions'])->middleware('can:read role');
    Route::get('role/{name}', [RoleController::class, 'show'])->middleware('can:read role');
    Route::post('role', [RoleController::class, 'create'])->middleware('can:create role');
    Route::put('role/{name}', [RoleController::class, 'update'])->middleware('can:update role');
    Route::delete('role/{name}', [RoleController::class, 'delete'])->middleware('can:delete role');


    Route::post('category', [CategoryController::class, 'create']);
    Route::post('category/update', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'delete']);

    Route::get('my/products', [ProductController::class, 'myProducts']);
    Route::post('product', [ProductController::class, 'create']);
    Route::post('product/update', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'delete']);

});

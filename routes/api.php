<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/me', [UserController::class, 'update']);
});

<?php

use App\Http\Controllers\{UserController};
use Illuminate\Support\Facades\Route;

Route::prefix('users/profile')->withoutMiddleware('isActive')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'profile');
        Route::patch('/', 'updateProfile');
    });

Route::apiResource('users', UserController::class);

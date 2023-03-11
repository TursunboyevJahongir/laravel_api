<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller('AuthController')
    ->withoutMiddleware(['auth:api', 'api', 'isActive'])
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('refresh', 'refresh');
        Route::post('logout', 'logout');
    });

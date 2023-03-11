<?php

use Illuminate\Support\Facades\Route;

Route::prefix('logger')
    ->middleware(['permission:read-logger'])
    ->controller('LoggerController')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{logger}', 'show');
    });

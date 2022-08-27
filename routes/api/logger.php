<?php

use App\Http\Controllers\{LoggerController};
use Illuminate\Support\Facades\Route;

Route::prefix('logger')
    ->middleware(['permission:read-logger'])
    ->controller(LoggerController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{logger}', 'show');
    });

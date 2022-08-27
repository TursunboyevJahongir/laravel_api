<?php

use App\Http\Controllers\{ProductController};
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

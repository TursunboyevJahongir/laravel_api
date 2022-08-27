<?php

use App\Http\Controllers\{RoleController, PermissionController};
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RoleController::class);
Route::prefix('roles')
    ->controller(RoleController::class)
    ->group(function () {
        Route::post('/{role}/permissions/add', 'assignPermission');
        Route::post('/{role}/permissions/sub', 'revokePermission');
        Route::post('/{role}/permissions/sync', 'syncPermissions');
    });

Route::apiResource('permissions', PermissionController::class);
Route::post('permissions/check-permission/{user?}', [PermissionController::class, 'hasAllPermissions']);

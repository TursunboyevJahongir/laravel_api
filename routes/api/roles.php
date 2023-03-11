<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('roles', 'RoleController');
Route::prefix('roles')
    ->controller('RoleController')
    ->group(function () {
        Route::post('/{role}/permissions/add', 'assignPermission');
        Route::post('/{role}/permissions/sub', 'revokePermission');
        Route::post('/{role}/permissions/sync', 'syncPermissions');
    });

Route::apiResource('permissions', 'PermissionController');
Route::post('permissions/check-permission/{user?}', 'PermissionController@hasAllPermissions');

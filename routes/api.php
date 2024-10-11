<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\PostController;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('auth.me');
    });
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/post', PostController::class);

    // Roles and Permissions
    Route::post('/roles-permissions', [RolePermissionController::class, 'store']);
    Route::get('/roles', [RolePermissionController::class, 'allRoles']);
    Route::get('/permissions', [RolePermissionController::class, 'allPermissions']);
});

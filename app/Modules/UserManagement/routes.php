<?php

use App\Modules\UserManagement\Controllers\RoleController;
use App\Modules\UserManagement\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/user-management')
    ->name('admin.user-management.')
    ->group(function () {
        // Users CRUD
        Route::resource('users', UserController::class)->middleware('permission:user.view');

        // Roles CRUD
        Route::resource('roles', RoleController::class)
            ->except(['show'])
            ->middleware('permission:role.view');
    });

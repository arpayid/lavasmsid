<?php

use App\Modules\UserManagement\Controllers\RoleController;
use App\Modules\UserManagement\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/user-management')
    ->name('admin.user-management.')
    ->group(function () {
        // Users CRUD with per-action permissions
        Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:user.view');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:user.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:user.create');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:user.view');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:user.update');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:user.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user.delete');

        // Roles CRUD with per-action permissions
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:role.view');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:role.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:role.create');
        Route::get('/roles/{name}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:role.update');
        Route::put('/roles/{name}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:role.update');
        Route::delete('/roles/{name}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:role.delete');
    });

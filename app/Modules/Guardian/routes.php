<?php

use App\Modules\Guardian\Controllers\GuardianController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('guardians', [GuardianController::class, 'index'])->name('guardians.index')->middleware('permission:guardian.view');
        Route::get('guardians/create', [GuardianController::class, 'create'])->name('guardians.create')->middleware('permission:guardian.create');
        Route::post('guardians', [GuardianController::class, 'store'])->name('guardians.store')->middleware('permission:guardian.create');
        Route::get('guardians/{guardian}', [GuardianController::class, 'show'])->name('guardians.show')->middleware('permission:guardian.view');
        Route::get('guardians/{guardian}/edit', [GuardianController::class, 'edit'])->name('guardians.edit')->middleware('permission:guardian.update');
        Route::put('guardians/{guardian}', [GuardianController::class, 'update'])->name('guardians.update')->middleware('permission:guardian.update');
        Route::delete('guardians/{guardian}', [GuardianController::class, 'destroy'])->name('guardians.destroy')->middleware('permission:guardian.delete');
    });

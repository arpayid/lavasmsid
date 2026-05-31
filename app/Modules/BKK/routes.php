<?php

use App\Modules\BKK\Controllers\AlumniController;
use App\Modules\BKK\Controllers\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/bkk')
    ->name('admin.bkk.')
    ->group(function () {
        Route::get('/', [AlumniController::class, 'index'])->name('dashboard')->middleware('permission:bkk.view');
        Route::resource('alumni', AlumniController::class)->except(['show'])->middleware('permission:alumni.view');
        Route::resource('vacancies', JobVacancyController::class)->except(['show'])->middleware('permission:bkk.view');
    });

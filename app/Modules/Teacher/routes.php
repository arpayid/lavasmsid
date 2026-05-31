<?php

use App\Modules\Teacher\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('teachers', TeacherController::class)->middleware('permission:teacher.view');
    });

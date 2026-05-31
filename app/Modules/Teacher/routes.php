<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Teacher\Controllers\TeacherController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('teachers', TeacherController::class)->middleware('permission:teacher.view');
    });

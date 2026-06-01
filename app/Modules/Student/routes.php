<?php

use App\Modules\Student\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('students', StudentController::class)->only(['index'])->middleware('permission:student.view');
    });

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Student\Controllers\StudentController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('students', StudentController::class)->middleware('permission:student.view');
    });

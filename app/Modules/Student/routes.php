<?php

use App\Modules\Student\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('students', StudentController::class)->middleware('permission:student.view');
    });

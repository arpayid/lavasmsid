<?php

use App\Modules\Internship\Controllers\InternshipController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/internships')
    ->name('admin.internships.')
    ->group(function () {
        Route::resource('/', InternshipController::class)->parameters(['' => 'internship'])->except(['show'])->middleware('permission:internship.view');
    });

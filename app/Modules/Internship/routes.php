<?php

use App\Modules\Internship\Controllers\InternshipController;
use App\Modules\Internship\Controllers\InternshipLogController;
use App\Modules\Internship\Controllers\InternshipMonitoringController;
use App\Modules\Internship\Controllers\InternshipReportController;
use App\Modules\Internship\Controllers\InternshipScoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/internships')
    ->name('admin.internships.')
    ->group(function () {
        Route::get('/dashboard', [InternshipController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [InternshipController::class, 'index'])->name('index')->middleware('permission:internship.view');
        Route::get('/create', [InternshipController::class, 'create'])->name('create')->middleware('permission:internship.create');
        Route::post('/', [InternshipController::class, 'store'])->name('store')->middleware('permission:internship.create');
        Route::get('/{internship}', [InternshipController::class, 'show'])->name('show')->middleware('permission:internship.view');
        Route::get('/{internship}/edit', [InternshipController::class, 'edit'])->name('edit')->middleware('permission:internship.update');
        Route::put('/{internship}', [InternshipController::class, 'update'])->name('update')->middleware('permission:internship.update');
        Route::delete('/{internship}', [InternshipController::class, 'destroy'])->name('destroy')->middleware('permission:internship.delete');

        // Logs
        Route::get('/logs/list', [InternshipLogController::class, 'index'])->name('logs.index')->middleware('permission:internship.view');
        Route::post('/logs', [InternshipLogController::class, 'store'])->name('logs.store')->middleware('permission:internship.create');
        Route::post('/logs/{internshipLog}/review', [InternshipLogController::class, 'review'])->name('logs.review')->middleware('permission:internship.update');

        // Monitorings
        Route::get('/monitorings/list', [InternshipMonitoringController::class, 'index'])->name('monitorings.index')->middleware('permission:internship.view');
        Route::post('/monitorings', [InternshipMonitoringController::class, 'store'])->name('monitorings.store')->middleware('permission:internship.create');

        // Scores
        Route::post('/scores', [InternshipScoreController::class, 'store'])->name('scores.store')->middleware('permission:internship.update');

        // Reports
        Route::get('/reports/index', [InternshipReportController::class, 'index'])->name('reports.index')->middleware('permission:internship.view');
        Route::get('/reports/export', [InternshipReportController::class, 'export'])->name('reports.export')->middleware('permission:internship.view');
    });

<?php

use App\Modules\BKK\Controllers\AlumniController;
use App\Modules\BKK\Controllers\BkkDashboardController;
use App\Modules\BKK\Controllers\BkkReportController;
use App\Modules\BKK\Controllers\JobApplicationController;
use App\Modules\BKK\Controllers\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/bkk')
    ->name('admin.bkk.')
    ->group(function () {
        Route::get('/', [BkkDashboardController::class, 'index'])->name('dashboard')->middleware('permission:bkk.view');

        Route::resource('alumni', AlumniController::class)->middleware([
            'index' => 'permission:alumni.view',
            'create' => 'permission:alumni.create',
            'store' => 'permission:alumni.create',
            'show' => 'permission:alumni.view',
            'edit' => 'permission:alumni.update',
            'update' => 'permission:alumni.update',
            'destroy' => 'permission:alumni.delete',
        ]);

        Route::resource('vacancies', JobVacancyController::class)->middleware([
            'index' => 'permission:bkk.view',
            'create' => 'permission:bkk.create',
            'store' => 'permission:bkk.create',
            'show' => 'permission:bkk.view',
            'edit' => 'permission:bkk.update',
            'update' => 'permission:bkk.update',
            'destroy' => 'permission:bkk.update',
        ]);

        // Job Applications
        Route::get('applications', [JobApplicationController::class, 'index'])->name('applications.index')->middleware('permission:bkk.view');
        Route::post('applications', [JobApplicationController::class, 'store'])->name('applications.store')->middleware('permission:bkk.update');
        Route::get('applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show')->middleware('permission:bkk.view');
        Route::put('applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.update-status')->middleware('permission:bkk.update');
        Route::delete('applications/{application}', [JobApplicationController::class, 'destroy'])->name('applications.destroy')->middleware('permission:bkk.update');

        // Reports & Exports
        Route::get('reports', [BkkReportController::class, 'index'])->name('reports.index')->middleware('permission:bkk.view');
        Route::get('reports/alumni/export', [BkkReportController::class, 'alumniExport'])->name('reports.alumni-export')->middleware('permission:alumni.export');
        Route::get('reports/vacancies/export', [BkkReportController::class, 'vacancyExport'])->name('reports.vacancy-export')->middleware('permission:bkk.export');
        Route::get('reports/applications/export', [BkkReportController::class, 'applicationExport'])->name('reports.application-export')->middleware('permission:bkk.export');
    });

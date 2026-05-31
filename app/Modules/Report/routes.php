<?php

use App\Modules\Report\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/reports')
    ->name('admin.reports.')
    ->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index')->middleware('permission:report.view');
        Route::get('/students', [ReportController::class, 'students'])->name('students')->middleware('permission:report.view');
        Route::get('/classrooms', [ReportController::class, 'classrooms'])->name('classrooms')->middleware('permission:report.view');
        Route::get('/departments', [ReportController::class, 'departments'])->name('departments')->middleware('permission:report.view');
        Route::get('/teachers', [ReportController::class, 'teachers'])->name('teachers')->middleware('permission:report.view');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance')->middleware('permission:report.view');
        Route::get('/grades', [ReportController::class, 'grades'])->name('grades')->middleware('permission:report.view');
        Route::get('/finance', [ReportController::class, 'finance'])->name('finance')->middleware('permission:report.view');
        Route::get('/ppdb', [ReportController::class, 'ppdb'])->name('ppdb')->middleware('permission:report.view');
        Route::get('/internship', [ReportController::class, 'internship'])->name('internship')->middleware('permission:report.view');
        Route::get('/alumni', [ReportController::class, 'alumni'])->name('alumni')->middleware('permission:report.view');
    });
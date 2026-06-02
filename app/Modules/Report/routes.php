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

        // Exports
        Route::get('/export/sample', [ReportController::class, 'exportSample'])->name('export.sample')->middleware('permission:report.view');
        Route::get('/students/export', [ReportController::class, 'exportStudents'])->name('students.export')->middleware('permission:report.view');
        Route::get('/finance/export', [ReportController::class, 'exportFinance'])->name('finance.export')->middleware('permission:report.view');
        Route::get('/ppdb/export', [ReportController::class, 'exportPpdb'])->name('ppdb.export')->middleware('permission:report.view');
        Route::get('/attendance/export', [ReportController::class, 'exportAttendance'])->name('attendance.export')->middleware('permission:report.view');
        Route::get('/grades/export', [ReportController::class, 'exportGrades'])->name('grades.export')->middleware('permission:report.view');
        Route::get('/internship/export', [ReportController::class, 'exportInternships'])->name('internships.export')->middleware('permission:report.view');
        Route::get('/alumni/export', [ReportController::class, 'exportAlumni'])->name('alumni.export')->middleware('permission:report.view');
        Route::get('/teachers/export', [ReportController::class, 'exportTeachers'])->name('teachers.export')->middleware('permission:report.view');
        Route::get('/classrooms/export', [ReportController::class, 'exportClassrooms'])->name('classrooms.export')->middleware('permission:report.view');

        // Website & Communication Reports
        Route::get('/website', [ReportController::class, 'website'])->name('website')->middleware('permission:report.view');
        Route::get('/website/export', [ReportController::class, 'exportWebsite'])->name('website.export')->middleware('permission:report.view');
        Route::get('/communication', [ReportController::class, 'communication'])->name('communication')->middleware('permission:report.view');
        Route::get('/communication/export', [ReportController::class, 'exportCommunication'])->name('communication.export')->middleware('permission:report.view');
    });

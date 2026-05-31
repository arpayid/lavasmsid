<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Academic\Controllers\AcademicYearController;
use App\Modules\Academic\Controllers\AttendanceController;
use App\Modules\Academic\Controllers\ClassroomController;
use App\Modules\Academic\Controllers\CompetencyController;
use App\Modules\Academic\Controllers\DepartmentController;
use App\Modules\Academic\Controllers\GradeController;
use App\Modules\Academic\Controllers\ScheduleController;
use App\Modules\Academic\Controllers\SemesterController;
use App\Modules\Academic\Controllers\SubjectController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('academic-years', AcademicYearController::class)->middleware('permission:academic.view');
        Route::resource('semesters', SemesterController::class)->except(['show'])->middleware('permission:academic.view');
        Route::resource('departments', DepartmentController::class)->middleware('permission:academic.view');
        Route::resource('competencies', CompetencyController::class)->except(['show'])->middleware('permission:academic.view');
        Route::resource('classrooms', ClassroomController::class)->except(['show'])->middleware('permission:academic.view');
        Route::resource('subjects', SubjectController::class)->except(['show'])->middleware('permission:academic.view');

        // Tahap 5: Schedule, Attendance, Grade
        Route::resource('schedules', ScheduleController::class)->except(['show'])->middleware('permission:academic.view');
        Route::get('attendances/recap', [AttendanceController::class, 'recap'])->name('attendances.recap')->middleware('permission:attendance.view');
        Route::resource('attendances', AttendanceController::class)->except(['show', 'edit', 'update'])->middleware('permission:attendance.view');
        Route::resource('grades', GradeController::class)->except(['show'])->middleware('permission:grade.view');
    });
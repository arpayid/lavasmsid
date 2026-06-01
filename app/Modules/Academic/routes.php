<?php

use App\Modules\Academic\Controllers\AcademicEventController;
use App\Modules\Academic\Controllers\AcademicYearController;
use App\Modules\Academic\Controllers\AttendanceController;
use App\Modules\Academic\Controllers\ClassroomController;
use App\Modules\Academic\Controllers\CompetencyController;
use App\Modules\Academic\Controllers\DepartmentController;
use App\Modules\Academic\Controllers\GradeController;
use App\Modules\Academic\Controllers\ReportCardController;
use App\Modules\Academic\Controllers\ScheduleController;
use App\Modules\Academic\Controllers\SemesterController;
use App\Modules\Academic\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // === Academic Year ===
        Route::get('academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index')->middleware('permission:academic.view');
        Route::get('academic-years/create', [AcademicYearController::class, 'create'])->name('academic-years.create')->middleware('permission:academic.create');
        Route::post('academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store')->middleware('permission:academic.create');
        Route::get('academic-years/{academic_year}', [AcademicYearController::class, 'show'])->name('academic-years.show')->middleware('permission:academic.view');
        Route::get('academic-years/{academic_year}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit')->middleware('permission:academic.update');
        Route::put('academic-years/{academic_year}', [AcademicYearController::class, 'update'])->name('academic-years.update')->middleware('permission:academic.update');
        Route::delete('academic-years/{academic_year}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy')->middleware('permission:academic.delete');

        // === Semester ===
        Route::get('semesters', [SemesterController::class, 'index'])->name('semesters.index')->middleware('permission:academic.view');
        Route::get('semesters/create', [SemesterController::class, 'create'])->name('semesters.create')->middleware('permission:academic.create');
        Route::post('semesters', [SemesterController::class, 'store'])->name('semesters.store')->middleware('permission:academic.create');
        Route::get('semesters/{semester}/edit', [SemesterController::class, 'edit'])->name('semesters.edit')->middleware('permission:academic.update');
        Route::put('semesters/{semester}', [SemesterController::class, 'update'])->name('semesters.update')->middleware('permission:academic.update');
        Route::delete('semesters/{semester}', [SemesterController::class, 'destroy'])->name('semesters.destroy')->middleware('permission:academic.delete');

        // === Department ===
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:academic.view');
        Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:academic.create');
        Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:academic.create');
        Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:academic.view');
        Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:academic.update');
        Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:academic.update');
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:academic.delete');

        // === Competency ===
        Route::get('competencies', [CompetencyController::class, 'index'])->name('competencies.index')->middleware('permission:academic.view');
        Route::get('competencies/create', [CompetencyController::class, 'create'])->name('competencies.create')->middleware('permission:academic.create');
        Route::post('competencies', [CompetencyController::class, 'store'])->name('competencies.store')->middleware('permission:academic.create');
        Route::get('competencies/{competency}/edit', [CompetencyController::class, 'edit'])->name('competencies.edit')->middleware('permission:academic.update');
        Route::put('competencies/{competency}', [CompetencyController::class, 'update'])->name('competencies.update')->middleware('permission:academic.update');
        Route::delete('competencies/{competency}', [CompetencyController::class, 'destroy'])->name('competencies.destroy')->middleware('permission:academic.delete');

        // === Classroom ===
        Route::get('classrooms', [ClassroomController::class, 'index'])->name('classrooms.index')->middleware('permission:academic.view');
        Route::get('classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create')->middleware('permission:academic.create');
        Route::post('classrooms', [ClassroomController::class, 'store'])->name('classrooms.store')->middleware('permission:academic.create');
        Route::get('classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit')->middleware('permission:academic.update');
        Route::put('classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update')->middleware('permission:academic.update');
        Route::delete('classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy')->middleware('permission:academic.delete');

        // === Subject ===
        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index')->middleware('permission:academic.view');
        Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create')->middleware('permission:academic.create');
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store')->middleware('permission:academic.create');
        Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit')->middleware('permission:academic.update');
        Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update')->middleware('permission:academic.update');
        Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy')->middleware('permission:academic.delete');

        // === Schedule ===
        Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index')->middleware('permission:schedule.view');
        Route::get('schedules/create', [ScheduleController::class, 'create'])->name('schedules.create')->middleware('permission:schedule.create');
        Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store')->middleware('permission:schedule.create');
        Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit')->middleware('permission:schedule.update');
        Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update')->middleware('permission:schedule.update');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy')->middleware('permission:schedule.delete');

        // === Attendance ===
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:attendance.view');
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create')->middleware('permission:attendance.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store')->middleware('permission:attendance.create');
        Route::get('attendances/recap', [AttendanceController::class, 'recap'])->name('attendances.recap')->middleware('permission:attendance.view');
        Route::get('attendances/export', [AttendanceController::class, 'export'])->name('attendances.export')->middleware('permission:attendance.export');
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy')->middleware('permission:attendance.delete');

        // === Grade ===
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index')->middleware('permission:grade.view');
        Route::get('grades/create', [GradeController::class, 'create'])->name('grades.create')->middleware('permission:grade.create');
        Route::post('grades', [GradeController::class, 'store'])->name('grades.store')->middleware('permission:grade.create');
        Route::get('grades/bulk', [GradeController::class, 'bulk'])->name('grades.bulk')->middleware('permission:grade.create');
        Route::post('grades/bulk-store', [GradeController::class, 'bulkStore'])->name('grades.bulk-store')->middleware('permission:grade.create');
        Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit')->middleware('permission:grade.update');
        Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update')->middleware('permission:grade.update');
        Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy')->middleware('permission:grade.delete');

        // === Report Card ===
        Route::get('report-cards', [ReportCardController::class, 'index'])->name('report-cards.index')->middleware('permission:report.view');
        Route::get('report-cards/show', [ReportCardController::class, 'show'])->name('report-cards.show')->middleware('permission:report.view');
        Route::get('report-cards/pdf', [ReportCardController::class, 'pdf'])->name('report-cards.pdf')->middleware('permission:report.export');

        // === Academic Events ===
        Route::get('academic-events', [AcademicEventController::class, 'index'])->name('academic-events.index')->middleware('permission:academic.view');
        Route::get('academic-events/create', [AcademicEventController::class, 'create'])->name('academic-events.create')->middleware('permission:academic.create');
        Route::post('academic-events', [AcademicEventController::class, 'store'])->name('academic-events.store')->middleware('permission:academic.create');
        Route::get('academic-events/{academic_event}/edit', [AcademicEventController::class, 'edit'])->name('academic-events.edit')->middleware('permission:academic.update');
        Route::put('academic-events/{academic_event}', [AcademicEventController::class, 'update'])->name('academic-events.update')->middleware('permission:academic.update');
        Route::delete('academic-events/{academic_event}', [AcademicEventController::class, 'destroy'])->name('academic-events.destroy')->middleware('permission:academic.delete');
    });

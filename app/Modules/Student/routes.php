<?php

use App\Modules\Student\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('students', [StudentController::class, 'index'])->name('students.index')->middleware('permission:student.view');
        Route::get('students/create', [StudentController::class, 'create'])->name('students.create')->middleware('permission:student.create');
        Route::post('students', [StudentController::class, 'store'])->name('students.store')->middleware('permission:student.create');
        Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show')->middleware('permission:student.view');
        Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit')->middleware('permission:student.update');
        Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update')->middleware('permission:student.update');
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy')->middleware('permission:student.delete');
    });

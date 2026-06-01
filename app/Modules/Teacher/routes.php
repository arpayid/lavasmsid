<?php

use App\Modules\Teacher\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('teachers', [TeacherController::class, 'index'])->name('teachers.index')->middleware('permission:teacher.view');
        Route::get('teachers/create', [TeacherController::class, 'create'])->name('teachers.create')->middleware('permission:teacher.create');
        Route::post('teachers', [TeacherController::class, 'store'])->name('teachers.store')->middleware('permission:teacher.create');
        Route::get('teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show')->middleware('permission:teacher.view');
        Route::get('teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit')->middleware('permission:teacher.update');
        Route::put('teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update')->middleware('permission:teacher.update');
        Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy')->middleware('permission:teacher.delete');
    });

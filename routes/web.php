<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Website\Controllers\PublicHomeController;
use App\Modules\Dashboard\Controllers\DashboardController;
use App\Modules\Student\Controllers\StudentController;
use App\Modules\Teacher\Controllers\TeacherController;
use App\Modules\Academic\Controllers\DepartmentController;

Route::get('/', [PublicHomeController::class, 'index'])->name('public.home');
Route::view('/profil', 'public.profile')->name('public.profile');
Route::view('/ppdb', 'public.ppdb')->name('public.ppdb');
Route::view('/kontak', 'public.contact')->name('public.contact');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', StudentController::class)->middleware('permission:student.view');
    Route::resource('teachers', TeacherController::class)->middleware('permission:teacher.view');
    Route::resource('departments', DepartmentController::class)->middleware('permission:academic.view');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/me', fn () => request()->user());
Route::get('/school/summary', fn () => [
    'name' => 'SMK Management System Professional',
    'modules' => ['student', 'teacher', 'academic', 'attendance', 'grade', 'finance', 'ppdb', 'internship', 'bkk', 'report'],
]);

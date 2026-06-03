<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->get('/me', fn (Request $request) => $request->user());
Route::get('/school/summary', fn () => [
    'name' => 'SMK Management System Professional',
    'modules' => ['student', 'teacher', 'academic', 'attendance', 'grade', 'finance', 'ppdb', 'internship', 'bkk', 'report'],
]);

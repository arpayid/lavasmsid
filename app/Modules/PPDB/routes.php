<?php

use App\Modules\PPDB\Controllers\PPDBController;
use App\Modules\PPDB\Controllers\PublicPPDBController;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware(['auth'])
    ->prefix('admin/ppdb')
    ->name('admin.ppdb.')
    ->group(function () {
        Route::get('/', [PPDBController::class, 'index'])->name('index')->middleware('permission:ppdb.view');
        Route::get('/{ppdb}', [PPDBController::class, 'show'])->name('show')->middleware('permission:ppdb.view');
        Route::post('/{ppdb}/verify', [PPDBController::class, 'verify'])->name('verify')->middleware('permission:ppdb.verify');
        Route::post('/{ppdb}/accept', [PPDBController::class, 'accept'])->name('accept')->middleware('permission:ppdb.approve');
        Route::post('/{ppdb}/reject', [PPDBController::class, 'reject'])->name('reject')->middleware('permission:ppdb.approve');
        Route::post('/{ppdb}/convert', [PPDBController::class, 'convert'])->name('convert')->middleware('permission:ppdb.convert');
    });

// Public routes
Route::get('/ppdb/daftar', [PublicPPDBController::class, 'form'])->name('public.ppdb.form');
Route::post('/ppdb/daftar', [PublicPPDBController::class, 'submit'])->name('public.ppdb.submit');
Route::get('/ppdb/status', [PublicPPDBController::class, 'checkStatus'])->name('public.ppdb.check');
Route::get('/ppdb/status/{number}', [PublicPPDBController::class, 'status'])->name('public.ppdb.status');

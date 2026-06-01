<?php

use App\Modules\PPDB\Controllers\PPDBController;
use App\Modules\PPDB\Controllers\PublicPPDBController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/ppdb')
    ->name('admin.ppdb.')
    ->group(function () {
        Route::get('/', [PPDBController::class, 'index'])->name('index')->middleware('permission:ppdb.view');
        Route::get('/dashboard', [PPDBController::class, 'index'])->name('dashboard')->middleware('permission:ppdb.view');
        Route::get('/registrations', [PPDBController::class, 'index'])->name('registrations.index')->middleware('permission:ppdb.view');
        Route::get('/registrations/{ppdb}', [PPDBController::class, 'show'])->name('registrations.show')->middleware('permission:ppdb.view');
        Route::post('/registrations/{ppdb}/verify', [PPDBController::class, 'verify'])->name('registrations.verify')->middleware('permission:ppdb.verify');
        Route::post('/registrations/{ppdb}/accept', [PPDBController::class, 'accept'])->name('registrations.accept')->middleware('permission:ppdb.approve');
        Route::post('/registrations/{ppdb}/reject', [PPDBController::class, 'reject'])->name('registrations.reject')->middleware('permission:ppdb.approve');
        Route::post('/registrations/{ppdb}/convert', [PPDBController::class, 'convert'])->name('registrations.convert')->middleware('permission:ppdb.convert');
        Route::get('/{ppdb}', [PPDBController::class, 'show'])->name('show')->middleware('permission:ppdb.view');
        Route::post('/{ppdb}/verify', [PPDBController::class, 'verify'])->name('verify')->middleware('permission:ppdb.verify');
        Route::post('/{ppdb}/accept', [PPDBController::class, 'accept'])->name('accept')->middleware('permission:ppdb.approve');
        Route::post('/{ppdb}/reject', [PPDBController::class, 'reject'])->name('reject')->middleware('permission:ppdb.approve');
        Route::post('/{ppdb}/convert', [PPDBController::class, 'convert'])->name('convert')->middleware('permission:ppdb.convert');
    });

Route::get('/ppdb', [PublicPPDBController::class, 'form'])->name('ppdb.index');
Route::get('/ppdb/daftar', [PublicPPDBController::class, 'form'])->name('public.ppdb.form');
Route::post('/ppdb/daftar', [PublicPPDBController::class, 'submit'])->name('public.ppdb.submit');
Route::post('/ppdb', [PublicPPDBController::class, 'submit'])->name('ppdb.store');
Route::get('/ppdb/status', [PublicPPDBController::class, 'checkStatus'])->name('public.ppdb.check');
Route::get('/ppdb/status/{number}', [PublicPPDBController::class, 'status'])->name('public.ppdb.status');

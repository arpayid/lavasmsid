<?php

use App\Modules\IndustryPartner\Controllers\IndustryPartnerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/industry-partners')
    ->name('admin.industry-partners.')
    ->group(function () {
        Route::get('/', [IndustryPartnerController::class, 'index'])->name('index')->middleware('permission:industry.view');
        Route::get('/create', [IndustryPartnerController::class, 'create'])->name('create')->middleware('permission:industry.create');
        Route::post('/', [IndustryPartnerController::class, 'store'])->name('store')->middleware('permission:industry.create');
        Route::get('/{industryPartner}', [IndustryPartnerController::class, 'show'])->name('show')->middleware('permission:industry.view');
        Route::get('/{industryPartner}/edit', [IndustryPartnerController::class, 'edit'])->name('edit')->middleware('permission:industry.update');
        Route::put('/{industryPartner}', [IndustryPartnerController::class, 'update'])->name('update')->middleware('permission:industry.update');
        Route::delete('/{industryPartner}', [IndustryPartnerController::class, 'destroy'])->name('destroy')->middleware('permission:industry.delete');
    });

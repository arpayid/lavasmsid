<?php

use App\Modules\Finance\Controllers\InvoiceController;
use App\Modules\Finance\Controllers\PaymentCategoryController;
use App\Modules\Finance\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/finance')
    ->name('admin.finance.')
    ->group(function () {
        // Dashboard
        Route::get('/', [PaymentController::class, 'dashboard'])->name('dashboard')->middleware('permission:finance.view');

        // Categories
        Route::resource('categories', PaymentCategoryController::class)->except(['show'])->middleware('permission:finance.view');

        // Invoices
        Route::resource('invoices', InvoiceController::class)->middleware('permission:finance.view');

        // Payments
        Route::resource('payments', PaymentController::class)->except(['show', 'edit', 'update'])->middleware('permission:finance.view');
        Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify')->middleware('permission:finance.verify');
    });
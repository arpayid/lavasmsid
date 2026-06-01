<?php

use App\Modules\Finance\Controllers\FinanceController;
use App\Modules\Finance\Controllers\FinanceReportController;
use App\Modules\Finance\Controllers\InvoiceController;
use App\Modules\Finance\Controllers\PaymentCategoryController;
use App\Modules\Finance\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Authenticated admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));

    // Finance Routes - Protected by finance.view permission
    Route::middleware(['permission:finance.view'])->prefix('finance')->name('finance.')->group(function () {
        // Finance Dashboard
        Route::get('/', [FinanceController::class, 'dashboard'])->name('dashboard');

        // Payment Categories
        Route::resource('categories', PaymentCategoryController::class);

        // Invoices
        Route::resource('invoices', InvoiceController::class);

        // Payments
        Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::resource('payments', PaymentController::class)->except(['edit', 'update']);

        // Reports
        Route::get('reports', [FinanceReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [FinanceReportController::class, 'export'])->name('reports.export');
    });
});

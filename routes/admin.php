<?php

use Illuminate\Support\Facades\Route;

// Authenticated admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
});

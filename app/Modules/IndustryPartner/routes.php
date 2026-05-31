<?php

use App\Modules\IndustryPartner\Controllers\IndustryPartnerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/industry-partners')
    ->name('admin.industry-partners.')
    ->group(function () {
        Route::resource('/', IndustryPartnerController::class)->parameters(['' => 'industryPartner'])->except(['show'])->middleware('permission:industry.view');
    });

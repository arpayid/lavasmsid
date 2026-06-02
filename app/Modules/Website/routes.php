<?php

use App\Modules\Website\Controllers\Admin\CmsController;
use App\Modules\Website\Controllers\Admin\GalleryController;
use App\Modules\Website\Controllers\Admin\NewsController;
use App\Modules\Website\Controllers\Admin\WebsiteSettingController;
use App\Modules\Website\Controllers\PublicWebsiteController;
use App\Modules\Website\Controllers\WebsiteDashboardController;
use Illuminate\Support\Facades\Route;

// Admin CMS routes
Route::middleware(['auth'])
    ->prefix('admin/website')
    ->name('admin.website.')
    ->group(function () {
        Route::get('/', [WebsiteDashboardController::class, 'index'])->name('dashboard')->middleware('permission:website.view');

        // Settings
        Route::get('/settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit')->middleware('permission:website.view');
        Route::put('/settings', [WebsiteSettingController::class, 'update'])->name('settings.update')->middleware('permission:website.update');

        // News (Posts)
        Route::resource('news', NewsController::class)->except(['show'])->middleware([
            'index' => 'permission:website.view',
            'create' => 'permission:website.create',
            'store' => 'permission:website.create',
            'edit' => 'permission:website.update',
            'update' => 'permission:website.update',
            'destroy' => 'permission:website.delete',
        ]);

        // Static Pages
        Route::get('/pages', [CmsController::class, 'pagesIndex'])->name('pages.index')->middleware('permission:website.view');
        Route::get('/pages/{slug}/edit', [CmsController::class, 'pagesEdit'])->name('pages.edit')->middleware('permission:website.update');
        Route::put('/pages/{slug}', [CmsController::class, 'pagesUpdate'])->name('pages.update')->middleware('permission:website.update');

        // Events (Announcements/Agenda)
        Route::get('/events', [CmsController::class, 'eventsIndex'])->name('events.index')->middleware('permission:website.view');
        Route::get('/events/create', [CmsController::class, 'eventsCreate'])->name('events.create')->middleware('permission:website.create');
        Route::post('/events', [CmsController::class, 'eventsStore'])->name('events.store')->middleware('permission:website.create');
        Route::get('/events/{event}/edit', [CmsController::class, 'eventsEdit'])->name('events.edit')->middleware('permission:website.update');
        Route::put('/events/{event}', [CmsController::class, 'eventsUpdate'])->name('events.update')->middleware('permission:website.update');
        Route::delete('/events/{event}', [CmsController::class, 'eventsDestroy'])->name('events.destroy')->middleware('permission:website.delete');

        // Gallery
        Route::resource('gallery', GalleryController::class)->except(['show'])->middleware('permission:website.view');

        // Facilities
        Route::get('/facilities', [CmsController::class, 'facilitiesIndex'])->name('facilities.index')->middleware('permission:website.view');
        Route::post('/facilities', [CmsController::class, 'facilitiesStore'])->name('facilities.store')->middleware('permission:website.create');
        Route::delete('/facilities/{facility}', [CmsController::class, 'facilitiesDestroy'])->name('facilities.destroy')->middleware('permission:website.delete');

        // Achievements
        Route::resource('achievements', CmsController::class)->except(['show'])->names([
            'index' => 'achievements.index',
            'create' => 'achievements.create',
            'store' => 'achievements.store',
            'edit' => 'achievements.edit',
            'update' => 'achievements.update',
            'destroy' => 'achievements.destroy',
        ])->middleware('permission:website.view');
    });

// Public website routes
Route::get('/', [PublicWebsiteController::class, 'home'])->name('public.home');
Route::get('/profil', [PublicWebsiteController::class, 'profile'])->name('public.profile');
Route::get('/jurusan', [PublicWebsiteController::class, 'departments'])->name('public.departments');
Route::get('/berita', [PublicWebsiteController::class, 'news'])->name('public.news');
Route::get('/berita/{slug}', [PublicWebsiteController::class, 'newsShow'])->name('public.news.show');
Route::get('/agenda', [PublicWebsiteController::class, 'events'])->name('public.events');
Route::get('/galeri', [PublicWebsiteController::class, 'gallery'])->name('public.gallery');
Route::get('/prestasi', [PublicWebsiteController::class, 'achievements'])->name('public.achievements');
Route::get('/fasilitas', [PublicWebsiteController::class, 'facilities'])->name('public.facilities');
Route::get('/kontak', [PublicWebsiteController::class, 'contact'])->name('public.contact');
Route::get('/halaman/{slug}', [PublicWebsiteController::class, 'page'])->name('public.page');

// Smart PPDB Integration - Alias to ppdb.index for backward compatibility
Route::get('/ppdb', function () {
    if (Route::has('public.ppdb.form')) {
        return redirect()->route('public.ppdb.form');
    }
    if (Route::has('ppdb.index')) {
        // Prevent infinite loop if this route is ppdb.index
        $route = Route::getRoutes()->getByName('ppdb.index');
        if ($route && $route->getActionName() !== 'Closure') {
            return redirect()->route('ppdb.index');
        }
    }
    abort(404, 'Informasi PPDB belum tersedia.');
})->name('ppdb.index');

// Additional alias for Phase 8 consistency if needed
if (! Route::has('public.ppdb')) {
    Route::get('/ppdb-info', fn () => redirect()->route('ppdb.index'))->name('public.ppdb');
}

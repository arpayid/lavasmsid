<?php

use App\Modules\Website\Controllers\Admin\CmsController;
use App\Modules\Website\Controllers\Admin\NewsController;
use App\Modules\Website\Controllers\Admin\GalleryController;
use App\Modules\Website\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Route;

// Admin CMS routes
Route::middleware(['auth'])
    ->prefix('admin/website')
    ->name('admin.website.')
    ->group(function () {
        // News
        Route::resource('news', NewsController::class)->except(['show'])->middleware('permission:website.view');
        // Gallery
        Route::resource('gallery', GalleryController::class)->except(['show'])->middleware('permission:website.view');
        // Events
        Route::get('/events', [CmsController::class, 'eventsIndex'])->name('events.index')->middleware('permission:website.view');
        Route::get('/events/create', [CmsController::class, 'eventsCreate'])->name('events.create')->middleware('permission:website.create');
        Route::post('/events', [CmsController::class, 'eventsStore'])->name('events.store')->middleware('permission:website.create');
        Route::get('/events/{event}/edit', [CmsController::class, 'eventsEdit'])->name('events.edit')->middleware('permission:website.update');
        Route::put('/events/{event}', [CmsController::class, 'eventsUpdate'])->name('events.update')->middleware('permission:website.update');
        Route::delete('/events/{event}', [CmsController::class, 'eventsDestroy'])->name('events.destroy')->middleware('permission:website.delete');
        // Facilities
        Route::get('/facilities', [CmsController::class, 'facilitiesIndex'])->name('facilities.index')->middleware('permission:website.view');
        Route::post('/facilities', [CmsController::class, 'facilitiesStore'])->name('facilities.store')->middleware('permission:website.create');
        Route::delete('/facilities/{facility}', [CmsController::class, 'facilitiesDestroy'])->name('facilities.destroy')->middleware('permission:website.delete');
        // Achievements
        Route::resource('achievements', CmsController::class)->except([])->names([
            'index' => 'achievements.index', 'create' => 'achievements.create',
            'store' => 'achievements.store', 'edit' => 'achievements.edit',
            'update' => 'achievements.update', 'destroy' => 'achievements.destroy',
        ])->middleware('permission:website.view');
        // Pages
        Route::get('/pages', [CmsController::class, 'pagesIndex'])->name('pages.index')->middleware('permission:website.view');
        Route::get('/pages/{slug}/edit', [CmsController::class, 'pagesEdit'])->name('pages.edit')->middleware('permission:website.update');
        Route::put('/pages/{slug}', [CmsController::class, 'pagesUpdate'])->name('pages.update')->middleware('permission:website.update');
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
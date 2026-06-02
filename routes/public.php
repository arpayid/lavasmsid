<?php

use App\Modules\Website\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::name('public.')->group(function () {
    Route::get('/', [PublicWebsiteController::class, 'home'])->name('home');
    Route::get('/profil', [PublicWebsiteController::class, 'profile'])->name('profile');
    Route::get('/jurusan', [PublicWebsiteController::class, 'departments'])->name('departments');
    Route::get('/berita', [PublicWebsiteController::class, 'news'])->name('news');
    Route::get('/berita/{slug}', [PublicWebsiteController::class, 'newsShow'])->name('news.show');
    Route::get('/agenda', [PublicWebsiteController::class, 'events'])->name('events');
    Route::get('/galeri', [PublicWebsiteController::class, 'gallery'])->name('gallery');
    Route::get('/prestasi', [PublicWebsiteController::class, 'achievements'])->name('achievements');
    Route::get('/fasilitas', [PublicWebsiteController::class, 'facilities'])->name('facilities');
    Route::get('/kontak', [PublicWebsiteController::class, 'contact'])->name('contact');
    Route::get('/halaman/{slug}', [PublicWebsiteController::class, 'page'])->name('page');
});

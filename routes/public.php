<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Website\Controllers\PublicHomeController;

Route::get('/', [PublicHomeController::class, 'index'])->name('public.home');
Route::view('/profil', 'public.profile')->name('public.profile');
Route::redirect('/ppdb', '/ppdb/daftar')->name('public.ppdb');
Route::view('/kontak', 'public.contact')->name('public.contact');

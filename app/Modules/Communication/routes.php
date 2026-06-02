<?php

use App\Modules\Communication\Controllers\AnnouncementController;
use App\Modules\Communication\Controllers\CommunicationDashboardController;
use App\Modules\Communication\Controllers\MessageController;
use App\Modules\Communication\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/communication')
    ->name('admin.communication.')
    ->group(function () {
        Route::get('/', [CommunicationDashboardController::class, 'index'])->name('dashboard')->middleware('permission:communication.view');

        // Announcements
        Route::resource('announcements', AnnouncementController::class)->middleware([
            'index' => 'permission:communication.view',
            'create' => 'permission:communication.create',
            'store' => 'permission:communication.create',
            'show' => 'permission:communication.view',
            'edit' => 'permission:communication.update',
            'update' => 'permission:communication.update',
            'destroy' => 'permission:communication.delete',
        ]);

        // Messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/inbox', [MessageController::class, 'inbox'])->name('inbox')->middleware('permission:communication.view');
            Route::get('/outbox', [MessageController::class, 'outbox'])->name('outbox')->middleware('permission:communication.view');
            Route::get('/create', [MessageController::class, 'create'])->name('create')->middleware('permission:communication.create');
            Route::post('/', [MessageController::class, 'store'])->name('store')->middleware('permission:communication.create');
            Route::get('/{message}', [MessageController::class, 'show'])->name('show')->middleware('permission:communication.view');
            Route::post('/{message}/mark-read', [MessageController::class, 'markRead'])->name('mark-read')->middleware('permission:communication.update');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy')->middleware('permission:communication.delete');
        });

        // Notification Center
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index')->middleware('permission:communication.view');
            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read')->middleware('permission:communication.update');
            Route::get('/{notification}', [NotificationController::class, 'show'])->name('show')->middleware('permission:communication.view');
            Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('mark-read')->middleware('permission:communication.update');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy')->middleware('permission:communication.delete');
        });
    });

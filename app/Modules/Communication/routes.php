<?php

use App\Modules\Communication\Controllers\AnnouncementController;
use App\Modules\Communication\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/communication')
    ->name('admin.communication.')
    ->group(function () {
        // Announcements
        Route::resource('announcements', AnnouncementController::class)->except(['show'])->middleware('permission:communication.view');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:communication.view');
        Route::get('/notifications/unread', [NotificationController::class, 'unreadCount'])->name('notifications.unread');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAsRead'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markSingle'])->name('notifications.read');
    });

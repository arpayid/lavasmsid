<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communication\Models\Announcement;
use App\Modules\Communication\Models\Message;
use App\Modules\Communication\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CommunicationDashboardController extends Controller
{
    /**
     * Display the communication dashboard.
     */
    public function index(Request $request): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        $stats = [
            'total_announcements' => 0,
            'published_announcements' => 0,
            'draft_announcements' => 0,
            'total_notifications' => 0,
            'unread_notifications' => 0,
            'total_messages' => 0,
            'unread_messages' => 0,
        ];

        if (Schema::hasTable('announcements')) {
            $stats['total_announcements'] = Announcement::count();
            $stats['published_announcements'] = Announcement::where('is_published', true)->count();
            $stats['draft_announcements'] = Announcement::where('is_published', false)->count();
        }

        if (Schema::hasTable('notifications')) {
            $stats['total_notifications'] = Notification::count();
            $stats['unread_notifications'] = Notification::where('is_read', false)->count();
        }

        if (Schema::hasTable('messages')) {
            $stats['total_messages'] = Message::count();
            $stats['unread_messages'] = Message::where('is_read', false)->count();
        }

        $recentAnnouncements = [];
        if (Schema::hasTable('announcements')) {
            $recentAnnouncements = Announcement::with('creator')
                ->latest()
                ->take(5)
                ->get();
        }

        $recentNotifications = [];
        if (Schema::hasTable('notifications')) {
            $recentNotifications = Notification::latest()
                ->take(5)
                ->get();
        }

        return view('modules.communication.dashboard', compact('stats', 'recentAnnouncements', 'recentNotifications'));
    }
}

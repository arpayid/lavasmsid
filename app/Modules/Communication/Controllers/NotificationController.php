<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communication\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('communication.view')) abort(403);

        $query = Notification::where('user_id', auth()->id())->orderByDesc('created_at');
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $notifications = $query->paginate(20);

        return view('modules.communication.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification): View
    {
        if (Gate::denies('communication.view')) abort(403);

        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('modules.communication.notifications.show', compact('notification'));
    }

    public function markRead(Notification $notification): RedirectResponse
    {
        if (Gate::denies('communication.update')) abort(403);

        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai terbaca.');
    }

    public function markAllRead(): RedirectResponse
    {
        if (Gate::denies('communication.update')) abort(403);

        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai terbaca.');
    }

    public function destroy(Notification $notification): RedirectResponse
    {
        if (Gate::denies('communication.delete')) abort(403);

        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->route('admin.communication.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function unreadCount(): int
    {
        return Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }
}

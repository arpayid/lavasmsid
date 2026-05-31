<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communication\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Notification::where('user_id', auth()->id())->orderByDesc('created_at');
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $notifications = $query->paginate(20);

        return view('modules.communication.notifications.index', compact('notifications'));
    }

    public function unreadCount(): JsonResponse
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markSingle(int $id): JsonResponse
    {
        $notification = Notification::where('user_id', auth()->id())->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}

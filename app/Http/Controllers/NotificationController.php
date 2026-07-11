<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get JSON list of unread notifications & count for top navbar dropdown.
     */
    public function getUnreadJson()
    {
        $userId = auth()->id();

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'message' => $notif->message,
                    'related_id' => $notif->related_id,
                    'read' => !is_null($notif->read_at),
                    'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : '-',
                ];
            });

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as read (AJAX POST).
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['read_at' => Carbon::now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all unread notifications of logged-in user as read (AJAX POST).
     */
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        return response()->json(['success' => true]);
    }

    /**
     * View all notifications for the logged-in user.
     */
    public function index()
    {
        $userId = auth()->id();
        $userRole = auth()->user()->roleId;

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Determine parent layout view based on role (main.layout.app or users.layout.app)
        $layout = in_array($userRole, [1, 2, 3]) ? 'main.layout.app' : 'users.layout.app';

        return view('notifications.index', compact('notifications', 'layout'));
    }
}

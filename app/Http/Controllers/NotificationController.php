<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        $count = auth()->user()->notifications()->unread()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get unread notifications (for dropdown/bell icon).
     */
    public function unread()
    {
        $notifications = auth()->user()->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('view', $notification);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->unread()
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete all read notifications.
     */
    public function clearRead()
    {
        auth()->user()->notifications()
            ->where('read', true)
            ->delete();

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for JS polling.
     */
    public function fetchUnread(Request $request)
    {
        $sinceId = $request->query('since_id', 0);

        $notifications = $request->user()->notifications()
            ->unread()
            ->when($sinceId > 0, function ($query) use ($sinceId) {
                return $query->where('id', '>', $sinceId);
            })
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all notifications of the authenticated user as read.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()->unread()->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Mark a single notification of the authenticated user as read.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $notification->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Delete all notifications of the authenticated user.
     */
    public function deleteAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dihapus.',
        ]);
    }
}

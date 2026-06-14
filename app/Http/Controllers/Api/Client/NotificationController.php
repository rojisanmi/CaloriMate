<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $username = $request->user()->username;
        $notifications = Notification::where('username', $username)
            ->orderBy('notify_at', 'desc')
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id_notification,
                    'type' => $notif->type ?? 'info',
                    'title' => $notif->title ?? 'Notifikasi',
                    'message' => $notif->message,
                    'time' => $notif->notify_at ? Carbon::createFromFormat('Y-m-d H:i:s', $notif->notify_at->format('Y-m-d H:i:s'), 'Asia/Jakarta')->diffForHumans(Carbon::now('Asia/Jakarta')) : 'Baru saja',
                    'is_read' => $notif->is_read,
                    'icon' => $notif->icon ?? 'info',
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->where('is_read', false)->count(),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $username = $request->user()->username;

        if ($request->has('id')) {
            Notification::where('id_notification', $request->id)
                ->where('username', $username)
                ->update(['is_read' => true]);
        } else {
            Notification::where('username', $username)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get only unread notifications for the authenticated client.
     */
    public function unread(Request $request)
    {
        $username = $request->user()->username;

        $notifications = Notification::where('username', $username)
            ->where('is_read', false)
            ->orderBy('notify_at', 'desc')
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id_notification,
                    'type' => $notif->type ?? 'info',
                    'title' => $notif->title ?? 'Notifikasi',
                    'message' => $notif->message,
                    'time' => $notif->notify_at ? Carbon::createFromFormat('Y-m-d H:i:s', $notif->notify_at->format('Y-m-d H:i:s'), 'Asia/Jakarta')->diffForHumans(Carbon::now('Asia/Jakarta')) : 'Baru saja',
                    'is_read' => $notif->is_read,
                    'icon' => $notif->icon ?? 'info',
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }
}


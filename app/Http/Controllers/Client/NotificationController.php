<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $username = session('user_id');
        $notifications = Notification::where('username', $username)
            ->orderBy('notify_at', 'desc')
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id_notification,
                    'type' => $notif->type ?? 'info',
                    'title' => $notif->title ?? 'Notifikasi',
                    'message' => $notif->message,
                    'time' => $notif->notify_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notif->notify_at->format('Y-m-d H:i:s'), 'Asia/Jakarta')->diffForHumans(\Carbon\Carbon::now('Asia/Jakarta')) : 'Baru saja',
                    'is_read' => $notif->is_read,
                    'icon' => $notif->icon ?? 'info',
                ];
            });

        return view('notifications-client', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $username = session('user_id');
        if ($request->has('id')) {
            Notification::where('id_notification', $request->id)
                ->where('username', $username)
                ->update(['is_read' => true]);
        } else {
            Notification::where('username', $username)
                ->update(['is_read' => true]);
        }
        
        return back()->with('ok', 'Notifikasi ditandai sudah dibaca.');
    }
}

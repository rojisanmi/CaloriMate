<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public function __construct(private Messaging $messaging)
    {
    }

    /**
     * Kirim notifikasi push ke semua device milik seorang client.
     * Token yang sudah tidak valid otomatis dihapus dari database.
     *
     * @param array<string,string> $data payload data tambahan (opsional)
     * @return int jumlah token yang berhasil dikirimi
     */
    public function sendToUser(string $username, string $title, string $body, array $data = []): int
    {
        $tokens = DeviceToken::where('username', $username)->pluck('token')->all();

        if (empty($tokens)) {
            return 0;
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data)
            ->withAndroidConfig([
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'calorimate_reminders_v3',
                    'sound' => 'cm',
                ],
            ]);

        try {
            $report = $this->messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::warning('FCM send failed for ' . $username . ': ' . $e->getMessage());
            return 0;
        }

        // Bersihkan token yang tidak valid / sudah tidak terdaftar
        $invalid = array_merge(
            $report->invalidTokens(),
            $report->unknownTokens()
        );
        if (!empty($invalid)) {
            DeviceToken::whereIn('token', $invalid)->delete();
        }

        return $report->successes()->count();
    }
}

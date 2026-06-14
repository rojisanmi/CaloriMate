<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    /**
     * Daftarkan / perbarui FCM token milik perangkat client.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'token'    => 'required|string|max:512',
            'platform' => 'nullable|string|max:50',
        ]);

        $username = $request->user()->username;

        // updateOrCreate berdasarkan token agar tidak ada duplikat.
        // Token yang sama bisa berpindah pemilik (mis. ganti akun di HP yang sama).
        DeviceToken::updateOrCreate(
            ['token' => $data['token']],
            [
                'username' => $username,
                'platform' => $data['platform'] ?? 'android',
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Hapus token (mis. saat logout).
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string|max:512',
        ]);

        DeviceToken::where('token', $data['token'])
            ->where('username', $request->user()->username)
            ->delete();

        return response()->json(['success' => true]);
    }
}

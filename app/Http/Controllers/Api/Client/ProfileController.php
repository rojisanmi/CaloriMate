<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display client profile
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('client');

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update client profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'bb'     => 'required|numeric|min:40|max:500',
            'tb'     => 'required|numeric|min:100|max:300',
            'umur'   => 'required|integer|min:17|max:120',
        ],[
            'bb.min' => 'Berat Badan minimal adalah 40 kg.',
            'tb.min' => 'Tinggi Badan minimal adalah 100 cm.',
            'umur.min' => 'Umur minimal adalah 17 tahun.',
        ]);

        if ($request->hasFile('avatar')) {
            // we'll handle avatar uploading
            $validated['avatar'] = $request->file('avatar')->store('avatar-client', 'public');
        }

        $user->client()->updateOrCreate(
            ['username' => $user->username],
            [
                'bb'     => $validated['bb'],
                'tb'     => $validated['tb'],
                'umur'   => $validated['umur'],
                'gender' => $request->gender ?? $user->client->gender ?? null,
                'avatar' => $validated['avatar'] ?? $user->client->avatar ?? null,
            ]
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user->load('client')
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'bb'                   => 'sometimes|numeric|min:40|max:500',
            'tb'                   => 'sometimes|numeric|min:100|max:300',
            'umur'                 => 'sometimes|integer|min:17|max:120',
            'gender'               => 'nullable|in:L,P',
            'food_reminder_time'   => 'nullable|date_format:H:i',
            'exercise_reminder_time' => 'nullable|date_format:H:i',
            'photo'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'avatar'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'bb.min'   => 'Berat Badan minimal adalah 40 kg.',
            'tb.min'   => 'Tinggi Badan minimal adalah 100 cm.',
            'umur.min' => 'Umur minimal adalah 17 tahun.',
        ]);

        $clientPayload = [];

        if (isset($validated['bb'])) {
            $clientPayload['bb'] = $validated['bb'];
        }
        if (isset($validated['tb'])) {
            $clientPayload['tb'] = $validated['tb'];
        }
        if (isset($validated['umur'])) {
            $clientPayload['umur'] = $validated['umur'];
        }
        if ($request->has('gender')) {
            $clientPayload['gender'] = $request->gender;
        }
        if ($request->has('food_reminder_time')) {
            $clientPayload['food_reminder_time'] = $validated['food_reminder_time'] ?: null;
        }
        if ($request->has('exercise_reminder_time')) {
            $clientPayload['exercise_reminder_time'] = $validated['exercise_reminder_time'] ?: null;
        }

        $photoFile = $request->file('photo') ?? $request->file('avatar');
        if ($photoFile) {
            if ($user->client && $user->client->photo_path) {
                Storage::disk('public')->delete($user->client->photo_path);
            }
            $clientPayload['photo_path'] = $photoFile->store('avatars', 'public');
        }

        if (!empty($clientPayload)) {
            $user->client()->updateOrCreate(
                ['username' => $user->username],
                $clientPayload
            );
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()->load('client'),
        ]);
    }
}

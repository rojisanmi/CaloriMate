<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('trainer');

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'keahlian'    => 'required|string|max:255',
            'sertifikasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('sertifikasi')) {
            $validated['sertifikasi'] = $request->file('sertifikasi')->store('sertifikasi', 'public');
        } else {
            // Keep the old sertifikasi if it's not present but was provided previously.
            unset($validated['sertifikasi']);
        }

        $photoFile = $request->file('photo') ?? $request->file('avatar');
        if ($photoFile) {
            if ($user->trainer && $user->trainer->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->trainer->photo_path);
            }
            $validated['photo_path'] = $photoFile->store('avatars', 'public');
        }
        unset($validated['photo'], $validated['avatar']);

        $user->trainer()->updateOrCreate(
            ['username' => $user->username],
            $validated
        );

        return response()->json([
            'message' => 'Trainer profile updated successfully',
            'data' => $user->load('trainer')
        ]);
    }
}

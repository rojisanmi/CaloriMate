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
        ]);

        if ($request->hasFile('sertifikasi')) {
            $validated['sertifikasi'] = $request->file('sertifikasi')->store('sertifikasi', 'public');
        } else {
            // Keep the old sertifikasi if it's not present but was provided previously.
            unset($validated['sertifikasi']);
        }

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

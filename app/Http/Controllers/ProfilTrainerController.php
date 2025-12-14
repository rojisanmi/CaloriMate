<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ProfilTrainerController extends Controller
{
    private function authTrainer()
    {
        $username = Session::get('user_id');
        abort_if(!$username, 403);

        $user = User::with('trainer')
            ->where('username', $username)
            ->firstOrFail();

        abort_if(!$user->isTrainer(), 403);

        return $user;
    }

    public function showTrainer()
    {
        $user = $this->authTrainer();
        return view('profile.trainer', compact('user'));
    }

    public function updateTrainer(Request $request)
    {
        $user = $this->authTrainer();

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'keahlian'    => 'required|string|max:255',
            'sertifikasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // upload sertifikasi jika ada
        if ($request->hasFile('sertifikasi')) {
            $validated['sertifikasi'] =
                $request->file('sertifikasi')->store('sertifikasi', 'public');
        }

        $user->trainer()->updateOrCreate(
            ['username' => $user->username],
            $validated
        );

        return back()->with('ok', 'Profil trainer berhasil disimpan');
    }
}

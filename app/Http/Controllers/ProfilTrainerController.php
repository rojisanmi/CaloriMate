<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
            'email'       => 'required|email|max:255|unique:user,email,' . $user->username . ',username',
            'nama'        => 'required|string|max:100',
            'keahlian'    => 'required|string|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sertifikasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan oleh akun lain.',
            'nama.required'     => 'Nama wajib diisi.',
            'keahlian.required' => 'Keahlian wajib diisi.',
            'photo.image'       => 'File foto harus berupa gambar.',
            'photo.mimes'       => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'         => 'Ukuran foto maksimal 2 MB.',
            'sertifikasi.mimes' => 'Format sertifikasi harus jpg, jpeg, png, atau pdf.',
            'sertifikasi.max'   => 'Ukuran sertifikasi maksimal 2 MB.',
        ]);

        // Update email di tabel user
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->save();
        }

        $payload = [
            'nama'     => $validated['nama'],
            'keahlian' => $validated['keahlian'],
        ];

        // Handle photo upload — replace old
        if ($request->hasFile('photo')) {
            if ($user->trainer && $user->trainer->photo_path) {
                Storage::disk('public')->delete($user->trainer->photo_path);
            }
            $payload['photo_path'] = $request->file('photo')->store('avatars', 'public');
        }

        // Handle sertifikasi upload
        if ($request->hasFile('sertifikasi')) {
            $payload['sertifikasi'] = $request->file('sertifikasi')->store('sertifikasi', 'public');
        }

        $user->trainer()->updateOrCreate(
            ['username' => $user->username],
            $payload
        );

        return back()->with('ok', 'Profil berhasil disimpan');
    }
}

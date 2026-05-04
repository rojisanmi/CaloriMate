<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Autentikasi dan mendapatkan user client dari session
    private function authClient()
    {
        $username = Session::get('user_id');
        abort_if(!$username, 403);

        $user = User::with('client')
            ->where('username', $username)
            ->firstOrFail();

        abort_if(!$user->isClient(), 403);

        return $user;
    }

    // Tampilan profil client
    public function show()
    {
        $user = $this->authClient();
        return view('profile.client', compact('user'));
    }

    // Memperbarui profil client
    public function update(Request $request)
    {
        $user = $this->authClient();

        $validated = $request->validate([
            'email'          => 'required|email|max:255|unique:user,email,' . $user->username . ',username',
            'bb'             => 'required|numeric|min:40|max:500',
            'tb'             => 'required|numeric|min:100|max:300',
            'umur'           => 'required|integer|min:17|max:120',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'calorie_target' => 'nullable|numeric|min:500|max:10000',
            'protein_ratio'  => 'required|integer|min:10|max:70',
            'carbo_ratio'    => 'required|integer|min:10|max:70',
            'fat_ratio'      => 'required|integer|min:10|max:70',
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan oleh akun lain.',
            'bb.required'        => 'Berat badan wajib diisi.',
            'bb.numeric'         => 'Berat badan harus berupa angka.',
            'bb.min'             => 'Berat badan minimal 40 kg.',
            'bb.max'             => 'Berat badan maksimal 500 kg.',
            'tb.required'        => 'Tinggi badan wajib diisi.',
            'tb.numeric'         => 'Tinggi badan harus berupa angka.',
            'tb.min'             => 'Tinggi badan minimal 100 cm.',
            'tb.max'             => 'Tinggi badan maksimal 300 cm.',
            'umur.required'      => 'Umur wajib diisi.',
            'umur.integer'       => 'Umur harus berupa angka bulat.',
            'umur.min'           => 'Umur minimal 17 tahun.',
            'umur.max'           => 'Umur maksimal 120 tahun.',
            'photo.image'        => 'File harus berupa gambar.',
            'photo.mimes'        => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'          => 'Ukuran foto maksimal 2 MB.',
            'calorie_target.min' => 'Target kalori minimal 500 kkal.',
            'calorie_target.max' => 'Target kalori maksimal 10.000 kkal.',
            'protein_ratio.min'  => 'Rasio protein minimal 10%.',
            'protein_ratio.max'  => 'Rasio protein maksimal 70%.',
            'carbo_ratio.min'    => 'Rasio karbo minimal 10%.',
            'carbo_ratio.max'    => 'Rasio karbo maksimal 70%.',
            'fat_ratio.min'      => 'Rasio lemak minimal 10%.',
            'fat_ratio.max'      => 'Rasio lemak maksimal 70%.',
        ]);

        $total = $validated['protein_ratio'] + $validated['carbo_ratio'] + $validated['fat_ratio'];
        if ($total !== 100) {
            return back()
                ->withErrors(['macro' => "Total rasio makro harus 100% (sekarang {$total}%)."])
                ->withInput();
        }

        // Update email (di tabel user)
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->save();
        }

        // Build payload untuk client
        $clientPayload = [
            'bb'             => $validated['bb'],
            'tb'             => $validated['tb'],
            'umur'           => $validated['umur'],
            'gender'         => $request->gender ?? $user->client->gender ?? null,
            'calorie_target' => $validated['calorie_target'] ?: null,
            'protein_ratio'  => $validated['protein_ratio'],
            'carbo_ratio'    => $validated['carbo_ratio'],
            'fat_ratio'      => $validated['fat_ratio'],
        ];

        // Handle photo upload — replace old photo if exists
        if ($request->hasFile('photo')) {
            if ($user->client && $user->client->photo_path) {
                Storage::disk('public')->delete($user->client->photo_path);
            }
            $clientPayload['photo_path'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->client()->updateOrCreate(
            ['username' => $user->username],
            $clientPayload
        );

        return back()->with('ok', 'Profil berhasil disimpan');
    }
}

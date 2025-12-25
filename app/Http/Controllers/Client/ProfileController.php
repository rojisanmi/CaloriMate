<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
            'bb'     => 'required|numeric|min:40|max:500',
            'tb'     => 'required|numeric|min:100|max:300',
            'umur'   => 'required|integer|min:17|max:120',
        ],[
            'bb.min' => 'Berat Badan minimal adalah 40 kg.',
            'tb.min' => 'Tinggi Badan minimal adalah 100 cm.',
            'umur.min' => 'Umur minimal adalah 17 tahun.',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] =
                $request->file('avatar')->store('avatar-client', 'public');
        }

        $user->client()->updateOrCreate(
            ['username' => $user->username],
            [
                'bb'     => $validated['bb'],
                'tb'     => $validated['tb'],
                'umur'  => $validated['umur'],
                'gender' => $request->gender ?? $user->client->gender ?? null,
            ]
        );
        

        return back()->with('ok', 'Profil client berhasil disimpan');
    }
}

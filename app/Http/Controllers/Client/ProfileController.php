<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ProfileController extends Controller
{
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

    public function show()
    {
        $user = $this->authClient();
        return view('profile.client', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $this->authClient();

        $validated = $request->validate([
            'bb'     => 'required|numeric|min:1',
            'tb'     => 'required|numeric|min:1',
            'umur'   => 'required|integer|min:1',
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

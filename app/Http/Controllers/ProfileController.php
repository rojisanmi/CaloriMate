<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $username = Session::get('user_id');
        if (!$username) {
            return redirect()->route('login.form'); // or wherever your login is
        }

        $user = User::with(['client', 'trainer'])->where('username', $username)->firstOrFail();

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $username = Session::get('user_id');
        if (!$username) {
            return redirect()->route('login.form');
        }

        $user = User::with(['client', 'trainer'])->where('username', $username)->firstOrFail();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $username = Session::get('user_id');
        if (!$username) {
            return redirect()->route('login.form');
        }

        $user = User::with(['client', 'trainer'])->where('username', $username)->firstOrFail();

        // Validate based on role
        if ($user->isClient()) {
            $validator = Validator::make($request->all(), [
                'tb' => 'required|numeric|min:50|max:250',
                'bb' => 'required|numeric|min:10|max:300',
                'gender' => 'required|in:L,P',
                'umur' => 'required|integer|min:10|max:100',
            ], [
                'tb.required' => 'Tinggi badan wajib diisi',
                'bb.required' => 'Berat badan wajib diisi',
                // add more custom messages if needed
            ]);
        } elseif ($user->isTrainer()) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:100',
                'keahlian' => 'required|string|max:255',
                'sertifikasi' => 'nullable|string|max:500',
            ]);
        } else {
            return back()->withErrors(['error' => 'Role tidak dikenali.']);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Save based on role
        if ($user->isClient()) {
            $clientData = $request->only(['tb', 'bb', 'gender', 'umur']);
            $user->client()->updateOrCreate(['username' => $username], $clientData);
        } elseif ($user->isTrainer()) {
            $trainerData = $request->only(['nama', 'keahlian', 'sertifikasi']);
            $user->trainer()->updateOrCreate(['username' => $username], $trainerData);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
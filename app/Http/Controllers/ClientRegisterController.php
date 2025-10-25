<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientRegisterController extends Controller
{
    public function show(Request $request)
    {
        $step1 = Session::get('reg.step1');
        if (!$step1) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Selesaikan langkah pertama dulu.']);
        }

        $username = $step1['username'];

        // Jika ternyata client sudah ada (user lama), arahkan
        if (Client::where('username', $username)->exists()) {
            Session::forget('reg.step1');
            return redirect()->route('user.home');
        }

        return view('register_client', ['username' => $username]);
    }

    public function store(Request $request)
    {   
        
        $step1 = Session::get('reg.step1');
        if (!$step1) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Sesi kedaluwarsa. Silakan daftar lagi.']);
        }

        $data = $request->validate([
            'tinggi_badan' => 'required|numeric|min:50|max:300',
            'berat_badan'  => 'required|numeric|min:10|max:500',
            'gender'       => 'required|in:L,P',
            'umur'         => 'required|integer|min:5|max:120',
        ]);


        DB::transaction(function () use ($step1, $data) {
            // 1) buat user (role selalu 1 = client)
            $user = User::create([
                'username' => $step1['username'],
                'email'    => $step1['email'],
                'password' => $step1['password'], // sudah di-hash
                'role'     => 1,
            ]);

            // 2) buat client (schema kamu: PK username)
            Client::create([
                'username' => $step1['username'],
                'tb'       => round($data['tinggi_badan'], 2),
                'bb'       => round($data['berat_badan'], 2),
                'gender'   => $data['gender'],
                'umur'     => (int) $data['umur'],
                // jika punya user_id fk: 'user_id' => $user->id,
            ]);
        });

        Session::forget('reg.step1');

        return redirect()->route('home')->with('status', 'Registrasi selesai. Silakan login.');

    }
}
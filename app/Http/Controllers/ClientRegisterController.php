<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientRegisterController extends Controller
{
    // Tampilan halaman registrasi client
    public function show(Request $request)
    {
        // Langkah pertama harus sudah diisi
        $step1 = Session::get('reg.step1');
        if (!$step1) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Selesaikan langkah pertama dulu.']);
        }

        $username = $step1['username'];
        if (Client::where('username', $username)->exists()) {
            Session::forget('reg.step1');
            return redirect()->route('user.home');
        }

        return view('register_client', ['username' => $username]);
    }

    // Proses penyimpanan data registrasi client
    public function store(Request $request)
    {   
        // Langkah pertama harus sudah diisi
        $step1 = Session::get('reg.step1');
        if (!$step1) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Sesi kedaluwarsa. Silakan daftar lagi.']);
        }
        // 2. Validasi input (VALIDASI SELURUH INPUT)
        $data = $request->validate([
            'tinggi_badan' => 'required|numeric|min:100|max:300',
            'berat_badan'  => 'required|numeric|min:40|max:500',
            'gender'       => 'required|in:L,P',
            'umur'         => 'required|integer|min:17|max:120',
        ], [
            'tinggi_badan.min' => 'Tinggi Badan minimal 100 cm.',
            'berat_badan.min' => 'Berat Badan minimal 40 kg.',
            'umur.min' => 'Umur minimal 17 tahun.',
        ]);

        // 3. Simpan ke database (user + client) dalam transaksi
        DB::transaction(function () use ($step1, $data) {
            $user = User::create([
                'username' => $step1['username'],
                'email'    => $step1['email'],
                'password' => $step1['password'], 
                'role'     => 1,
            ]);

            Client::create([
                'username' => $step1['username'],
                'tb'       => round($data['tinggi_badan'], 2),
                'bb'       => round($data['berat_badan'], 2),
                'gender'   => $data['gender'],
                'umur'     => (int) $data['umur'],
            ]);
        });

        Session::forget('reg.step1');

        return redirect()->route('home')->with('status', 'Registrasi selesai. Silahkan login.');

    }
}
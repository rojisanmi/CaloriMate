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

        // Validasi input
        $data = $request->validate([
            'tinggi_badan' => 'required|numeric|min:100|max:300',
            'berat_badan'  => 'required|numeric|min:40|max:500',
            'gender'       => 'required|in:L,P',
            'umur'         => 'required|integer|min:17|max:120',
            'photo'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.numeric'  => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min'      => 'Tinggi badan minimal 100 cm.',
            'tinggi_badan.max'      => 'Tinggi badan maksimal 300 cm.',
            'berat_badan.required'  => 'Berat badan wajib diisi.',
            'berat_badan.numeric'   => 'Berat badan harus berupa angka.',
            'berat_badan.min'       => 'Berat badan minimal 40 kg.',
            'berat_badan.max'       => 'Berat badan maksimal 500 kg.',
            'gender.required'       => 'Jenis kelamin wajib dipilih.',
            'gender.in'             => 'Pilihan jenis kelamin tidak valid.',
            'umur.required'         => 'Umur wajib diisi.',
            'umur.integer'          => 'Umur harus berupa angka bulat.',
            'umur.min'              => 'Umur minimal 17 tahun.',
            'umur.max'              => 'Umur maksimal 120 tahun.',
            'photo.required'        => 'Foto profil wajib diunggah.',
            'photo.image'           => 'File harus berupa gambar.',
            'photo.mimes'           => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'             => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Upload foto sebelum transaksi DB
        $photoPath = $request->file('photo')->store('avatars', 'public');

        // Simpan ke database (user + client) dalam transaksi
        DB::transaction(function () use ($step1, $data, $photoPath) {
            User::create([
                'username' => $step1['username'],
                'email'    => $step1['email'],
                'password' => $step1['password'],
                'role'     => 1,
            ]);

            Client::create([
                'username'   => $step1['username'],
                'tb'         => round($data['tinggi_badan'], 2),
                'bb'         => round($data['berat_badan'], 2),
                'gender'     => $data['gender'],
                'umur'       => (int) $data['umur'],
                'photo_path' => $photoPath,
            ]);
        });

        Session::forget('reg.step1');

        // Auto-login: set session lalu redirect ke dashboard client
        $request->session()->regenerate();
        $request->session()->put('user_id',   $step1['username']);
        $request->session()->put('user_role', 1);
        $request->session()->put('user_name', $step1['username']);

        return redirect()->route('client.home')
            ->with('ok', 'Registrasi selesai. Selamat datang di CaloriMate!');
    }
}

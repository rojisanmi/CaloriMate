<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Tampilan halaman login
    public function showLogin()
    {
       
        return view('login');
    }
    // Tampilan halaman login user
    public function showLoginClient()
    {
        return view('login_client');
    }
    // Tampilan halaman login trainer
    public function showLoginTrainer()
    {
        return view('login_trainer');
    }

    // Tampilan halaman login admin
    public function showLoginAdmin()
    {
        return view('login_admin');
    }
    // proses login client dan trainer
    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:20',
            'password' => 'required|string',
            'role'     => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Admin (role 0) boleh login dari halaman manapun
        $user = User::where('username', $request->username)
            ->where(function ($query) use ($request) {
                $query->where('role', $request->role)
                      ->orWhere('role', 0); // admin selalu diizinkan
            })
            ->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        $valid = false;

        try {
            // NORMAL CASE (bcrypt / argon)
            $valid = Hash::check($request->password, $user->password);
        } catch (\Exception $e) {
            // LEGACY CASE (plain text)
            if ($request->password === $user->password) {
                $user->password = Hash::make($request->password);
                $user->save();
                $valid = true;
            }
        }

        if (!$valid) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        // Regenerate session
        $request->session()->regenerate();

        $request->session()->put('user_id', $user->username);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->username);


        return match((int) $user->role) {
            0 => redirect()->route('admin.home'),
            2 => redirect()->route('trainer.home'),
            default => redirect()->route('client.home'),
        };
    }

    // proses logout
    public function logout(Request $request)
    {
        // Proper logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('status', 'Logged out.');
    }

    // Tampilan halaman pilihan register (trainer / client)
    public function showRegisterChoice()
    {
        if (Session::has('user_id')) {
            return (int) Session::get('user_role') === 2
                ? redirect()->route('trainer.home')
                : redirect()->route('client.home');
        }
        return view('register_choice');
    }

    // Tampilan halaman register client (form step 1)
    public function showRegister()
    {
        if (Session::has('user_id')) {
            return (int) Session::get('user_role') === 2
                ? redirect()->route('trainer.home')
                : redirect()->route('client.home');
        }
        return view('register');
    }

    // Tampilan halaman register trainer
    public function showRegisterTrainer()
    {
        if (Session::has('user_id')) {
            return (int) Session::get('user_role') === 2
                ? redirect()->route('trainer.home')
                : redirect()->route('client.home');
        }
        return view('register_trainer');
    }

    // proses register trainer
    public function doRegisterTrainer(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:20|unique:user,username',
            'email'    => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'nama'     => 'required|string|max:100',
            'keahlian' => 'required|string|max:255',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.max'      => 'Username maksimal 20 karakter.',
            'username.unique'   => 'Username sudah digunakan, coba yang lain.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'keahlian.required' => 'Keahlian wajib diisi.',
        ]);

        DB::transaction(function () use ($data) {
            User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 2,
            ]);

            Trainer::create([
                'username' => $data['username'],
                'nama'     => $data['nama'],
                'keahlian' => $data['keahlian'],
            ]);
        });

        // Auto-login setelah register
        $request->session()->regenerate();
        $request->session()->put('user_id',   $data['username']);
        $request->session()->put('user_role', 2);
        $request->session()->put('user_name', $data['username']);

        return redirect()->route('trainer.home')
            ->with('ok', 'Registrasi berhasil. Selamat datang di CaloriMate!');
    }

    // proses register 
    public function doRegister(Request $request)
    {
        // 1. Validasi input (HANYA VALIDASI)
        $data = $request->validate([
            'username' => 'required|string|max:20|unique:user,username',
            'email'    => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.max'      => 'Username maksimal 20 karakter.',
            'username.unique'   => 'Username sudah digunakan, coba yang lain.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Hash password SETELAH validasi
        $data['password'] = Hash::make($data['password']);

        // 3. Simpan ke session (step 1)
        session([
            'reg.step1' => [
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => 1, // client
            ],
        ]);

        return redirect()->route('register.client.show');
    }
}
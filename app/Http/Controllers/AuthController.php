<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilan halaman login
    public function showLogin()
    {
       
        return view('login');
    }
    // Tampilan halaman login user
    public function showLoginUser()
    {
        return view('login_user');
    }
    // Tampilan halaman login trainer
    public function showLoginTrainer()
    {
        return view('login_trainer');
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

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
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
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
        }

        // Regenerate session
        $request->session()->regenerate();

        Session::put('user_id', $user->username);
        Session::put('user_role', $user->role);
        Session::put('user_name', $user->username);

        return (int) $user->role === 2
            ? redirect()->route('trainer.home')
            : redirect()->route('client.home');
    }

    // proses logout
    public function logout(Request $request)
    {
        // Proper logout
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('status', 'Logged out.');
    }

    // Tampilan halaman register
    public function showRegister()
    {
        if (Session::has('user_id')) {
            return (int) Session::get('user_role') === 2
                ? redirect()->route('trainer.home')
                : redirect()->route('client.home');
        }
        return view('register');
    }

    // proses register 
    public function doRegister(Request $request)
    {
        // 1. Validasi input (HANYA VALIDASI)
        $data = $request->validate([
            'username' => 'required|string|max:20|unique:user,username',
            'email'    => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8',
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // if (Session::has('user_id')) {
        //     // Route by role if already logged in
        //     return (int) Session::get('user_role') === 2
        //         ? redirect()->route('trainer.home')
        //         : redirect()->route('user.home');
        // }
        return view('login');
    }

    public function showLoginUser()
    {
        return view('login_user');
    }

    public function showLoginTrainer()
    {
        return view('login_trainer');
    }
    
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

        // Cari user berdasarkan username & role
        $user = User::where('username', $request->username)
        ->where('role', $request->role)
        ->first();

        // Cek password hashed
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
        }

        // Regenerate session (security best practice)
        $request->session()->regenerate();

        Session::put('user_id', $user->username);
        Session::put('user_role', $user->role);
        Session::put('user_name', $user->username);

        // Redirect by role
        return (int) $user->role === 2
            ? redirect()->route('trainer.home')
            : redirect()->route('client.home');
    }


    public function logout(Request $request)
    {
        // Proper logout in L11
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('status', 'Logged out.');
    }

    public function showRegister()
    {
        if (Session::has('user_id')) {
            return (int) Session::get('user_role') === 2
                ? redirect()->route('trainer.home')
                : redirect()->route('client.home');
        }
        return view('register');
    }

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
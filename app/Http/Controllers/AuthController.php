<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Match username and plaintext password
        $user = User::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if (!$user) {
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
            : redirect()->route('user.home');
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
                : redirect()->route('user.home');
        }
        return view('register');
    }

 public function doRegister(Request $request)
{
    $data = $request->validate([
        // ganti 'user' -> 'users' jika tabelmu plural
        'username' => 'required|string|max:20|unique:user,username',
        'email'    => 'required|email|max:255|unique:user,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // simpan ke session (password di-hash, role dipaksa client = 1)
    session([
        'reg.step1' => [
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => 1, // client only
        ],
    ]);

    return redirect()->route('register.client.show');
}



}

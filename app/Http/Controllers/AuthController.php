<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Optional: redirect if already logged in
        if (Session::has('user_id')) {
            return ((int) Session::get('user_role') === 2)
                ? redirect()->route('trainer.home')
                : redirect()->route('user.home');
        }
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

    // Login
    public function doLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20',
            'password' => 'required|string',
        ]);

        $user = User::authenticate($request->username, $request->password);

        if (!$user) {
            return back()->withErrors(['username' => 'Invalid username or password'])->withInput();
        }

        $request->session()->regenerate();

        Session::put('user_id', $user->username);
        Session::put('user_role', (int) $user->role);
        Session::put('user_name', $user->username);

        return $user->isTrainer()
            ? redirect()->route('trainer.home')
            : redirect()->route('user.home');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('status', 'Logged out.');
    }

    //Register
    public function showRegister()
    {
        if (Session::has('user_id')) {
            return ((int) Session::get('user_role') === 2)
                ? redirect()->route('trainer.home')
                : redirect()->route('user.home');
        }
        return view('register');
    }

    // Step 1 registration
    public function doRegister(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:20|alpha_dash|unique:user,username',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:4|confirmed', // still plain text by your request
        ]);

        session([
            'reg.step1' => [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 1, // client by default; trainers probably have another registration flow
            ],
        ]);

        return redirect()->route('register.client.show');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Client;

class ClientRegisterController extends Controller
{
    public function show(Request $request)
    {
        $username = Session::get('pending_client_username');

        // If user bypasses step-1, bounce to register/login
        if (!$username) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Finish the first registration step first.']);
        }

        // If client already exists, skip to home
        if (Client::where('username', $username)->exists()) {
            Session::forget('pending_client_username');
            return redirect()->route('user.home');
        }

        return view('register_client', ['username' => $username]);
    }

    public function store(Request $request)
    {
        $username = Session::get('pending_client_username');
        if (!$username) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'Session expired. Please register again.']);
        }

        // Validate payload
        $data = $request->validate([
            'tinggi_badan' => 'required|numeric|min:50|max:300',
            'berat_badan' => 'required|numeric|min:10|max:500',
            'gender' => 'required|in:L,P,M,F,male,female',
            'umur' => 'required|integer|min:5|max:120',
        ]);

        $user = User::where('username', $username)->where('role', 1)->first();
        if (!$user) {
            return redirect()->route('register.form')
                ->withErrors(['username' => 'User not found or role invalid.']);
        }

        if (Client::where('username', $username)->exists()) {
            Session::forget('pending_client_username');
            return redirect()->route('user.home');
        }

        // Convert to float with 2 decimals
        Client::create([
            'username' => $username,
            'tb' => (float) number_format($data['tinggi_badan'], 2, '.', ''),
            'bb' => (float) number_format($data['berat_badan'], 2, '.', ''),
            'gender' => $data['gender'],
            'umur' => $data['umur'],
        ]);

        Session::forget('pending_client_username');
        return redirect()->route('user.home')->with('status', 'Profile completed!');
    }
}

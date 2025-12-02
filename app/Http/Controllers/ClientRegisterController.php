<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClientRegisterController extends Controller
{
    public function show()
    {
        if (!session()->has('reg.step1')) {
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

    public function store(Request $request)
    {
        // Ensure step1 exists
        $step = Session::get('reg.step1');
        if (!$step) {
            return redirect()->route('register.form')
                ->withErrors(['register' => 'Please complete the first registration step.']);
        }

        // Validate client fields
        $data = $request->validate([
            'tinggi_badan' => 'required|numeric|min:100|max:300',
            'berat_badan'  => 'required|numeric|min:40|max:500',
            'gender'       => 'required|in:L,P',
            'umur'         => 'required|integer|min:5|max:120',
        ]);


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

        // Clear the step
        Session::forget('reg.step1');

        // Auto login
        $request->session()->regenerate();
        Session::put('user_id', $step['username']);
        Session::put('user_role', 1);
        Session::put('user_name', $step['username']);

        return redirect()->route('user.home')->with('status', 'Registration complete.');
    }
}

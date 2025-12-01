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
                ->withErrors(['register' => 'Please complete the first registration step.']);
        }

        $step = session('reg.step1'); // ['username'=>..., 'email'=>..., ...]
        return view('register_client', [
            'username' => $step['username'],
        ]);
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
            'tb' => 'required|numeric|min:50|max:300', // cm
            'bb' => 'required|numeric|min:10|max:400', // kg
            'gender' => 'required|in:L,P',
            'umur' => 'required|integer|min:1|max:120',
        ]);

        // Transactionally create user and client
        DB::transaction(function () use ($step, $data) {
            // Create user (table 'user', PK 'username')
            User::create([
                'username' => $step['username'],
                'email' => $step['email'],
                'password' => $step['password'], // plain text per your choice
                'role' => $step['role'],     // 1 = client
            ]);

            // Create client profile
            Client::create([
                'username' => $step['username'],
                'tb' => $data['tb'],
                'bb' => $data['bb'],
                'gender' => $data['gender'],
                'umur' => $data['umur'],
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

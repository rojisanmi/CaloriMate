<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $username = Session::get('user_id');
        $client = User::with('client')
            ->where('username', $username)
            ->firstOrFail()
            ->client;

        $bmi      = $client?->calculateBMI();
        $category = $client?->getBMICategory();

        return view('client_home', compact('bmi', 'category', 'client'));
    }
}

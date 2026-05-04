<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'email'    => 'admin@calorimate.com',
                'password' => Hash::make('admin123'),
                'role'     => 0,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            // Trainers
            ['username' => 'trainer1', 'email' => 'trainer1@calorimate.com', 'password' => Hash::make('password'), 'role' => 2],
            ['username' => 'trainer2', 'email' => 'trainer2@calorimate.com', 'password' => Hash::make('password'), 'role' => 2],

            // Clients
            ['username' => 'client1', 'email' => 'client1@calorimate.com', 'password' => Hash::make('password'), 'role' => 1],
            ['username' => 'client2', 'email' => 'client2@calorimate.com', 'password' => Hash::make('password'), 'role' => 1],
            ['username' => 'client3', 'email' => 'client3@calorimate.com', 'password' => Hash::make('password'), 'role' => 1],
            ['username' => 'client4', 'email' => 'client4@calorimate.com', 'password' => Hash::make('password'), 'role' => 1],
            ['username' => 'client5', 'email' => 'client5@calorimate.com', 'password' => Hash::make('password'), 'role' => 1],
        ]);
    }
}

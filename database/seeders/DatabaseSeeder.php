<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,   // role 0 — admin
            UserSeeder::class,    // role 1 (client) + role 2 (trainer)
            TrainerSeeder::class, // profil trainer
            ClientSeeder::class,  // profil client (BMI data)
            FoodSeeder::class,    // 33 makanan Indonesia
            ProgramSeeder::class, // 6 program latihan + items
            HistorySeeder::class, // 14 hari history per client
        ]);
    }
}

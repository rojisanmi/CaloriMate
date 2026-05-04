<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainers')->insert([
            [
                'username'     => 'trainer1',
                'nama'         => 'Budi Santoso',
                'keahlian'     => 'Strength Training, HIIT',
                'sertifikasi'  => null,
            ],
            [
                'username'     => 'trainer2',
                'nama'         => 'Sari Dewi',
                'keahlian'     => 'Yoga, Pilates, Cardio',
                'sertifikasi'  => null,
            ],
        ]);
    }
}

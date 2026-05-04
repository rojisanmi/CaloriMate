<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // BMI categories for variety:
        // client1: Normal (BMI ~22)   — 170cm 64kg L 20thn
        // client2: Overweight (BMI ~27) — 165cm 73kg P 22thn
        // client3: Underweight (BMI ~17) — 168cm 48kg L 19thn
        // client4: Normal (BMI ~23)   — 160cm 59kg P 21thn
        // client5: Obese (BMI ~31)    — 163cm 82kg L 25thn

        DB::table('client')->insert([
            [
                'username'      => 'client1',
                'tb'            => 170,
                'bb'            => 64,
                'gender'        => 'L',
                'umur'          => 20,
                'calorie_target'=> null,
                'protein_ratio' => 30,
                'carbo_ratio'   => 40,
                'fat_ratio'     => 30,
            ],
            [
                'username'      => 'client2',
                'tb'            => 165,
                'bb'            => 73,
                'gender'        => 'P',
                'umur'          => 22,
                'calorie_target'=> null,
                'protein_ratio' => 30,
                'carbo_ratio'   => 40,
                'fat_ratio'     => 30,
            ],
            [
                'username'      => 'client3',
                'tb'            => 168,
                'bb'            => 48,
                'gender'        => 'L',
                'umur'          => 19,
                'calorie_target'=> null,
                'protein_ratio' => 35,
                'carbo_ratio'   => 40,
                'fat_ratio'     => 25,
            ],
            [
                'username'      => 'client4',
                'tb'            => 160,
                'bb'            => 59,
                'gender'        => 'P',
                'umur'          => 21,
                'calorie_target'=> null,
                'protein_ratio' => 30,
                'carbo_ratio'   => 40,
                'fat_ratio'     => 30,
            ],
            [
                'username'      => 'client5',
                'tb'            => 163,
                'bb'            => 82,
                'gender'        => 'L',
                'umur'          => 25,
                'calorie_target'=> null,
                'protein_ratio' => 25,
                'carbo_ratio'   => 45,
                'fat_ratio'     => 30,
            ],
        ]);
    }
}

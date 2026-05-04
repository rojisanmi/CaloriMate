<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'name'             => 'Yoga Pagi',
                'type'             => 'Fleksibilitas',
                'difficulty'       => 'Low',
                'duration_minutes' => 30,
                'items' => [
                    ['Pemanasan Ringan',       5, 'low'],
                    ['Pose Mountain & Tree',   8, 'low'],
                    ['Pose Warrior I & II',    8, 'low'],
                    ['Pose Child & Cat-Cow',   5, 'low'],
                    ['Pendinginan & Napas',    4, 'low'],
                ],
            ],
            [
                'name'             => 'Jalan & Lari Ringan',
                'type'             => 'Kardio',
                'difficulty'       => 'Low',
                'duration_minutes' => 45,
                'items' => [
                    ['Jalan Cepat',    15, 'low'],
                    ['Lari Santai',    20, 'low'],
                    ['Jalan Santai',   10, 'low'],
                ],
            ],
            [
                'name'             => 'Kardio Dasar',
                'type'             => 'Kardio',
                'difficulty'       => 'Medium',
                'duration_minutes' => 40,
                'items' => [
                    ['Pemanasan Lari Pelan',  5, 'low'],
                    ['Jumping Jack',         10, 'medium'],
                    ['High Knees',           10, 'medium'],
                    ['Mountain Climbers',    10, 'medium'],
                    ['Pendinginan',           5, 'low'],
                ],
            ],
            [
                'name'             => 'Full Body Workout',
                'type'             => 'Kekuatan',
                'difficulty'       => 'Medium',
                'duration_minutes' => 50,
                'items' => [
                    ['Pemanasan Dinamis',  5, 'low'],
                    ['Squat',            12, 'medium'],
                    ['Push Up',          10, 'medium'],
                    ['Plank',             8, 'medium'],
                    ['Lunges',           10, 'medium'],
                    ['Pendinginan',       5, 'low'],
                ],
            ],
            [
                'name'             => 'HIIT Pembakaran Kalori',
                'type'             => 'HIIT',
                'difficulty'       => 'High',
                'duration_minutes' => 35,
                'items' => [
                    ['Pemanasan',              5, 'low'],
                    ['Burpees',                8, 'high'],
                    ['Sprint di Tempat',       7, 'high'],
                    ['Box Jump / Jump Squat',  8, 'high'],
                    ['Pendinginan',            7, 'low'],
                ],
            ],
            [
                'name'             => 'Latihan Kekuatan Penuh',
                'type'             => 'Kekuatan',
                'difficulty'       => 'High',
                'duration_minutes' => 60,
                'items' => [
                    ['Pemanasan',              5, 'low'],
                    ['Deadlift / Hip Hinge',  12, 'high'],
                    ['Bench Press / Push Up', 12, 'high'],
                    ['Pull Up / Lat Pulldown',10, 'high'],
                    ['Overhead Press',        10, 'high'],
                    ['Core Circuit',          11, 'medium'],
                    ['Pendinginan',            5, 'low'],
                ],
            ],
        ];

        foreach ($programs as $program) {
            $items = $program['items'];
            unset($program['items']);

            $programId = DB::table('programs')->insertGetId($program);

            foreach ($items as [$exercise, $duration, $intensity]) {
                DB::table('program_items')->insert([
                    'program_id'      => $programId,
                    'exercise_name'   => $exercise,
                    'duration_minutes'=> $duration,
                    'intensity_level' => $intensity,
                ]);
            }
        }
    }
}

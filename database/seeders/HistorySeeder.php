<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistorySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua food_id dan program_id yang sudah di-seed
        $foodIds    = DB::table('foods')->pluck('food_id')->toArray();
        $programIds = DB::table('programs')->pluck('program_id')->toArray();

        if (empty($foodIds) || empty($programIds)) {
            $this->command->warn('HistorySeeder: FoodSeeder dan ProgramSeeder harus dijalankan dulu.');
            return;
        }

        // Pola per client: [username, activeDays(dari 14), exerciseDayInterval]
        // client1 — aktif, rutin olahraga (tiap 2 hari)
        // client2 — cukup aktif, jarang olahraga
        // client3 — jarang catat (underweight, perlu saran)
        // client4 — aktif, tidak pernah olahraga
        // client5 — aktif, olahraga rutin (overweight)

        $patterns = [
            'client1' => ['active_days' => 13, 'exercise_every' => 2],
            'client2' => ['active_days' => 9,  'exercise_every' => 4],
            'client3' => ['active_days' => 4,  'exercise_every' => 0],
            'client4' => ['active_days' => 12, 'exercise_every' => 0],
            'client5' => ['active_days' => 11, 'exercise_every' => 2],
        ];

        foreach ($patterns as $username => $pattern) {
            $this->seedClientHistory($username, $pattern, $foodIds, $programIds);
        }
    }

    private function seedClientHistory(string $username, array $pattern, array $foodIds, array $programIds): void
    {
        $activeDayCount = 0;

        for ($daysAgo = 13; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo)->format('Y-m-d');

            // Lewati hari ini dengan probabilitas tertentu sesuai pola
            if ($activeDayCount >= $pattern['active_days']) {
                continue;
            }

            // Acak lewat beberapa hari agar tidak selalu berturutan
            $chance = $pattern['active_days'] / 14;
            if (mt_rand(1, 100) > ($chance * 100)) {
                continue;
            }

            $activeDayCount++;

            // Tentukan apakah hari ini ada latihan
            $hasExercise = $pattern['exercise_every'] > 0
                && ($activeDayCount % $pattern['exercise_every'] === 0);

            $programId  = $hasExercise ? $programIds[array_rand($programIds)] : null;
            $caloriOut  = 0;

            if ($hasExercise && $programId) {
                $items = DB::table('program_items')->where('program_id', $programId)->get();
                foreach ($items as $item) {
                    $caloriOut += $this->estimateCalories($item->duration_minutes, $item->intensity_level);
                }
            }

            $historyId = DB::table('histories')->insertGetId([
                'username'   => $username,
                'program_id' => $programId,
                'date'       => $date,
                'calori_in'  => 0,
                'calori_out' => $caloriOut,
            ]);

            // Pilih 3-5 makanan berbeda untuk hari ini
            $shuffled     = $foodIds;
            shuffle($shuffled);
            $todayFoods   = array_slice($shuffled, 0, mt_rand(3, 5));
            $categories   = ['breakfast', 'lunch', 'dinner', 'snack'];

            foreach ($todayFoods as $i => $foodId) {
                DB::table('food_consumptions')->insert([
                    'history_id' => $historyId,
                    'food_id'    => $foodId,
                    'portions'   => mt_rand(1, 2),
                    'category'   => $categories[$i % count($categories)],
                ]);
            }
        }
    }

    private function estimateCalories(int $duration, string $intensity): int
    {
        $rate = match (strtolower($intensity)) {
            'low', 'rendah'    => 4,
            'high', 'tinggi'   => 10,
            default            => 7,
        };
        return $duration * $rate;
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticController extends Controller
{
    /**
     * Display daily statistics
     */
    public function index(Request $request)
    {
        $username = $request->user()->username;
        $today = Carbon::today();

        $todayHistory = History::where('username', $username)
            ->whereDate('date', $today)
            ->with(['foodConsumptions.food', 'historyPrograms.program.items'])
            ->first();

        $totalKaloriMasuk = 0;
        $totalKaloriKeluar = 0;

        $totalProtein = 0;
        $totalLemak = 0;
        $totalKarbo = 0;

        $foodsToday = [];
        $aktivitas = collect();

        if ($todayHistory) {
            foreach ($todayHistory->foodConsumptions as $consumption) {
                $food = $consumption->food;
                if (!$food) continue;

                $porsi = $consumption->portions ?? 1;

                $kalori = ($food->calories_per_portion ?? 0) * $porsi;
                $protein = ($food->total_protein ?? 0) * $porsi;
                $lemak = ($food->total_fat ?? 0) * $porsi;
                $karbo = ($food->total_carbo ?? 0) * $porsi;

                $totalKaloriMasuk += $kalori;
                $totalProtein += $protein;
                $totalLemak += $lemak;
                $totalKarbo += $karbo;

                $foodsToday[] = [
                    'nama' => $food->name,
                    'kategori' => $consumption->category,
                    'porsi' => $porsi,
                    'kalori' => $kalori,
                    'protein' => $protein,
                    'lemak' => $lemak,
                    'karbo' => $karbo,
                ];
            }

            $totalKaloriKeluar = $todayHistory->calori_out ?? 0;

            if ($todayHistory->historyPrograms && $todayHistory->historyPrograms->isNotEmpty()) {
                $aktivitas = $todayHistory->historyPrograms->flatMap(function ($historyProgram) use ($todayHistory) {
                    if (!$historyProgram->program) return collect();
                    return $historyProgram->program->items->map(function ($item) use ($todayHistory) {
                        return [
                            'tanggal' => Carbon::parse($todayHistory->date)->translatedFormat('d M Y'),
                            'nama' => $item->exercise_name,
                            'waktu_menit' => $item->duration_minutes,
                            'kalori_terbakar' => $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level),
                        ];
                    });
                });
            }
        }

        $selisih = $totalKaloriMasuk - $totalKaloriKeluar;

        $statistik = [
            'kalori_masuk' => $totalKaloriMasuk,
            'kalori_keluar' => $totalKaloriKeluar,
            'selisih' => $selisih,
        ];

        $nutritionData = [
            'kalori' => round($totalKaloriMasuk, 2),
            'protein' => round($totalProtein, 2),
            'lemak' => round($totalLemak, 2),
            'karbo' => round($totalKarbo, 2),
        ];

        return response()->json([
            'date' => $today->toDateString(),
            'statistik' => $statistik,
            'nutrition' => $nutritionData,
            'foods_today' => $foodsToday,
            'aktivitas' => $aktivitas
        ]);
    }

    private function estimateCaloriesBurned(int $durationMinutes, ?string $intensityLevel): int
    {
        $caloriesPerMinute = match (strtolower($intensityLevel ?? '')) {
            'low', 'rendah' => 4,
            'medium', 'sedang' => 7,
            'high', 'tinggi' => 10,
            default => 5,
        };

        return $durationMinutes * $caloriesPerMinute;
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index()
    {
        $username = Session::get('user_id');
        $today = Carbon::today();

        // Ambil history untuk hari ini lengkap dengan makanan & program latihan
        $todayHistory = History::where('username', $username)
            ->whereDate('date', $today)
            ->with(['foodConsumptions.food', 'program.items'])
            ->first();

        $totalKaloriMasuk = 0;
        $totalKaloriKeluar = 0;

        $totalProtein = 0;
        $totalLemak = 0;
        $totalKarbo = 0;

        $foodsToday = [];
        $aktivitas = collect();

        if ($todayHistory) {
            // Hitung nutrisi harian dari makanan yang dimakan hari ini
            foreach ($todayHistory->foodConsumptions as $consumption) {
                $food = $consumption->food;
                if (!$food) {
                    continue;
                }

                $porsi = $consumption->portions ?? 1;

                $kalori = $food->calories_per_portion * $porsi;
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

            // Kalori keluar harian diambil dari kolom calori_out (diisi saat latihan)
            $totalKaloriKeluar = $todayHistory->calori_out ?? 0;

            // Riwayat aktivitas latihan hari ini (per item latihan)
            if ($todayHistory->program) {
                $aktivitas = $todayHistory->program->items->map(function ($item) use ($todayHistory) {
                    return [
                        'tanggal' => Carbon::parse($todayHistory->date)->translatedFormat('d M Y'),
                        'nama' => $item->exercise_name,
                        'waktu' => $item->duration_minutes . ' menit',
                        'kalori' => $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level),
                    ];
                });
            }
        }

        $selisih = $totalKaloriMasuk - $totalKaloriKeluar;

        $statistik = [
            'kalori_masuk' => number_format($totalKaloriMasuk, 0, ',', '.'),
            'kalori_keluar' => number_format($totalKaloriKeluar, 0, ',', '.'),
            'selisih' => number_format($selisih, 0, ',', '.'),
        ];

        // Data untuk pie chart nutrisi harian
        $nutritionChartData = [
            'labels' => ['Kalori', 'Protein (g)', 'Lemak (g)', 'Karbo (g)'],
            'values' => [
                round($totalKaloriMasuk, 2),
                round($totalProtein, 2),
                round($totalLemak, 2),
                round($totalKarbo, 2),
            ],
        ];

        return view('statistic-client', [
            'statistik' => $statistik,
            'aktivitas' => $aktivitas,
            'nutritionChartData' => $nutritionChartData,
            'foodsToday' => $foodsToday,
            'today' => $today,
        ]);
    }

    private function estimateCaloriesBurned(int $durationMinutes, string $intensityLevel): int
    {
        $caloriesPerMinute = match (strtolower($intensityLevel)) {
            'low', 'rendah' => 4,
            'medium', 'sedang' => 7,
            'high', 'tinggi' => 10,
            default => 5,
        };

        return $durationMinutes * $caloriesPerMinute;
    }
}

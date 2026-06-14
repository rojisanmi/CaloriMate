<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Client;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class StatisticController extends Controller
{
    // Halaman statistik harian client
    public function index()
    {
        $username = Session::get('user_id');
        $client   = Client::where('username', $username)->first();
        $today    = Carbon::today();

        // Ambil history untuk hari ini lengkap dengan makanan & program latihan
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
            if ($todayHistory->historyPrograms && $todayHistory->historyPrograms->isNotEmpty()) {
                $aktivitas = $todayHistory->historyPrograms->flatMap(function ($historyProgram) use ($todayHistory) {
                    if (!$historyProgram->program) return collect();
                    return $historyProgram->program->items->map(function ($item) use ($todayHistory) {
                        return [
                            'tanggal' => Carbon::parse($todayHistory->date)->translatedFormat('d M Y'),
                            'nama' => $item->exercise_name,
                            'waktu' => $item->duration_minutes . ' menit',
                            'kalori' => $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level),
                        ];
                    });
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
            'labels' => ['Protein (g)', 'Lemak (g)', 'Karbo (g)'],
            'values' => [
                round($totalProtein, 2),
                round($totalLemak, 2),
                round($totalKarbo, 2),
            ],
        ];

        $macroTargets  = $client ? $client->getMacroTargets() : ['protein' => 150, 'carbo' => 200, 'fat' => 67];
        $calorieTarget = $client ? $client->getEffectiveCalorieTarget() : 2000;

        // ── Weekly evaluation (last 7 days) ────────────────────────────────
        $weekStart = Carbon::today()->subDays(6);

        $weekHistories = History::where('username', $username)
            ->whereDate('date', '>=', $weekStart)
            ->whereDate('date', '<=', $today)
            ->with(['foodConsumptions.food'])
            ->get();

        $wCalIn = $wCalOut = $wProtein = $wKarbo = $wLemak = [];
        $activeDays = $exerciseDays = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date    = Carbon::today()->subDays($i);
            $dayHist = $weekHistories->first(fn($h) => Carbon::parse($h->date)->isSameDay($date));

            if ($dayHist) {
                $dayCalIn  = $dayHist->getTotalCaloriesConsumed();
                $dayCalOut = (float) $dayHist->calori_out;
                $dp = $dk = $dl = 0;
                foreach ($dayHist->foodConsumptions as $fc) {
                    $f = $fc->food;
                    if (!$f) continue;
                    $p = $fc->portions ?? 1;
                    $dp += ($f->total_protein ?? 0) * $p;
                    $dk += ($f->total_carbo   ?? 0) * $p;
                    $dl += ($f->total_fat     ?? 0) * $p;
                }
                if ($dayCalIn > 0 || $dayCalOut > 0) $activeDays++;
                if ($dayCalOut > 0) $exerciseDays++;
            } else {
                $dayCalIn = $dayCalOut = $dp = $dk = $dl = 0;
            }

            $wCalIn[]   = $dayCalIn;
            $wCalOut[]  = $dayCalOut;
            $wProtein[] = $dp;
            $wKarbo[]   = $dk;
            $wLemak[]   = $dl;
        }

        $weeklyEval = [
            'activeDays'     => $activeDays,
            'exerciseDays'   => $exerciseDays,
            'avgCaloriesIn'  => round(array_sum($wCalIn)  / 7),
            'avgCaloriesOut' => round(array_sum($wCalOut) / 7),
            'calorieTarget'  => round($calorieTarget),
            'avgProtein'     => round(array_sum($wProtein) / 7, 1),
            'avgKarbo'       => round(array_sum($wKarbo)   / 7, 1),
            'avgLemak'       => round(array_sum($wLemak)   / 7, 1),
            'weekStart'      => $weekStart,
            'weekEnd'        => $today,
            'tips'           => $this->generateWeeklyTips(
                array_sum($wCalIn) / 7,
                $calorieTarget,
                array_sum($wProtein) / 7,
                $macroTargets,
                $activeDays,
                $exerciseDays
            ),
        ];

        return view('statistic-client', [
            'statistik'          => $statistik,
            'aktivitas'          => $aktivitas,
            'nutritionChartData' => $nutritionChartData,
            'foodsToday'         => $foodsToday,
            'today'              => $today,
            'macroTargets'       => $macroTargets,
            'calorieTarget'      => $calorieTarget,
            'totalProtein'       => round($totalProtein, 1),
            'totalKarbo'         => round($totalKarbo, 1),
            'totalLemak'         => round($totalLemak, 1),
            'weeklyEval'         => $weeklyEval,
        ]);
    }

    private function generateWeeklyTips(
        float $avgCalIn, float $target,
        float $avgProtein, array $macroTargets,
        int $activeDays, int $exerciseDays
    ): array {
        $tips = [];

        if ($avgCalIn < $target * 0.8) {
            $tips[] = ['type' => 'warning', 'text' =>
                'Rata-rata asupan kalori kamu (' . round($avgCalIn) . ' kkal/hari) masih di bawah target. Coba tambah porsi makan atau camilan bergizi.'];
        } elseif ($avgCalIn > $target * 1.2) {
            $tips[] = ['type' => 'danger', 'text' =>
                'Rata-rata asupan kalori kamu (' . round($avgCalIn) . ' kkal/hari) melebihi target. Perhatikan ukuran porsi dan kurangi makanan berlemak atau manis.'];
        } else {
            $tips[] = ['type' => 'success', 'text' =>
                'Asupan kalori mingguan kamu sudah sesuai target. Pertahankan pola makan ini!'];
        }

        if ($exerciseDays === 0) {
            $tips[] = ['type' => 'danger', 'text' =>
                'Kamu belum melakukan latihan sama sekali minggu ini. Coba mulai dengan program ringan 2–3 kali per minggu.'];
        } elseif ($exerciseDays < 3) {
            $tips[] = ['type' => 'warning', 'text' =>
                "Kamu berolahraga {$exerciseDays} hari minggu ini. Coba tingkatkan ke minimal 3 hari untuk hasil optimal."];
        } else {
            $tips[] = ['type' => 'success', 'text' =>
                "Bagus! Kamu aktif berolahraga {$exerciseDays} hari minggu ini. Tetap konsisten!"];
        }

        if ($activeDays < 3) {
            $tips[] = ['type' => 'warning', 'text' =>
                "Kamu mencatat aktivitas hanya {$activeDays} hari minggu ini. Mencatat setiap hari membantu memantau progres lebih akurat."];
        }

        if ($avgProtein < $macroTargets['protein'] * 0.7) {
            $tips[] = ['type' => 'warning', 'text' =>
                'Asupan protein rata-rata kamu masih kurang dari target. Tambahkan sumber protein seperti ayam, ikan, telur, atau tahu/tempe.'];
        }

        return $tips;
    }

    // Estimasi kalori terbakar berdasarkan durasi dan tingkat intensitas
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

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

        $histories = History::where('username', $username)
            ->with(['program.items'])
            ->orderBy('date', 'desc')
            ->get();

        $totalKaloriMasuk = $histories->sum('calori_in');
        $totalKaloriKeluar = $histories->sum('calori_out');
        $selisih = $totalKaloriMasuk - $totalKaloriKeluar;

        $statistik = [
            'kalori_masuk' => number_format($totalKaloriMasuk, 0, ',', '.'),
            'kalori_keluar' => number_format($totalKaloriKeluar, 0, ',', '.'),
            'selisih' => number_format($selisih, 0, ',', '.'),
        ];

        $aktivitas = $histories
            ->filter(fn($h) => $h->program !== null)
            ->flatMap(function ($history) {
                return $history->program->items->map(function ($item) use ($history) {
                    return [
                        'tanggal' => Carbon::parse($history->date)->translatedFormat('d M Y'),
                        'nama' => $item->exercise_name,
                        'waktu' => $item->duration_minutes . ' menit',
                        'kalori' => $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level),
                    ];
                });
            })
            ->values();

        return view('statistic-client', compact('statistik', 'aktivitas'));
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

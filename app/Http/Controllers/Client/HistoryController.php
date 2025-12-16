<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $username = Session::get('user_id');
        // period untuk chart & list:
        // - daily   : 7 hari terakhir
        // - weekly  : beberapa minggu terakhir
        // - monthly : beberapa bulan terakhir
        $period = $request->get('period', 'daily');

        // Tentukan rentang tanggal maksimal yang dibutuhkan
        $startDate = match ($period) {
            'daily' => Carbon::today()->subDays(6),   // 7 hari (hari ini + 6 hari ke belakang)
            'weekly' => Carbon::today()->subWeeks(7), // 8 minggu ke belakang
            'monthly' => Carbon::today()->subMonths(5), // 6 bulan ke belakang
            // fallback untuk nilai lama supaya tidak error
            '1_day' => Carbon::today(),
            '7_days' => Carbon::today()->subDays(6),
            '1_month' => Carbon::today()->subMonth(),
            default => Carbon::today()->subDays(6),
        };

        $today = Carbon::today();

        // Ambil seluruh history dalam rentang yang dibutuhkan (untuk chart & aktivitas)
        $historiesCollection = History::where('username', $username)
            ->whereDate('date', '>=', $startDate)
            ->with(['foodConsumptions.food', 'program'])
            ->orderBy('date', 'asc') // asc supaya enak diolah ke chart
            ->get();

        // ====== DATA UNTUK LIST (detail makanan) – tetap seperti sebelumnya ======
        $historiesForList = $historiesCollection->sortByDesc('date');

        $histories = $historiesForList->flatMap(function ($history) {
            return $history->foodConsumptions->map(function ($consumption) use ($history) {
                return [
                    'name' => $consumption->food->name,
                    'date' => $history->date,
                    'calories' => $consumption->food->calories_per_portion * $consumption->portions,
                ];
            });
        });

        // ====== DATA UNTUK CHART (kalori masuk & keluar) ======
        $chartData = [
            'labels' => [],
            'calori_in' => [],
            'calori_out' => [],
        ];

        if ($period === 'daily' || $period === '1_day' || $period === '7_days') {
            // 7 hari terakhir (harian)
            $start = $period === '1_day'
                ? $today // fallback lama: hanya hari ini, tapi kita jadikan 1 bar saja
                : $today->copy()->subDays(6);

            for ($date = $start->copy(); $date->lte($today); $date->addDay()) {
                $label = $date->translatedFormat('d M');

                // Filter history yang benar-benar jatuh pada tanggal tersebut
                $collectionByDate = $historiesCollection->filter(function (History $history) use ($date) {
                    return Carbon::parse($history->date)->isSameDay($date);
                });

                // kalori masuk dihitung dari makanan yang dimakan (relasi foodConsumptions)
                $in = $collectionByDate->sum(function (History $history) {
                    return $history->getTotalCaloriesConsumed();
                });

                // kalori keluar dari aktivitas latihan (exercise)
                $out = $collectionByDate->sum('calori_out');

                $chartData['labels'][] = $label;
                $chartData['calori_in'][] = $in;
                $chartData['calori_out'][] = $out;
            }
        } elseif ($period === 'weekly') {
            // Kelompokkan per minggu (8 minggu terakhir)
            $grouped = [];
            foreach ($historiesCollection as $history) {
                $date = Carbon::parse($history->date);
                $yearWeek = $date->format('o-W'); // contoh: 2025-03
                if (!isset($grouped[$yearWeek])) {
                    $grouped[$yearWeek] = [
                        'label' => 'Minggu ' . $date->isoWeek() . ' (' . $date->startOfWeek()->translatedFormat('d M') . ')',
                        'in' => 0,
                        'out' => 0,
                    ];
                }
                // kalori masuk dari makanan
                $grouped[$yearWeek]['in'] += $history->getTotalCaloriesConsumed();
                // kalori keluar dari latihan
                $grouped[$yearWeek]['out'] += $history->calori_out;
            }

            foreach ($grouped as $g) {
                $chartData['labels'][] = $g['label'];
                $chartData['calori_in'][] = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        } else {
            // monthly atau 1_month: kelompokkan per bulan (6 bulan terakhir)
            $grouped = [];
            foreach ($historiesCollection as $history) {
                $date = Carbon::parse($history->date);
                $yearMonth = $date->format('Y-m');
                if (!isset($grouped[$yearMonth])) {
                    $grouped[$yearMonth] = [
                        'label' => $date->translatedFormat('M Y'),
                        'in' => 0,
                        'out' => 0,
                    ];
                }
                // kalori masuk dari makanan
                $grouped[$yearMonth]['in'] += $history->getTotalCaloriesConsumed();
                // kalori keluar dari latihan
                $grouped[$yearMonth]['out'] += $history->calori_out;
            }

            foreach ($grouped as $g) {
                $chartData['labels'][] = $g['label'];
                $chartData['calori_in'][] = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        }

        // ====== DATA AKTIVITAS LATIHAN (untuk ditampilkan di bawah chart) ======
        $activities = $historiesCollection
            ->filter(function (History $history) {
                return $history->calori_out > 0 && $history->program !== null;
            })
            ->map(function (History $history) {
                return [
                    'date' => $history->date,
                    'program_name' => $history->program->name ?? 'Program Latihan',
                    'calories_out' => $history->calori_out,
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('history-client', compact('histories', 'period', 'chartData', 'activities'));
    }
}

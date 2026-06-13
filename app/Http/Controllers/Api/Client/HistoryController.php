<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoryController extends Controller
{
    /**
     * Display history based on period
     */
    public function index(Request $request)
    {
        $username = $request->user()->username;
        $period = $request->query('period', 'daily');

        $startDate = match ($period) {
            'daily' => Carbon::today()->subDays(6),
            'weekly' => Carbon::today()->subWeeks(7),
            'monthly' => Carbon::today()->subMonths(5),
            '1_day' => Carbon::today(),
            '7_days' => Carbon::today()->subDays(6),
            '1_month' => Carbon::today()->subMonth(),
            default => Carbon::today()->subDays(6),
        };

        $today = Carbon::today();

        $historiesCollection = History::where('username', $username)
            ->whereDate('date', '>=', $startDate)
            ->with(['foodConsumptions.food', 'historyPrograms.program.items'])
            ->orderBy('date', 'asc')
            ->get();

        $historiesForList = $historiesCollection->sortByDesc('date');
        $histories = $historiesForList->flatMap(function ($history) {
            return $history->foodConsumptions->map(function ($consumption) use ($history) {
                return [
                    'name' => $consumption->food->name ?? 'Unknown',
                    'date' => $history->date,
                    'calories' => ($consumption->food->calories_per_portion ?? 0) * $consumption->portions,
                ];
            });
        })->values();

        $chartData = [
            'labels' => [],
            'calori_in' => [],
            'calori_out' => [],
        ];

        if (in_array($period, ['daily', '1_day', '7_days'])) {
            $start = $period === '1_day' ? $today : $today->copy()->subDays(6);
            for ($date = $start->copy(); $date->lte($today); $date->addDay()) {
                $label = $date->translatedFormat('d M');
                $collectionByDate = $historiesCollection->filter(fn(History $h) => Carbon::parse($h->date)->isSameDay($date));
                
                $in = $collectionByDate->sum(fn(History $h) => $h->getTotalCaloriesConsumed());
                $out = $collectionByDate->sum('calori_out');

                $chartData['labels'][] = $label;
                $chartData['calori_in'][] = $in;
                $chartData['calori_out'][] = $out;
            }
        } elseif ($period === 'weekly') {
            $grouped = [];
            foreach ($historiesCollection as $history) {
                $date = Carbon::parse($history->date);
                $yearWeek = $date->format('o-W');
                if (!isset($grouped[$yearWeek])) {
                    $grouped[$yearWeek] = [
                        'label' => 'Minggu ' . $date->isoWeek() . ' (' . $date->startOfWeek()->translatedFormat('d M') . ')',
                        'in' => 0,
                        'out' => 0,
                    ];
                }
                $grouped[$yearWeek]['in'] += $history->getTotalCaloriesConsumed();
                $grouped[$yearWeek]['out'] += $history->calori_out;
            }
            foreach ($grouped as $g) {
                $chartData['labels'][] = $g['label'];
                $chartData['calori_in'][] = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        } else {
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
                $grouped[$yearMonth]['in'] += $history->getTotalCaloriesConsumed();
                $grouped[$yearMonth]['out'] += $history->calori_out;
            }
            foreach ($grouped as $g) {
                $chartData['labels'][] = $g['label'];
                $chartData['calori_in'][] = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        }

        $activities = $historiesCollection
            ->filter(fn(History $h) => $h->calori_out > 0 && $h->historyPrograms->isNotEmpty())
            ->flatMap(function (History $h) {
                return $h->historyPrograms->map(function ($hp) use ($h) {
                    $programCalories = 0;
                    if ($hp->program && $hp->program->items) {
                        $programCalories = $hp->program->items->sum(function ($item) {
                            return $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level);
                        });
                    }
                    return [
                        'date' => $h->date,
                        'program_name' => $hp->program->name ?? 'Program Latihan',
                        'calories_out' => $programCalories > 0 ? $programCalories : $h->calori_out,
                    ];
                });
            })
            ->sortByDesc('date')
            ->values();

        return response()->json([
            'period' => $period,
            'histories' => $histories,
            'chart_data' => $chartData,
            'activities' => $activities
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

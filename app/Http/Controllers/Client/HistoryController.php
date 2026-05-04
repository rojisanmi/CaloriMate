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

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->get('date_from'))->startOfDay()
            : Carbon::today()->subDays(6);

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->get('date_to'))->endOfDay()
            : Carbon::today();

        if ($dateFrom->gt($dateTo)) {
            $dateFrom = $dateTo->copy()->subDays(6);
        }

        $historiesCollection = History::where('username', $username)
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->with(['foodConsumptions.food', 'program'])
            ->orderBy('date', 'asc')
            ->get();

        $histories = $historiesCollection->sortByDesc('date')->flatMap(function ($history) {
            return $history->foodConsumptions->map(function ($consumption) use ($history) {
                return [
                    'name'     => $consumption->food->name,
                    'date'     => $history->date,
                    'calories' => $consumption->food->calories_per_portion * $consumption->portions,
                ];
            });
        });

        $diffDays = $dateFrom->diffInDays($dateTo) + 1;
        $chartData = ['labels' => [], 'calori_in' => [], 'calori_out' => []];

        if ($diffDays <= 31) {
            for ($date = $dateFrom->copy(); $date->lte($dateTo); $date->addDay()) {
                $day = $historiesCollection->filter(
                    fn($h) => Carbon::parse($h->date)->isSameDay($date)
                );
                $chartData['labels'][]     = $date->translatedFormat('d M');
                $chartData['calori_in'][]  = $day->sum(fn($h) => $h->getTotalCaloriesConsumed());
                $chartData['calori_out'][] = $day->sum('calori_out');
            }
        } elseif ($diffDays <= 90) {
            $grouped = [];
            foreach ($historiesCollection as $history) {
                $d   = Carbon::parse($history->date);
                $key = $d->format('o-W');
                $grouped[$key] ??= [
                    'label' => 'Mg ' . $d->isoWeek() . ' (' . $d->copy()->startOfWeek()->translatedFormat('d M') . ')',
                    'in' => 0, 'out' => 0,
                ];
                $grouped[$key]['in']  += $history->getTotalCaloriesConsumed();
                $grouped[$key]['out'] += $history->calori_out;
            }
            foreach ($grouped as $g) {
                $chartData['labels'][]     = $g['label'];
                $chartData['calori_in'][]  = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        } else {
            $grouped = [];
            foreach ($historiesCollection as $history) {
                $d   = Carbon::parse($history->date);
                $key = $d->format('Y-m');
                $grouped[$key] ??= ['label' => $d->translatedFormat('M Y'), 'in' => 0, 'out' => 0];
                $grouped[$key]['in']  += $history->getTotalCaloriesConsumed();
                $grouped[$key]['out'] += $history->calori_out;
            }
            foreach ($grouped as $g) {
                $chartData['labels'][]     = $g['label'];
                $chartData['calori_in'][]  = $g['in'];
                $chartData['calori_out'][] = $g['out'];
            }
        }

        $activities = $historiesCollection
            ->filter(fn($h) => $h->calori_out > 0 && $h->program !== null)
            ->map(fn($h) => [
                'date'         => $h->date,
                'program_name' => $h->program->name ?? 'Program Latihan',
                'calories_out' => $h->calori_out,
            ])
            ->sortByDesc('date')
            ->values();

        return view('history-client', compact('histories', 'chartData', 'activities', 'dateFrom', 'dateTo'));
    }
}

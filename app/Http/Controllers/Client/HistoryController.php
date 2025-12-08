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
        $period = $request->get('period', '7_days');

        $startDate = match ($period) {
            '1_day' => Carbon::today(),
            '7_days' => Carbon::today()->subDays(6),
            '1_month' => Carbon::today()->subMonth(),
            default => Carbon::today()->subDays(6),
        };

        $historiesQuery = History::where('username', $username)
            ->whereDate('date', '>=', $startDate)
            ->with(['foodConsumptions.food'])
            ->orderBy('date', 'desc')
            ->get();

        $histories = $historiesQuery->flatMap(function ($history) {
            return $history->foodConsumptions->map(function ($consumption) use ($history) {
                return [
                    'name' => $consumption->food->name,
                    'date' => $history->date,
                    'calories' => $consumption->food->calories_per_portion * $consumption->portions,
                ];
            });
        });

        return view('history-client', compact('histories', 'period'));
    }
}

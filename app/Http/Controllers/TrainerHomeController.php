<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Program;
use App\Models\History;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TrainerHomeController extends Controller
{
    public function index()
    {
        $totalClients  = User::where('role', 1)->count();
        $totalFoods    = Food::count();
        $totalPrograms = Program::count();

        $activeClients = History::whereDate('date', '>=', now()->subDays(6))
            ->select('username')->distinct()->count();

        $topFoods = DB::table('food_consumptions')
            ->join('foods', 'food_consumptions.food_id', '=', 'foods.food_id')
            ->select('foods.name', DB::raw('COUNT(*) as frequency'), DB::raw('SUM(food_consumptions.portions) as total_portions'))
            ->groupBy('foods.food_id', 'foods.name')
            ->orderByDesc('frequency')
            ->limit(5)
            ->get();

        $topPrograms = DB::table('histories')
            ->join('programs', 'histories.program_id', '=', 'programs.program_id')
            ->whereNotNull('histories.program_id')
            ->select('programs.program_id', 'programs.name', 'programs.difficulty', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('programs.program_id', 'programs.name', 'programs.difficulty')
            ->orderByDesc('usage_count')
            ->limit(3)
            ->get();

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $chartValues[] = History::whereDate('date', $date)->select('username')->distinct()->count();
        }
        $activityChart = ['labels' => $chartLabels, 'values' => $chartValues];

        return view('trainer_home', compact(
            'totalClients', 'totalFoods', 'totalPrograms', 'activeClients',
            'topFoods', 'topPrograms', 'activityChart'
        ));
    }
}

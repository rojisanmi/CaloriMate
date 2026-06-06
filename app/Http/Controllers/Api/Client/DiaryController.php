<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodConsumption;
use App\Models\History;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * List foods for diary add-food picker
     */
    public function foods(Request $request)
    {
        $query = Food::orderBy('name');

        if ($q = $request->get('q')) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        return response()->json($query->limit(100)->get());
    }

    /**
     * Display diary overview
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $username = $user->username;
        $client = $user->client;

        $todayHistory = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        $consumedCalories = 0;
        $consumptions = [];

        if ($todayHistory) {
            $consumptionsData = $todayHistory->foodConsumptions()->with('food')->get();
            $consumedCalories = $consumptionsData->sum(fn($c) => ($c->food->calories_per_portion ?? 0) * $c->portions);
            
            foreach($consumptionsData as $c) {
                $category = $c->category;
                if(!isset($consumptions[$category])) {
                    $consumptions[$category] = [];
                }
                $consumptions[$category][] = $c;
            }
        }

        $dailyCaloriesTarget = $client ? $client->calculateDailyCalories() : 2000;
        $remainingCalories = max(0, $dailyCaloriesTarget - $consumedCalories);

        // Empty PHP array encodes as JSON [] — force object {} for mobile clients
        return response()->json([
            'daily_target' => $dailyCaloriesTarget,
            'consumed_calories' => $consumedCalories,
            'remaining_calories' => $remainingCalories,
            'consumptions' => empty($consumptions) ? (object) [] : $consumptions,
        ]);
    }

    /**
     * Store food into diary
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'food_id' => ['required', 'integer', 'exists:foods,food_id'],
            'portions' => ['required', 'integer', 'min:1'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ]);

        $username = $request->user()->username;

        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        $existing = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();

        if ($existing) {
            $existing->portions += $data['portions'];
            $existing->save();
        } else {
            FoodConsumption::create([
                'history_id' => $history->history_id,
                'food_id' => $data['food_id'],
                'portions' => $data['portions'],
                'category' => $data['category'],
            ]);
        }

        $food = Food::find($data['food_id']);
        $history->calori_in += ($food->calories_per_portion ?? 0) * $data['portions'];
        $history->save();

        return response()->json([
            'message' => 'Food added successfully'
        ], 201);
    }

    /**
     * Remove food from diary
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'food_id' => ['required', 'integer'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ]);

        $username = $request->user()->username;

        $history = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        if (!$history) {
            return response()->json(['message' => 'History not found'], 404);
        }

        $consumption = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();

        if ($consumption) {
            $food = Food::find($data['food_id']);
            $history->calori_in -= ($food->calories_per_portion ?? 0) * $consumption->portions;
            $history->calori_in = max(0, $history->calori_in);
            $history->save();

            $consumption->delete();

            return response()->json(['message' => 'Food removed successfully']);
        }

        return response()->json(['message' => 'Consumption not found'], 404);
    }
}

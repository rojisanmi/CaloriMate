<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Food;
use App\Models\FoodConsumption;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiaryController extends Controller
{
    // Halaman utama diary client
    public function index()
    {
        // get username dari session
        $username = Session::get('user_id');
        $client = Client::where('username', $username)->first();

        // get history hari ini
        $todayHistory = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        // hitung kalori dan makro yang sudah dikonsumsi hari ini
        $consumedCalories = 0;
        $consumedProtein  = 0;
        $consumedCarbo    = 0;
        $consumedFat      = 0;

        // foods grouped by category
        $foodsByCategory = [
            'breakfast' => [],
            'lunch'     => [],
            'dinner'    => [],
            'snack'     => [],
        ];

        if ($todayHistory) {
            $consumptions = $todayHistory->foodConsumptions()->with('food')->get();
            foreach ($consumptions as $c) {
                $food = $c->food;
                if (!$food) continue;
                $consumedCalories += $food->calories_per_portion * $c->portions;
                $consumedProtein  += ($food->total_protein ?? 0) * $c->portions;
                $consumedCarbo    += ($food->total_carbo   ?? 0) * $c->portions;
                $consumedFat      += ($food->total_fat     ?? 0) * $c->portions;

                $cat = $c->category ?? 'snack';
                if (!isset($foodsByCategory[$cat])) $foodsByCategory[$cat] = [];
                $foodsByCategory[$cat][] = [
                    'food_id'  => $food->food_id,
                    'name'     => $food->name,
                    'portions' => $c->portions,
                    'calories' => $food->calories_per_portion * $c->portions,
                ];
            }
        }

        // target kalori dan makro harian
        $dailyCaloriesTarget = $client ? $client->getEffectiveCalorieTarget() : 2000;
        $macroTargets        = $client ? $client->getMacroTargets() : ['protein' => 150, 'carbo' => 200, 'fat' => 67];

        $remainingCalories = max(0, $dailyCaloriesTarget - $consumedCalories);

        $remainingMacros = [
            'protein' => max(0, $macroTargets['protein'] - $consumedProtein),
            'carbo'   => max(0, $macroTargets['carbo']   - $consumedCarbo),
            'fat'     => max(0, $macroTargets['fat']     - $consumedFat),
        ];

        $calorieGoalReached = $remainingCalories <= 50;
        $defaultCategory    = $this->getDefaultCategory();
        $recommendations    = [];

        if (!$calorieGoalReached) {
            $recommendations = $this->getRecommendations(
                Food::orderBy('name')->get(),
                $remainingCalories,
                $remainingMacros,
                $client?->getBMICategory()
            );
        }

        return view('diary-client', compact(
            'remainingCalories', 'consumedCalories', 'dailyCaloriesTarget',
            'consumedProtein', 'consumedCarbo', 'consumedFat', 'macroTargets',
            'foodsByCategory', 'recommendations', 'defaultCategory', 'calorieGoalReached'
        ));
    }

    // halaman tambah makanan ke diary
    public function showAddFood(string $category)
    {
        $validCategories = ['breakfast', 'lunch', 'dinner', 'snack'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        $foods = Food::orderBy('name')->get();

        $username = Session::get('user_id');
        // get history hari ini
        $history = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        // get konsumsi makanan pada kategori ini hari ini
        $consumptions = [];
        if ($history) {
            $consumptions = FoodConsumption::with('food')
                ->where('history_id', $history->history_id)
                ->where('category', $category)
                ->get();
        }

        return view('diary-add-food', compact(
            'category',
            'foods',
            'consumptions'
        ));
    }

    // proses tambah makanan ke diary
    public function storeFood(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'food_id'  => ['required', 'integer', 'exists:foods,food_id'],
            'portions' => ['required', 'integer', 'min:1', 'max:50'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ], [
            'food_id.required'  => 'Pilih makanan terlebih dahulu.',
            'food_id.exists'    => 'Makanan tidak ditemukan.',
            'portions.required' => 'Jumlah porsi wajib diisi.',
            'portions.integer'  => 'Jumlah porsi harus berupa angka bulat.',
            'portions.min'      => 'Jumlah porsi minimal 1.',
            'portions.max'      => 'Jumlah porsi maksimal 50.',
        ]);

        $username = Session::get('user_id');

        // get atau buat history hari ini
        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        // cek apakah makanan sudah ada di kategori ini
        $existing = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();
        // jika ada, update porsi, jika tidak buat baru
        if ($existing) {
            FoodConsumption::where('history_id', $history->history_id)
                ->where('food_id', $data['food_id'])
                ->where('category', $data['category'])
                ->update(['portions' => $existing->portions + $data['portions']]);
        } else {
            FoodConsumption::create([
                'history_id' => $history->history_id,
                'food_id' => $data['food_id'],
                'portions' => $data['portions'],
                'category' => $data['category'],
            ]);
        }
        // update kalori masuk di history
        $food = Food::find($data['food_id']);
        $history->calori_in += $food->calories_per_portion * $data['portions'];
        $history->save();

        return redirect()
            ->route('client.diary')
            ->with('ok', 'Makanan berhasil ditambahkan');
    }

    // proses hapus makanan dari diary
    public function removeFood(Request $request)
    {
        $data = $request->validate([
            'food_id' => ['required', 'integer'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ]);

        $username = Session::get('user_id');

        $history = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        if (!$history) {
            return back()->with('error', 'History tidak ditemukan');
        }
        // cari konsumsi makanan
        $consumption = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();
        // jika ada, hapus konsumsi makanan dan update kalori masuk
        if ($consumption) {
            $food = Food::find($data['food_id']);
            $history->calori_in -= $food->calories_per_portion * $consumption->portions;
            $history->calori_in = max(0, $history->calori_in);
            $history->save();

            FoodConsumption::where('history_id', $history->history_id)
                ->where('food_id', $data['food_id'])
                ->where('category', $data['category'])
                ->delete();
        }

        return back()->with('ok', 'Makanan berhasil dihapus');
    }

    private function getRecommendations($foods, float $remainingCalories, array $remainingMacros, ?string $bmiCategory): array
    {
        $eligible = $foods->filter(fn($f) =>
            $f->calories_per_portion > 0 &&
            $f->calories_per_portion <= $remainingCalories
        );

        if ($eligible->isEmpty()) return [];

        $totalDeficiency = array_sum($remainingMacros);

        $scored = $eligible->map(function ($food) use ($remainingCalories, $remainingMacros, $totalDeficiency, $bmiCategory) {
            $score = 0;

            // Rule 1: Calorie fit (0-40 pts) — ideal range 15-40% of remaining
            $calRatio = $food->calories_per_portion / $remainingCalories;
            if ($calRatio >= 0.15 && $calRatio <= 0.40)      $score += 40;
            elseif ($calRatio > 0.40 && $calRatio <= 0.60)   $score += 25;
            elseif ($calRatio >= 0.05 && $calRatio < 0.15)   $score += 20;
            else                                               $score += 5;

            // Rule 2: Macro match (0-40 pts) — weighted by deficiency ratios
            if ($totalDeficiency > 0) {
                $proteinW = $remainingMacros['protein'] / $totalDeficiency;
                $carboW   = $remainingMacros['carbo']   / $totalDeficiency;
                $fatW     = $remainingMacros['fat']     / $totalDeficiency;

                $score += $remainingMacros['protein'] > 0
                    ? min(1, ($food->total_protein ?? 0) / $remainingMacros['protein']) * $proteinW * 40 : 0;
                $score += $remainingMacros['carbo'] > 0
                    ? min(1, ($food->total_carbo ?? 0) / $remainingMacros['carbo']) * $carboW * 40 : 0;
                $score += $remainingMacros['fat'] > 0
                    ? min(1, ($food->total_fat ?? 0) / $remainingMacros['fat']) * $fatW * 40 : 0;
            }

            // Rule 3: BMI preference (0-20 pts)
            if ($bmiCategory === 'Underweight') {
                if ($food->calories_per_portion >= 300)        $score += 20;
                elseif ($food->calories_per_portion >= 200)    $score += 12;
                if (($food->total_protein ?? 0) >= 15)         $score += 8;
            } elseif ($bmiCategory === 'Overweight' || $bmiCategory === 'Obese') {
                if ($food->calories_per_portion <= 200)        $score += 20;
                if (($food->total_protein ?? 0) >= 10)         $score += 8;
                if (($food->total_fat ?? 0) <= 5)              $score += 6;
                if (($food->total_carbo ?? 0) <= 30)           $score += 4;
            } else {
                if ($food->calories_per_portion >= 150 && $food->calories_per_portion <= 350) $score += 10;
            }

            return [
                'food'  => $food,
                'score' => $score,
                'tag'   => $this->buildReasonTag($food, $remainingMacros, $bmiCategory),
            ];
        });

        return $scored->sortByDesc('score')->take(6)->values()->toArray();
    }

    private function buildReasonTag(Food $food, array $remainingMacros, ?string $bmiCategory): string
    {
        if ($bmiCategory === 'Underweight' && $food->calories_per_portion >= 250) {
            return 'Tinggi kalori';
        }
        if (in_array($bmiCategory, ['Overweight', 'Obese']) && $food->calories_per_portion <= 150) {
            return 'Rendah kalori';
        }

        $maxVal = max($remainingMacros);
        if ($maxVal <= 0) return 'Seimbang';

        $maxMacro = array_search($maxVal, $remainingMacros);

        return match ($maxMacro) {
            'protein' => ($food->total_protein ?? 0) > 5  ? 'Tinggi protein' : 'Cocok untukmu',
            'carbo'   => ($food->total_carbo   ?? 0) > 10 ? 'Sumber karbo'   : 'Cocok untukmu',
            'fat'     => ($food->total_fat     ?? 0) > 3  ? 'Sumber lemak'   : 'Cocok untukmu',
            default   => 'Cocok untukmu',
        };
    }

    /**
     * Kembalikan kategori default berdasarkan jam saat ini (WIB).
     */
    private function getDefaultCategory(): string
    {
        $hour = now('Asia/Jakarta')->hour;
        if ($hour >= 5  && $hour < 10)  return 'breakfast';
        if ($hour >= 10 && $hour < 15)  return 'lunch';
        if ($hour >= 15 && $hour < 18)  return 'snack';
        return 'dinner';
    }
}

<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Food;
use App\Models\History;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Rekomendasi makanan berdasarkan sisa kuota kalori, defisiensi makro, dan BMI.
     *
     * GET /api/client/recommendations/food
     */
    public function food(Request $request)
    {
        $user     = $request->user();
        $username = $user->username;
        $client   = Client::where('username', $username)->first();

        // Hitung kalori & makro yang sudah dikonsumsi hari ini
        $todayHistory = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        $consumedCalories = 0;
        $consumedProtein  = 0;
        $consumedCarbo    = 0;
        $consumedFat      = 0;

        if ($todayHistory) {
            $consumptions = $todayHistory->foodConsumptions()->with('food')->get();
            foreach ($consumptions as $c) {
                $food = $c->food;
                if (!$food) continue;
                $consumedCalories += $food->calories_per_portion * $c->portions;
                $consumedProtein  += ($food->total_protein ?? 0) * $c->portions;
                $consumedCarbo    += ($food->total_carbo   ?? 0) * $c->portions;
                $consumedFat      += ($food->total_fat     ?? 0) * $c->portions;
            }
        }

        $dailyCaloriesTarget = $client ? $client->getEffectiveCalorieTarget() : 2000;
        $macroTargets        = $client ? $client->getMacroTargets() : ['protein' => 150, 'carbo' => 200, 'fat' => 67];

        $remainingCalories = max(0, $dailyCaloriesTarget - $consumedCalories);

        $remainingMacros = [
            'protein' => max(0, $macroTargets['protein'] - $consumedProtein),
            'carbo'   => max(0, $macroTargets['carbo']   - $consumedCarbo),
            'fat'     => max(0, $macroTargets['fat']     - $consumedFat),
        ];

        $bmiCategory = $client?->getBMICategory();

        // Jika target kalori sudah tercapai
        if ($remainingCalories <= 50) {
            return response()->json([
                'calorie_goal_reached' => true,
                'remaining_calories'   => $remainingCalories,
                'recommendations'      => [],
                'message'              => 'Target kalori harian sudah tercapai!',
            ]);
        }

        $foods           = Food::orderBy('name')->get();
        $recommendations = $this->getRecommendations($foods, $remainingCalories, $remainingMacros, $bmiCategory);

        return response()->json([
            'calorie_goal_reached' => false,
            'remaining_calories'   => round($remainingCalories),
            'remaining_macros'     => $remainingMacros,
            'bmi_category'         => $bmiCategory,
            'recommendations'      => $recommendations,
        ]);
    }

    /**
     * Rekomendasi latihan berdasarkan BMI, surplus/defisit kalori, dan target.
     *
     * GET /api/client/recommendations/exercise
     */
    public function exercise(Request $request)
    {
        $user     = $request->user();
        $username = $user->username;
        $client   = Client::where('username', $username)->first();

        $programs = Program::with('items')->get()->map(function ($program) {
            $estimatedCalories = $program->items->sum(function ($item) {
                return $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level);
            });
            return [
                'id'                 => $program->program_id,
                'title'              => $program->name,
                'type'               => $program->type,
                'difficulty'         => $program->difficulty,
                'duration'           => $program->duration_minutes,
                'estimated_calories' => $estimatedCalories,
            ];
        });

        $todayHistory     = History::where('username', $username)->whereDate('date', today())->first();
        $consumedCalories = $todayHistory ? $todayHistory->getTotalCaloriesConsumed() : 0;
        $calorieTarget    = $client ? $client->getEffectiveCalorieTarget() : 2000;
        $calorieSurplus   = $consumedCalories - $calorieTarget;
        $bmiCategory      = $client?->getBMICategory();

        $recommendations = $this->getExerciseRecommendations($programs, $bmiCategory, $calorieSurplus);

        return response()->json([
            'bmi_category'      => $bmiCategory,
            'calorie_surplus'   => round($calorieSurplus),
            'consumed_calories' => round($consumedCalories),
            'calorie_target'    => round($calorieTarget),
            'recommendations'   => $recommendations,
        ]);
    }

    /**
     * Evaluasi progress mingguan (7 hari terakhir) beserta saran perbaikan.
     *
     * GET /api/client/recommendations/weekly
     */
    public function weekly(Request $request)
    {
        $user     = $request->user();
        $username = $user->username;
        $client   = Client::where('username', $username)->first();

        $today     = Carbon::today();
        $weekStart = Carbon::today()->subDays(6);

        $macroTargets  = $client ? $client->getMacroTargets() : ['protein' => 150, 'carbo' => 200, 'fat' => 67];
        $calorieTarget = $client ? $client->getEffectiveCalorieTarget() : 2000;

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

        $tips = $this->generateWeeklyTips(
            array_sum($wCalIn) / 7,
            $calorieTarget,
            array_sum($wProtein) / 7,
            $macroTargets,
            $activeDays,
            $exerciseDays
        );

        return response()->json([
            'week_start'       => $weekStart->toDateString(),
            'week_end'         => $today->toDateString(),
            'active_days'      => $activeDays,
            'exercise_days'    => $exerciseDays,
            'avg_calories_in'  => round(array_sum($wCalIn) / 7),
            'avg_calories_out' => round(array_sum($wCalOut) / 7),
            'calorie_target'   => round($calorieTarget),
            'avg_protein'      => round(array_sum($wProtein) / 7, 1),
            'avg_karbo'        => round(array_sum($wKarbo) / 7, 1),
            'avg_lemak'        => round(array_sum($wLemak) / 7, 1),
            'tips'             => $tips,
        ]);
    }

    // ─── Private helpers — Food Recommendation Engine ───────────────────────────

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
                'score' => round($score, 2),
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

    // ─── Private helpers — Exercise Recommendation Engine ────────────────────────

    private function getExerciseRecommendations($programs, ?string $bmiCategory, float $calorieSurplus): array
    {
        if ($programs->isEmpty()) return [];

        $scored = $programs->map(function ($program) use ($bmiCategory, $calorieSurplus) {
            $score      = 0;
            $difficulty = $this->normalizeDifficulty($program['difficulty'] ?? '');
            $estCal     = $program['estimated_calories'];
            $duration   = $program['duration'] ?? 0;

            // Rule 1: BMI ↔ difficulty match (0–40 pts)
            if ($bmiCategory === 'Underweight') {
                $score += match ($difficulty) { 'low' => 40, 'medium' => 25, default => 10 };
            } elseif ($bmiCategory === 'Overweight' || $bmiCategory === 'Obese') {
                $score += match ($difficulty) { 'high' => 40, 'medium' => 35, default => 15 };
            } else {
                $score += match ($difficulty) { 'medium' => 40, default => 25 };
            }

            // Rule 2: calorie surplus match (0–40 pts)
            if ($calorieSurplus > 300) {
                if ($estCal >= 300)      $score += 40;
                elseif ($estCal >= 150)  $score += 25;
                else                     $score += 10;
            } elseif ($calorieSurplus > 0) {
                $score += ($estCal >= 100 && $estCal <= 400) ? 40 : 20;
            } else {
                $score += 30;
            }

            // Rule 3: duration suitability (0–20 pts)
            if ($bmiCategory === 'Underweight') {
                if ($duration > 0 && $duration <= 30) $score += 20;
                elseif ($duration <= 45)               $score += 10;
            } elseif ($bmiCategory === 'Overweight' || $bmiCategory === 'Obese') {
                if ($duration >= 30)   $score += 20;
                elseif ($duration > 0) $score += 10;
            } else {
                $score += ($duration >= 20 && $duration <= 60) ? 20 : 10;
            }

            return [
                'program' => $program,
                'score'   => $score,
                'tag'     => $this->buildExerciseReasonTag($difficulty, $estCal, $bmiCategory, $calorieSurplus),
            ];
        });

        return $scored->sortByDesc('score')->take(3)->values()->toArray();
    }

    private function buildExerciseReasonTag(string $difficulty, int $estCal, ?string $bmiCategory, float $calorieSurplus): string
    {
        if ($calorieSurplus > 300 && $estCal >= 300)                           return 'Bakar kalori berlebih';
        if ($bmiCategory === 'Underweight' && $difficulty === 'low')           return 'Aman untuk Underweight';
        if (in_array($bmiCategory, ['Overweight', 'Obese']) && $difficulty === 'high') return 'Efektif turunkan berat';
        return match ($difficulty) {
            'low'    => 'Cocok untuk pemula',
            'high'   => 'Tantangan tinggi',
            default  => 'Intensitas ideal',
        };
    }

    private function normalizeDifficulty(?string $difficulty): string
    {
        $d = strtolower(trim($difficulty ?? ''));
        if (in_array($d, ['low', 'rendah', 'beginner', 'pemula', 'mudah', 'easy'])) return 'low';
        if (in_array($d, ['high', 'tinggi', 'advanced', 'lanjutan', 'hard', 'sulit'])) return 'high';
        return 'medium';
    }

    // ─── Private helpers — Weekly Evaluation ─────────────────────────────────────

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

    private function estimateCaloriesBurned(int $durationMinutes, ?string $intensityLevel): int
    {
        $caloriesPerMinute = match (strtolower($intensityLevel ?? '')) {
            'low', 'rendah'     => 4,
            'medium', 'sedang'  => 7,
            'high', 'tinggi'    => 10,
            default             => 5,
        };
        return $durationMinutes * $caloriesPerMinute;
    }
}

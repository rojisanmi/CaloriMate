<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\History;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExerciseController extends Controller
{
    public function index()
    {
        $username = Session::get('user_id');
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
                'image'              => 'images/exercise-default.png',
            ];
        });

        $todayHistory     = History::where('username', $username)->whereDate('date', today())->first();
        $consumedCalories = $todayHistory ? $todayHistory->getTotalCaloriesConsumed() : 0;
        $calorieTarget    = $client ? $client->getEffectiveCalorieTarget() : 2000;
        $calorieSurplus   = $consumedCalories - $calorieTarget;

        $recommendations = $this->getExerciseRecommendations(
            $programs,
            $client?->getBMICategory(),
            $calorieSurplus
        );

        return view('exercise-client', compact('programs', 'recommendations', 'client'));
    }

    public function show(int $id)
    {
        $program = Program::with('items')->findOrFail($id);
        return view('exercise-detail-client', compact('program'));
    }

    public function play(Request $request, int $id)
    {
        $program = Program::with('items')->findOrFail($id);
        $items   = $program->items;

        if ($items->isEmpty()) {
            return redirect()
                ->route('client.exercise.show', $program->program_id)
                ->with('ok', 'Program ini belum memiliki daftar latihan.');
        }

        $totalSteps = $items->count();
        $step       = (int) $request->integer('step', 0);
        $step       = max(0, min($step, $totalSteps - 1));

        $currentItem     = $items[$step];
        $durationSeconds = max(1, $currentItem->duration_minutes * 60);

        return view('exercise-run-client', [
            'program'         => $program,
            'item'            => $currentItem,
            'step'            => $step,
            'totalSteps'      => $totalSteps,
            'durationSeconds' => $durationSeconds,
        ]);
    }

    public function start(int $id)
    {
        $username = Session::get('user_id');
        $program  = Program::with('items')->findOrFail($id);

        $totalCaloriesBurned = $program->items->sum(function ($item) {
            return $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level);
        });

        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        $history->program_id  = $program->program_id;
        $history->calori_out += $totalCaloriesBurned;
        $history->save();

        return redirect()
            ->route('client.exercise')
            ->with('ok', "Program latihan '{$program->name}' telah ditambahkan! Kalori terbakar: {$totalCaloriesBurned} kkal");
    }

    // ─── Private helpers ────────────────────────────────────────────────────────

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

    private function estimateCaloriesBurned(int $durationMinutes, string $intensityLevel): int
    {
        $caloriesPerMinute = match (strtolower($intensityLevel)) {
            'low', 'rendah'     => 4,
            'medium', 'sedang'  => 7,
            'high', 'tinggi'    => 10,
            default             => 5,
        };
        return $durationMinutes * $caloriesPerMinute;
    }
}

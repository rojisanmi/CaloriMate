<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Program;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display programs
     */
    public function index()
    {
        $programs = Program::with('items')->get()->map(function ($program) {
            return [
                'id' => $program->program_id,
                'title' => $program->name,
                'type' => $program->type,
                'difficulty' => $program->difficulty,
                'duration' => $program->duration_minutes,
                'image' => 'images/exercise-default.png',
            ];
        });

        return response()->json($programs);
    }

    /**
     * Display detail program
     */
    public function show(int $id)
    {
        $program = Program::with('items')->findOrFail($id);

        return response()->json($program);
    }

    /**
     * Start program and save to history
     */
    public function start(Request $request, int $id)
    {
        $username = $request->user()->username;
        $program = Program::with('items')->findOrFail($id);

        $totalCaloriesBurned = $program->items->sum(function ($item) {
            return $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level);
        });

        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        $history->historyPrograms()->create([
            'program_id' => $program->program_id
        ]);

        $history->calori_out += $totalCaloriesBurned;
        $history->save();

        return response()->json([
            'message' => "Program latihan '{$program->name}' telah ditambahkan. Kalori terbakar: {$totalCaloriesBurned} kkal",
            'calories_burned' => $totalCaloriesBurned
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

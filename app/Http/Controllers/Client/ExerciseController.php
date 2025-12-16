<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExerciseController extends Controller
{
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

        return view('exercise-client', compact('programs'));
    }

    public function show(int $id)
    {
        $program = Program::with('items')->findOrFail($id);

        return view('exercise-detail-client', compact('program'));
    }

    /**
     * Halaman menjalankan program latihan per item dengan timer & navigasi.
     */
    public function play(Request $request, int $id)
    {
        $program = Program::with('items')->findOrFail($id);
        $items = $program->items;

        if ($items->isEmpty()) {
            return redirect()
                ->route('client.exercise.show', $program->program_id)
                ->with('ok', 'Program ini belum memiliki daftar latihan.');
        }

        $totalSteps = $items->count();
        $step = (int) $request->integer('step', 0);
        if ($step < 0) {
            $step = 0;
        }
        if ($step >= $totalSteps) {
            $step = $totalSteps - 1;
        }

        $currentItem = $items[$step];
        $durationSeconds = max(1, $currentItem->duration_minutes * 60);

        return view('exercise-run-client', [
            'program' => $program,
            'item' => $currentItem,
            'step' => $step,
            'totalSteps' => $totalSteps,
            'durationSeconds' => $durationSeconds,
        ]);
    }

    public function start(int $id)
    {
        $username = Session::get('user_id');
        $program = Program::with('items')->findOrFail($id);

        $totalCaloriesBurned = $program->items->sum(function ($item) {
            return $this->estimateCaloriesBurned($item->duration_minutes, $item->intensity_level);
        });

        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        $history->program_id = $program->program_id;
        $history->calori_out += $totalCaloriesBurned;
        $history->save();

        return redirect()
            ->route('client.exercise')
            ->with('ok', "Program latihan '{$program->name}' telah ditambahkan! Kalori terbakar: {$totalCaloriesBurned} kkal");
    }

    private function estimateCaloriesBurned(int $durationMinutes, string $intensityLevel): int
    {
        $caloriesPerMinute = match (strtolower($intensityLevel)) {
            'low', 'rendah' => 4,
            'medium', 'sedang' => 7,
            'high', 'tinggi' => 10,
            default => 5,
        };

        return $durationMinutes * $caloriesPerMinute;
    }
}

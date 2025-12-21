<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExerciseController extends Controller
{
    // Halaman utama exercise client
    public function index()
    {
        // get semua program latihan
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

    // Tampilan detail program latihan
    public function show(int $id)
    {
        $program = Program::with('items')->findOrFail($id);

        return view('exercise-detail-client', compact('program'));
    }

    // Menambahkan program latihan ke history dan menghitung kalori terbakar
    public function play(Request $request, int $id)
    {
        $program = Program::with('items')->findOrFail($id);
        $items = $program->items;

        if ($items->isEmpty()) {
            return redirect()
                ->route('client.exercise.show', $program->program_id)
                ->with('ok', 'Program ini belum memiliki daftar latihan.');
        }
        // agar tidak keluar error saat step melebihi jumlah item
        $totalSteps = $items->count();
        $step = (int) $request->integer('step', 0);
        if ($step < 0) {
            $step = 0;
        }
        if ($step >= $totalSteps) {
            $step = $totalSteps - 1;
        }
        // item saat ini
        $currentItem = $items[$step];
        $durationSeconds = max(1, $currentItem->duration_minutes * 60);
        // tampilkan view exercise
        return view('exercise-run-client', [
            'program' => $program,
            'item' => $currentItem,
            'step' => $step,
            'totalSteps' => $totalSteps,
            'durationSeconds' => $durationSeconds,
        ]);
    }

    // Memulai program latihan dan mencatat kalori terbakar
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

    // Estimasi kalori terbakar berdasarkan durasi dan tingkat intensitas
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

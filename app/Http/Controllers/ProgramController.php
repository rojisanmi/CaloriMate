<?php

// app/Http/Controllers/ProgramController.php
namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $programs = Program::query()
            ->when($q, fn($qq) => $qq->where('name', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate((int) $request->integer('per_page', 10));

        return view('kelola-program', compact('programs'));
    }

    public function create()
    {
        return view('buat-program');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'nullable|string|max:50',
            'difficulty' => 'nullable|string|max:50',
            'duration_minutes' => 'nullable|integer|min:1',

            // arrays for program items
            'items' => 'array',
            'items.*.exercise_name' => 'required_with:items|string|max:100',
            'items.*.duration_minutes' => 'nullable|integer|min:1',
            'items.*.intensity_level' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($data) {
            $program = Program::create([
                'name' => $data['name'],
                'type' => $data['type'] ?? null,
                'difficulty' => $data['difficulty'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
            ]);

            if (!empty($data['items'])) {
                foreach ($data['items'] as $row) {
                    ProgramItem::create([
                        'program_id' => $program->program_id,
                        'exercise_name' => $row['exercise_name'],
                        'duration_minutes' => $row['duration_minutes'] ?? null,
                        'intensity_level' => $row['intensity_level'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('trainer.programs.index')->with('ok', 'Program dibuat');
    }

    public function edit(Program $program)
    {
        $program->load('items');
        return view('edit_program', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'nullable|string|max:50',
            'difficulty' => 'nullable|string|max:50',
            'duration_minutes' => 'nullable|integer|min:1',

            // Replace items completely on save (simple): send full set
            'items' => 'array',
            'items.*.program_item_id' => 'nullable|integer',
            'items.*.exercise_name' => 'required_with:items|string|max:100',
            'items.*.duration_minutes' => 'nullable|integer|min:1',
            'items.*.intensity_level' => 'nullable|string|max:50',
            'delete_item_ids' => 'array',
            'delete_item_ids.*' => 'integer',
        ]);

        DB::transaction(function () use ($program, $data) {
            $program->update([
                'name' => $data['name'],
                'type' => $data['type'] ?? null,
                'difficulty' => $data['difficulty'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
            ]);

            // delete removed items
            if (!empty($data['delete_item_ids'])) {
                ProgramItem::where('program_id', $program->program_id)
                    ->whereIn('program_item_id', $data['delete_item_ids'])
                    ->delete();
            }

            // upsert current items (by program_item_id if present)
            if (!empty($data['items'])) {
                foreach ($data['items'] as $row) {
                    if (!empty($row['program_item_id'])) {
                        ProgramItem::where('program_item_id', $row['program_item_id'])
                            ->where('program_id', $program->program_id)
                            ->update([
                                'exercise_name' => $row['exercise_name'],
                                'duration_minutes' => $row['duration_minutes'] ?? null,
                                'intensity_level' => $row['intensity_level'] ?? null,
                            ]);
                    } else {
                        ProgramItem::create([
                            'program_id' => $program->program_id,
                            'exercise_name' => $row['exercise_name'],
                            'duration_minutes' => $row['duration_minutes'] ?? null,
                            'intensity_level' => $row['intensity_level'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('trainer.programs.index')->with('ok', 'Program diperbarui');
    }

    public function destroy(Program $program)
    {
        // If you didn’t add FK cascade, this will only delete program row.
        // Consider manually deleting items or adding FK cascade.
        $program->delete();
        return back()->with('ok', 'Program dihapus');
    }
}


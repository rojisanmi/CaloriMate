<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 12);

        $programs = Program::query()
            ->when($q, fn($b) => $b->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json($programs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'nullable|string|max:50',
            'difficulty'       => 'nullable|string|max:50',

            // Items can be passed when creating program
            'items'                    => 'nullable|array',
            'items.*.exercise_name'    => 'required_with:items|string|max:255',
            'items.*.duration_minutes' => 'nullable|integer|min:0',
            'items.*.intensity_level'  => 'nullable|string|max:50',
        ]);

        $program = null;

        DB::transaction(function () use ($data, &$program) {
            $program = Program::create([
                'name'             => $data['name'],
                'type'             => $data['type'] ?? null,
                'difficulty'       => $data['difficulty'] ?? null,
            ]);

            foreach ($data['items'] ?? [] as $row) {
                $program->items()->create([
                    'exercise_name'   => $row['exercise_name'],
                    'duration_minutes'=> $row['duration_minutes'] ?? null,
                    'intensity_level' => $row['intensity_level'] ?? null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Program created successfully',
            'data' => $program->load('items')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program, Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 10);

        $itemsQuery = $program->items()->orderBy('program_item_id');

        if ($q) {
            $itemsQuery->where('exercise_name', 'like', "%{$q}%");
        }

        $items = $itemsQuery->paginate($perPage);

        return response()->json([
            'program' => $program,
            'items' => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'nullable|string|max:50',
            'difficulty'       => 'nullable|string|max:50',
        ]);

        $program->update([
            'name'             => $data['name'],
            'type'             => $data['type'] ?? null,
            'difficulty'       => $data['difficulty'] ?? null,
        ]);

        return response()->json([
            'message' => 'Program updated successfully',
            'data' => $program
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->items()->delete();
        $program->delete();

        return response()->json([
            'message' => 'Program deleted successfully'
        ]);
    }
}

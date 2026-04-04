<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramItem;
use Illuminate\Http\Request;

class ItemsLatihanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Program $program)
    {
        $data = $request->validate([
            'exercise_name'   => 'required|string|max:255',
            'duration_minutes'=> 'nullable|integer|min:1',
            'intensity_level' => 'nullable|string|max:50',
        ]);

        $item = $program->items()->create($data);

        return response()->json([
            'message' => 'Program item created successfully',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramItem $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramItem $item)
    {
        $data = $request->validate([
            'exercise_name'   => 'required|string|max:255',
            'duration_minutes'=> 'nullable|integer|min:1',
            'intensity_level' => 'nullable|string|max:50',
        ]);

        $item->update($data);

        return response()->json([
            'message' => 'Program item updated successfully',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramItem $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Program item deleted successfully'
        ]);
    }
}

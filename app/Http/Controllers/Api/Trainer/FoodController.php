<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 10);

        $foods = Food::query()
            ->when($q, fn($qq) => $qq->where('name', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json($foods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:foods,name'],
            'grammage' => ['required', 'numeric', 'gte:0'],
            'calories_per_portion' => ['required', 'numeric', 'gte:0'],
            'total_fat' => ['required', 'numeric', 'gte:0'],
            'total_carbo' => ['required', 'numeric', 'gte:0'],
            'total_protein' => ['required', 'numeric', 'gte:0'],
        ]);

        $food = Food::create($data);

        return response()->json([
            'message' => 'Food created successfully',
            'data' => $food
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        return response()->json($food);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('foods', 'name')->ignore($food->food_id, 'food_id'),
            ],
            'grammage' => ['required', 'numeric', 'gte:0'],
            'calories_per_portion' => ['required', 'numeric', 'gte:0'],
            'total_fat' => ['required', 'numeric', 'gte:0'],
            'total_carbo' => ['required', 'numeric', 'gte:0'],
            'total_protein' => ['required', 'numeric', 'gte:0'],
        ]);

        $food->update($data);

        return response()->json([
            'message' => 'Food updated successfully',
            'data' => $food
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        $food->delete();

        return response()->json([
            'message' => 'Food deleted successfully'
        ]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 10);

        $foods = Food::query()
            ->when($q, fn($qq) => $qq->where('name','like',"%$q%"))
            ->orderBy('name')
            ->paginate($perPage);

        return view('kelola-makanan', compact('foods'));
    }

    public function create()
    {
        return view('add-makanan');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'grammage' => 'required|numeric|min:0',
            'calories_per_portion' => 'required|numeric|min:0',
            'total_fat' => 'required|numeric|min:0',
            'total_carbo' => 'required|numeric|min:0',
            'total_protein' => 'required|numeric|min:0',
        ]);
        Food::create($data);
        return redirect()->route('trainer.foods.index')->with('ok','Item ditambahkan');
    }

    public function edit(Food $food)
    {
        return view('edit-makanan', compact('food'));
    }

    public function update(Request $request, Food $food)
    {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'grammage' => 'required|numeric|min:0',
            'calories_per_portion' => 'required|numeric|min:0',
            'total_fat' => 'required|numeric|min:0',
            'total_carbo' => 'required|numeric|min:0',
            'total_protein' => 'required|numeric|min:0',
        ]);
        $food->update($data);
        return redirect()->route('trainer.foods.index')->with('ok','Item diupdate');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return back()->with('ok','Item dihapus');
    }
}


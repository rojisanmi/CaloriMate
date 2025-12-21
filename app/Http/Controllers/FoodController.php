<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class FoodController extends Controller
{
    // Halaman kelola makanan untuk trainer
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 10);

        $foods = Food::query()
            ->when($q, fn($qq) => $qq->where('name', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate($perPage);

        return view('kelola-makanan', compact('foods'));
    }

    // Halaman tambah makanan baru
    public function create()
    {
        return view('add-makanan');
    }

    // Proses penyimpanan makanan baru
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:100', 'unique:foods,name'],
                'grammage' => ['required', 'numeric', 'gte:0'],
                'calories_per_portion' => ['required', 'numeric', 'gte:0'],
                'total_fat' => ['required', 'numeric', 'gte:0'],
                'total_carbo' => ['required', 'numeric', 'gte:0'],
                'total_protein' => ['required', 'numeric', 'gte:0'],
            ],
            [
                'name.unique' => 'Nama makanan tersebut sudah ada.',
                'grammage.gte' => 'Gramasi tidak boleh bernilai negatif.',
                'calories_per_portion.gte' => 'Kalori per porsi tidak boleh bernilai negatif.',
                'total_fat.gte' => 'Lemak tidak boleh bernilai negatif.',
                'total_carbo.gte' => 'Karbo tidak boleh bernilai negatif.',
                'total_protein.gte' => 'Protein tidak boleh bernilai negatif.',
            ]
        );

        Food::create($data);

        return redirect()
            ->route('trainer.foods.index')
            ->with('ok', 'Item ditambahkan');
    }

    // Halaman edit makanan
    public function edit(Food $food)
    {
        return view('edit-makanan', compact('food'));
    }
    // Proses update makanan
    public function update(Request $request, Food $food)
    {
        $data = $request->validate(
            [
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
            ],
            [
                'name.unique' => 'Nama makanan tersebut sudah ada.',
                'grammage.gte' => 'Gramasi tidak boleh bernilai negatif.',
                'calories_per_portion.gte' => 'Kalori per porsi tidak boleh bernilai negatif.',
                'total_fat.gte' => 'Lemak tidak boleh bernilai negatif.',
                'total_carbo.gte' => 'Karbo tidak boleh bernilai negatif.',
                'total_protein.gte' => 'Protein tidak boleh bernilai negatif.',
            ]
        );

        $food->update($data);

        return redirect()
            ->route('trainer.foods.index')
            ->with('ok', 'Item diupdate');
    }

    // Proses hapus makanan
    public function destroy(Food $food)
    {
        $food->delete();
        return back()->with('ok', 'Item dihapus');
    }
}

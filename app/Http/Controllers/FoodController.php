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
                'name'                 => ['required', 'string', 'max:100', 'unique:foods,name'],
                'grammage'             => ['required', 'numeric', 'min:0', 'max:2000'],
                'calories_per_portion' => ['required', 'numeric', 'min:0', 'max:5000'],
                'total_fat'            => ['required', 'numeric', 'min:0', 'max:500'],
                'total_carbo'          => ['required', 'numeric', 'min:0', 'max:500'],
                'total_protein'        => ['required', 'numeric', 'min:0', 'max:500'],
            ],
            [
                'name.unique'                  => 'Nama makanan tersebut sudah ada.',
                'grammage.min'                 => 'Gramasi tidak boleh negatif.',
                'grammage.max'                 => 'Gramasi maksimal 2000 g.',
                'calories_per_portion.min'     => 'Kalori per porsi tidak boleh negatif.',
                'calories_per_portion.max'     => 'Kalori per porsi maksimal 5000 kkal.',
                'total_fat.min'                => 'Lemak tidak boleh negatif.',
                'total_fat.max'                => 'Lemak maksimal 500 g.',
                'total_carbo.min'              => 'Karbo tidak boleh negatif.',
                'total_carbo.max'              => 'Karbo maksimal 500 g.',
                'total_protein.min'            => 'Protein tidak boleh negatif.',
                'total_protein.max'            => 'Protein maksimal 500 g.',
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
                    'required', 'string', 'max:100',
                    Rule::unique('foods', 'name')->ignore($food->food_id, 'food_id'),
                ],
                'grammage'             => ['required', 'numeric', 'min:0', 'max:2000'],
                'calories_per_portion' => ['required', 'numeric', 'min:0', 'max:5000'],
                'total_fat'            => ['required', 'numeric', 'min:0', 'max:500'],
                'total_carbo'          => ['required', 'numeric', 'min:0', 'max:500'],
                'total_protein'        => ['required', 'numeric', 'min:0', 'max:500'],
            ],
            [
                'name.unique'              => 'Nama makanan tersebut sudah ada.',
                'grammage.min'             => 'Gramasi tidak boleh negatif.',
                'grammage.max'             => 'Gramasi maksimal 2000 g.',
                'calories_per_portion.min' => 'Kalori per porsi tidak boleh negatif.',
                'calories_per_portion.max' => 'Kalori per porsi maksimal 5000 kkal.',
                'total_fat.min'            => 'Lemak tidak boleh negatif.',
                'total_fat.max'            => 'Lemak maksimal 500 g.',
                'total_carbo.min'          => 'Karbo tidak boleh negatif.',
                'total_carbo.max'          => 'Karbo maksimal 500 g.',
                'total_protein.min'        => 'Protein tidak boleh negatif.',
                'total_protein.max'        => 'Protein maksimal 500 g.',
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

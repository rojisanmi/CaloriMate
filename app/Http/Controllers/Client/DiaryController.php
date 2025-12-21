<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Food;
use App\Models\FoodConsumption;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiaryController extends Controller
{
    // Halaman utama diary client
    public function index()
    {
        // get username dari session
        $username = Session::get('user_id');
        $client = Client::where('username', $username)->first();

        // get history hari ini
        $todayHistory = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        // hitung kalori yang sudah dikonsumsi hari ini
        $consumedCalories = 0;
        if ($todayHistory) {
            $consumedCalories = $todayHistory->foodConsumptions()
                ->with('food')
                ->get()
                ->sum(fn($c) => $c->food->calories_per_portion * $c->portions);
        }

        // target kalori harian
        $dailyCaloriesTarget = $client ? $client->calculateDailyCalories() : 2000;
        // hitung sisa kalori
        $remainingCalories = max(0, $dailyCaloriesTarget - $consumedCalories);

        return view('diary-client', compact('remainingCalories', 'consumedCalories'));
    }

    // halaman tambah makanan ke diary
    public function showAddFood(string $category)
    {
        $validCategories = ['breakfast', 'lunch', 'dinner', 'snack'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        $foods = Food::orderBy('name')->get();

        $username = Session::get('user_id');
        // get history hari ini
        $history = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        // get konsumsi makanan pada kategori ini hari ini
        $consumptions = [];
        if ($history) {
            $consumptions = FoodConsumption::with('food')
                ->where('history_id', $history->history_id)
                ->where('category', $category)
                ->get();
        }

        return view('diary-add-food', compact(
            'category',
            'foods',
            'consumptions'
        ));
    }

    // proses tambah makanan ke diary
    public function storeFood(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'food_id' => ['required', 'integer', 'exists:foods,food_id'],
            'portions' => ['required', 'integer', 'min:1'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ]);

        $username = Session::get('user_id');

        // get atau buat history hari ini
        $history = History::firstOrCreate(
            ['username' => $username, 'date' => today()],
            ['calori_in' => 0, 'calori_out' => 0]
        );

        // cek apakah makanan sudah ada di kategori ini
        $existing = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();
        // jika ada, update porsi, jika tidak buat baru
        if ($existing) {
            $existing->portions += $data['portions'];
            $existing->save();
        } else {
            FoodConsumption::create([
                'history_id' => $history->history_id,
                'food_id' => $data['food_id'],
                'portions' => $data['portions'],
                'category' => $data['category'],
            ]);
        }
        // update kalori masuk di history
        $food = Food::find($data['food_id']);
        $history->calori_in += $food->calories_per_portion * $data['portions'];
        $history->save();

        return redirect()
            ->route('client.diary')
            ->with('ok', 'Makanan berhasil ditambahkan');
    }

    // proses hapus makanan dari diary
    public function removeFood(Request $request)
    {
        $data = $request->validate([
            'food_id' => ['required', 'integer'],
            'category' => ['required', 'in:breakfast,lunch,dinner,snack'],
        ]);

        $username = Session::get('user_id');

        $history = History::where('username', $username)
            ->whereDate('date', today())
            ->first();

        if (!$history) {
            return back()->with('error', 'History tidak ditemukan');
        }
        // cari konsumsi makanan
        $consumption = FoodConsumption::where('history_id', $history->history_id)
            ->where('food_id', $data['food_id'])
            ->where('category', $data['category'])
            ->first();
        // jika ada, hapus konsumsi makanan dan update kalori masuk
        if ($consumption) {
            $food = Food::find($data['food_id']);
            $history->calori_in -= $food->calories_per_portion * $consumption->portions;
            $history->calori_in = max(0, $history->calori_in);
            $history->save();

            $consumption->delete();
        }

        return back()->with('ok', 'Makanan berhasil dihapus');
    }
}

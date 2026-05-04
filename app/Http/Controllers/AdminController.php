<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\FoodConsumption;
use App\Models\History;
use App\Models\Program;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function home()
    {
        $stats = [
            'total_clients'  => User::where('role', 1)->count(),
            'total_trainers' => User::where('role', 2)->count(),
            'total_foods'    => Food::count(),
            'total_programs' => Program::count(),
            'active_today'   => History::whereDate('date', today())
                                    ->distinct('username')
                                    ->count('username'),
        ];

        $topFoods = FoodConsumption::select('food_id', DB::raw('SUM(portions) as total_portions'))
            ->groupBy('food_id')
            ->orderByDesc('total_portions')
            ->with('food')
            ->limit(5)
            ->get();

        $topPrograms = History::select('program_id', DB::raw('count(*) as total'))
            ->whereNotNull('program_id')
            ->groupBy('program_id')
            ->orderByDesc('total')
            ->with('program')
            ->limit(3)
            ->get();

        return view('admin.home', compact('stats', 'topFoods', 'topPrograms'));
    }

    public function indexTrainers(Request $request)
    {
        $search  = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $trainers = Trainer::with('user')
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%"))
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.trainers.index', compact('trainers', 'search', 'perPage'));
    }

    public function createTrainer()
    {
        return view('admin.trainers.create');
    }

    public function storeTrainer(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:user,username',
            'email'    => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8',
            'nama'     => 'required|string|max:100',
            'keahlian' => 'nullable|string|max:255',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique'    => 'Email sudah digunakan.',
            'password.min'    => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 2,
            ]);

            Trainer::create([
                'username' => $request->username,
                'nama'     => $request->nama,
                'keahlian' => $request->keahlian,
            ]);
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "Trainer {$request->nama} berhasil ditambahkan.");
    }

    public function editTrainer(string $username)
    {
        $trainer = Trainer::with('user')->where('username', $username)->firstOrFail();
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function updateTrainer(Request $request, string $username)
    {
        $trainer = Trainer::where('username', $username)->firstOrFail();

        $request->validate([
            'nama'     => 'required|string|max:100',
            'keahlian' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($request, $trainer) {
            $trainer->update([
                'nama'     => $request->nama,
                'keahlian' => $request->keahlian,
            ]);

            if ($request->filled('password')) {
                $trainer->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "Data trainer {$trainer->nama} berhasil diperbarui.");
    }

    public function destroyTrainer(string $username)
    {
        $trainer = Trainer::where('username', $username)->firstOrFail();
        $nama    = $trainer->nama;

        DB::transaction(function () use ($trainer) {
            $trainer->delete();
            User::where('username', $trainer->username)->delete();
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "Trainer {$nama} berhasil dihapus.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    // Menampilkan daftar program dengan pagination dan pencarian
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 12);

        $programs = Program::query()
            ->when($q, fn($b) => $b->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return view('kelola-program', compact('programs'));
    }

    // Menampilkan form untuk membuat program baru
    public function create()
    {
        return view('buat-program');
    }

    // Menyimpan program baru ke dalam storage
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'nullable|string|max:50',
            'difficulty'       => 'nullable|string|max:50',
            'duration_minutes' => 'nullable|integer|min:0',

            // bisa sekaligus menambah item latihan saat buat program
            'items'                    => 'nullable|array',
            'items.*.exercise_name'    => 'required_with:items|string|max:255',
            'items.*.duration_minutes' => 'nullable|integer|min:0',
            'items.*.intensity_level'  => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($data) {
            $program = Program::create([
                'name'             => $data['name'],
                'type'             => $data['type'] ?? null,
                'difficulty'       => $data['difficulty'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
            ]);

            foreach ($data['items'] ?? [] as $row) {
                $program->items()->create([
                    'exercise_name'   => $row['exercise_name'],
                    'duration_minutes'=> $row['duration_minutes'] ?? null,
                    'intensity_level' => $row['intensity_level'] ?? null,
                ]);
            }
        });

        return redirect()->route('trainer.programs.index')->with('ok', 'Program dibuat');
    }

    // Menampilkan detail program
    public function show(Program $program, Request $request)
    {
        $q = $request->string('q')->toString();
        $perPage = (int) $request->integer('per_page', 10);

        $itemsQuery = $program->items()->orderBy('program_item_id');

        if ($q) {
            $itemsQuery->where('exercise_name', 'like', "%{$q}%");
        }

        $items = $itemsQuery->paginate($perPage)->withQueryString();

        return view('items-latihan', compact('program', 'items'));
    }

    // Menampilkan form edit program
    public function edit(Program $program)
    {
        return view('edit-programs-latihan', compact('program'));
    }

    // Memperbarui program yang ada
    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'nullable|string|max:50',
            'difficulty'       => 'nullable|string|max:50',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $program->update([
            'name'             => $data['name'],
            'type'             => $data['type'] ?? null,
            'difficulty'       => $data['difficulty'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
        ]);

        return redirect()->route('trainer.programs.index')->with('ok', 'Program diperbarui');
    }

    // Menghapus program
    public function destroy(Program $program)
    {
        $program->items()->delete();

        $program->delete();

        return redirect()->route('trainer.programs.index')->with('ok', 'Program dihapus');
    }
}

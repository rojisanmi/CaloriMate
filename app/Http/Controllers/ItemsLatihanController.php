<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramItem;
use Illuminate\Http\Request;

class ItemsLatihanController extends Controller
{
    // redirect ke halaman show program
    public function index(Program $program, Request $request)
    {
        return redirect()->route('trainer.programs.show', $program);
    }

    // menampilkan form create new item
    public function create(Program $program)
    {
        return view('create-items-latihan', compact('program'));
    }

    // menyimpan item baru
    public function store(Program $program, Request $request)
    {
        $data = $request->validate([
            'exercise_name'   => 'required|string|max:255',
            'duration_minutes'=> 'nullable|integer|min:0',
            'intensity_level' => 'nullable|string|max:50',
        ]);

        $program->items()->create($data);

        return redirect()->route('trainer.programs.show', $program)
                         ->with('ok', 'Item latihan ditambahkan.');
    }

    // menampilkan form edit item
    public function edit(ProgramItem $item)
    {
        $program = $item->program;
        return view('edit-items-latihan', compact('program', 'item'));
    }

    // memperbarui item
    public function update(Request $request, ProgramItem $item)
    {
        $data = $request->validate([
            'exercise_name'   => 'required|string|max:255',
            'duration_minutes'=> 'nullable|integer|min:0',
            'intensity_level' => 'nullable|string|max:50',
        ]);

        $item->update($data);

        return redirect()->route('trainer.programs.show', $item->program)
                         ->with('ok', 'Item latihan diperbarui.');
    }

    // menghapus item
    public function destroy(ProgramItem $item)
    {
        $program = $item->program;
        $item->delete();

        return redirect()->route('trainer.programs.show', $program)
                         ->with('ok', 'Item latihan dihapus.');
    }
}

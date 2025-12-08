<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramItem;
use Illuminate\Http\Request;

class ItemsLatihanController extends Controller
{
    /**
     * index: redirect ke program show (single source of truth)
     */
    public function index(Program $program, Request $request)
    {
        return redirect()->route('trainer.programs.show', $program);
    }

    /**
     * show create form
     * menggunakan view: resources/views/create-items-latihan.blade.php
     */
    public function create(Program $program)
    {
        return view('create-items-latihan', compact('program'));
    }

    /**
     * store new item
     */
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

    /**
     * edit form for an item
     * menggunakan view: resources/views/edit-items-latihan.blade.php
     * Note: signature expects ProgramItem $item because routes are shallow for edit/update
     */
    public function edit(ProgramItem $item)
    {
        $program = $item->program;
        return view('edit-items-latihan', compact('program', 'item'));
    }

    /**
     * update item
     */
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

    /**
     * destroy item
     */
    public function destroy(ProgramItem $item)
    {
        $program = $item->program;
        $item->delete();

        return redirect()->route('trainer.programs.show', $program)
                         ->with('ok', 'Item latihan dihapus.');
    }
}

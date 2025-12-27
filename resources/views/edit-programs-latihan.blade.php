@extends('layouts.verivied')

@section('title', 'Edit Program: ' . $program->name)

@section('content')
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-[#2E471F]">Edit Program</h1>
    <p class="text-sm text-gray-600">Ubah nama, tipe, tingkat, dan durasi program.</p>
  </div>

  <div class="bg-white p-6 rounded-lg shadow">
    {{-- FORM UTAMA UNTUK UPDATE --}}
    <form action="{{ route('trainer.programs.update', $program) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        {{-- Nama --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Program</label>
          <input type="text" name="name" value="{{ old('name', $program->name) }}"
                 class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 focus:border-[#2E471F] focus:outline-none"
                 required>
          @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tipe --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Tipe</label>
          <input type="text" name="type" value="{{ old('type', $program->type) }}"
                 placeholder="mis. HIIT, Strength, Cardio"
                 class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 focus:border-[#2E471F] focus:outline-none">
          @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tingkat --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Tingkat</label>
          <select name="difficulty"
                  class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 focus:border-[#2E471F] focus:outline-none">
            <option value="">Pilih</option>
            <option value="Beginner" @selected(old('difficulty', $program->difficulty) === 'Beginner')>Beginner</option>
            <option value="Intermediate" @selected(old('difficulty', $program->difficulty) === 'Intermediate')>Intermediate</option>
            <option value="Advanced" @selected(old('difficulty', $program->difficulty) === 'Advanced')>Advanced</option>
          </select>
          @error('difficulty') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Aksi --}}
      <div class="pt-4 border-t flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
          Simpan Perubahan
        </button>

        <a href="{{ url('/trainer/programs') }}"
           class="text-sm px-3 py-2 rounded-md border bg-white hover:bg-gray-50">
          Batal
        </a>
      </div>
    </form>

    {{-- FORM TERPISAH UNTUK HAPUS --}}
    <div class="flex justify-end mt-4">
      <form action="{{ route('trainer.programs.destroy', $program) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus program ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold bg-red-600 text-white hover:bg-red-700">
          Hapus Program
        </button>
      </form>
    </div>
  </div>
@endsection

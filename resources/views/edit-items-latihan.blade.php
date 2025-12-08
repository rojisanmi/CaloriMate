@extends('layouts.verivied')

@section('title', 'Edit Item Latihan')

@section('content')
  <div class="mb-6">
    <h1 class="text-4xl font-extrabold text-[#2E471F]">Edit Item Latihan</h1>
    <p class="text-sm text-gray-600 mt-2">
      Program: <span class="font-semibold text-gray-800">{{ $program->name }}</span>
    </p>
  </div>
  
    <div class="bg-white rounded-lg shadow-md p-6 w-full">
      {{-- validation errors --}}
      @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-4">
          <p class="text-sm font-semibold text-red-700">Terdapat kesalahan pada input:</p>
          <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('trainer.items.update', $item) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
          <label for="exercise_name" class="block mb-2 text-sm font-medium text-gray-700">Nama Latihan <span class="text-red-500">*</span></label>
          <input
            id="exercise_name"
            name="exercise_name"
            type="text"
            value="{{ old('exercise_name', $item->exercise_name) }}"
            required
            class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F] focus:ring-opacity-20 focus:outline-none @error('exercise_name') border-red-500 @enderror"
            placeholder="Contoh: Push Up, Squat, Plank">
        </div>

        {{-- Duration --}}
        <div>
          <label for="duration_minutes" class="block mb-2 text-sm font-medium text-gray-700">Durasi (menit)</label>
          <input
            id="duration_minutes"
            name="duration_minutes"
            type="number"
            min="0"
            value="{{ old('duration_minutes', $item->duration_minutes) }}"
            class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F] focus:ring-opacity-20 focus:outline-none @error('duration_minutes') border-red-500 @enderror"
            placeholder="Mis: 10">
        </div>

        {{-- Intensity --}}
        <div>
          <label for="intensity_level" class="block mb-2 text-sm font-medium text-gray-700">Tingkat Intensitas</label>
          <select id="intensity_level" name="intensity_level"
                  class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-700 bg-white focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F] focus:ring-opacity-20 focus:outline-none @error('intensity_level') border-red-500 @enderror">
            <option value="">-- Pilih Intensitas --</option>
            <option value="Rendah" @selected(old('intensity_level', $item->intensity_level) === 'Rendah')>Rendah</option>
            <option value="Sedang"  @selected(old('intensity_level', $item->intensity_level) === 'Sedang')>Sedang</option>
            <option value="Tinggi"  @selected(old('intensity_level', $item->intensity_level) === 'Tinggi')>Tinggi</option>
          </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-[#2E7D32] px-6 py-2.5 text-white font-semibold shadow hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-[#2E7D32] focus:ring-opacity-20">
              Simpan Perubahan
            </button>

            <a href="{{ route('trainer.programs.show', $program) }}"
              class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
              Batal
            </a>
          </div>

          {{-- optional: quick delete (if you want) --}}
          {{-- <form action="{{ route('trainer.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-white text-sm font-medium hover:bg-red-700">Hapus</button>
          </form> --}}
        </div>
      </form>
    </div>
@endsection

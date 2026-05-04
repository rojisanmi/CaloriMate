@extends('layouts.verivied')

@section('title', 'Edit Item Latihan')

@section('content')

<div class="max-w-xl mx-auto cm-fadein">

  {{-- HEADER --}}
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ route('trainer.programs.show', $program) }}"
       class="h-9 w-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center
              text-gray-500 hover:text-[#2E471F] hover:border-[#2E471F] transition-all duration-150">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </a>
    <div>
      <h1 class="font-raleway text-xl font-bold text-[#2E471F]">Edit Item Latihan</h1>
      <p class="text-xs text-gray-400">Program: {{ $program->name }}</p>
    </div>
  </div>

  {{-- ERROR SUMMARY --}}
  @if($errors->any())
    <div class="mb-4 flex items-start gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200">
      <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
      </svg>
      <ul class="text-red-600 text-sm list-disc list-inside">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm p-6">
    <form method="POST" action="{{ route('trainer.items.update', $item) }}" class="space-y-5">
      @csrf @method('PUT')

      <div>
        <label for="exercise_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
          Nama Latihan <span class="text-red-400">*</span>
        </label>
        <input id="exercise_name" name="exercise_name" type="text"
               value="{{ old('exercise_name', $item->exercise_name) }}" required
               placeholder="Contoh: Push Up, Squat, Plank"
               class="w-full rounded-xl border {{ $errors->has('exercise_name') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                      px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                      transition-all duration-150">
        @error('exercise_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Durasi (menit)</label>
        <input id="duration_minutes" name="duration_minutes" type="number" min="0"
               value="{{ old('duration_minutes', $item->duration_minutes) }}" placeholder="mis. 10"
               class="w-full rounded-xl border {{ $errors->has('duration_minutes') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                      px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                      transition-all duration-150">
        @error('duration_minutes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="intensity_level" class="block text-sm font-semibold text-gray-700 mb-1.5">Tingkat Intensitas</label>
        <select id="intensity_level" name="intensity_level"
                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 text-sm bg-white
                       focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                       transition-all duration-150">
          <option value="">Pilih intensitas</option>
          <option value="Rendah" @selected(old('intensity_level', $item->intensity_level) === 'Rendah')>Rendah</option>
          <option value="Sedang"  @selected(old('intensity_level', $item->intensity_level) === 'Sedang')>Sedang</option>
          <option value="Tinggi"  @selected(old('intensity_level', $item->intensity_level) === 'Tinggi')>Tinggi</option>
        </select>
        @error('intensity_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                       shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
          Simpan Perubahan
        </button>
        <a href="{{ route('trainer.programs.show', $program) }}"
           class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
                  hover:bg-gray-50 transition-colors duration-150">
          Batal
        </a>
      </div>

    </form>
  </div>

</div>

@endsection

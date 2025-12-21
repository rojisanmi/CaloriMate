@extends('layouts.verivied')

@section('title', 'Tambah Makanan')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Tambah Makanan</h1>

    <a href="{{ route('trainer.foods.index') }}"
       class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-gray-700 font-semibold hover:bg-gray-300">
      Kembali
    </a>
  </div>


  <div class="bg-white shadow-md rounded-lg p-6">
    <form method="POST" action="{{ route('trainer.foods.store') }}" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
        <input name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Gramasi (g)</label>
          <input type="number" step="0.01" min="0" name="grammage" value="{{ old('grammage') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('grammage') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Kalori / Porsi</label>
          <input type="number" step="0.01" min="0" name="calories_per_portion" value="{{ old('calories_per_portion') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('calories_per_portion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Lemak (g)</label>
          <input type="number" step="0.01" min="0" name="total_fat" value="{{ old('total_fat') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('total_fat') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Karbo (g)</label>
          <input type="number" step="0.01" min="0" name="total_carbo" value="{{ old('total_carbo') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('total_carbo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Protein (g)</label>
          <input type="number" step="0.01" min="0" name="total_protein" value="{{ old('total_protein') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('total_protein') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="flex gap-3">
        <button type="submit"
                class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
          Simpan
        </button>
        <a href="{{ route('trainer.foods.index') }}"
           class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-gray-700 font-semibold bg-white hover:bg-gray-50">
          Batal
        </a>
      </div>
    </form>
  </div>
@endsection

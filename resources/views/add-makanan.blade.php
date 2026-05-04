@extends('layouts.verivied')

@section('title', 'Tambah Makanan')

@section('content')

<div class="max-w-2xl mx-auto cm-fadein">

  {{-- HEADER --}}
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ route('trainer.foods.index') }}"
       class="h-9 w-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center
              text-gray-500 hover:text-[#2E471F] hover:border-[#2E471F] transition-all duration-150">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </a>
    <div>
      <h1 class="font-raleway text-xl font-bold text-[#2E471F]">Tambah Makanan</h1>
      <p class="text-xs text-gray-400">Isi data nutrisi makanan baru</p>
    </div>
  </div>

  {{-- FORM CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6">
    <form method="POST" action="{{ route('trainer.foods.store') }}" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Makanan</label>
        <input name="name" value="{{ old('name') }}" required placeholder="mis. Nasi Putih, Ayam Bakar..."
               class="w-full rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                      px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                      transition-all duration-150">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Gramasi (g)</label>
          <input type="number" step="0.01" min="0" max="2000" name="grammage" value="{{ old('grammage') }}" required
                 class="w-full rounded-xl border {{ $errors->has('grammage') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                        px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                        transition-all duration-150">
          @error('grammage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kalori / Porsi</label>
          <input type="number" step="0.01" min="0" max="5000" name="calories_per_portion" value="{{ old('calories_per_portion') }}" required
                 class="w-full rounded-xl border {{ $errors->has('calories_per_portion') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                        px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                        transition-all duration-150">
          @error('calories_per_portion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lemak (g)</label>
          <input type="number" step="0.01" min="0" max="500" name="total_fat" value="{{ old('total_fat') }}" required
                 class="w-full rounded-xl border {{ $errors->has('total_fat') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                        px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                        transition-all duration-150">
          @error('total_fat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Karbo (g)</label>
          <input type="number" step="0.01" min="0" max="500" name="total_carbo" value="{{ old('total_carbo') }}" required
                 class="w-full rounded-xl border {{ $errors->has('total_carbo') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                        px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                        transition-all duration-150">
          @error('total_carbo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Protein (g)</label>
          <input type="number" step="0.01" min="0" max="500" name="total_protein" value="{{ old('total_protein') }}" required
                 class="w-full rounded-xl border {{ $errors->has('total_protein') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}
                        px-4 py-2.5 text-gray-800 text-sm focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                        transition-all duration-150">
          @error('total_protein') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                       shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
          Simpan
        </button>
        <a href="{{ route('trainer.foods.index') }}"
           class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
                  hover:bg-gray-50 transition-colors duration-150">
          Batal
        </a>
      </div>

    </form>
  </div>

</div>

@endsection

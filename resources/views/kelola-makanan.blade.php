@extends('layouts.verivied')

@section('title', 'Kelola Makanan')

@section('content')

{{-- FLASH --}}
@if(session('ok'))
<div id="flash-msg"
     class="fixed top-20 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-[#2E471F] text-white shadow-lg cm-fadein max-w-sm">
  <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24"
       stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
  </svg>
  <span class="text-sm font-medium flex-1">{{ session('ok') }}</span>
  <button onclick="document.getElementById('flash-msg').remove()"
          class="text-white/50 hover:text-white transition-colors">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
  </button>
</div>
<script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
@endif

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 cm-fadein">
  <div>
    <h1 class="font-raleway text-2xl font-bold text-[#2E471F]">Kelola Makanan</h1>
    <p class="text-sm text-gray-400 mt-0.5">Data nutrisi makanan untuk diary klien</p>
  </div>
  <a href="{{ route('trainer.foods.create') }}"
     class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
            shadow hover:bg-[#3d6628] hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 self-start sm:self-auto">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Tambah Item
  </a>
</div>

{{-- FILTER BAR --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5 cm-fadein cm-delay-1">
  <form method="GET" action="{{ route('trainer.foods.index') }}" class="flex-1">
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
      </svg>
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari makanan..."
             class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-700
                    placeholder-gray-400 focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                    transition-all duration-150">
    </div>
  </form>

  <form method="GET" action="{{ route('trainer.foods.index') }}" class="flex items-center gap-2">
    <input type="hidden" name="q" value="{{ request('q') }}">
    <label class="text-sm text-gray-500 whitespace-nowrap">Per halaman</label>
    <select name="per_page" onchange="this.form.submit()"
            class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                   focus:border-[#2E471F] focus:outline-none transition-colors duration-150">
      @foreach([10,25,50,100] as $n)
        <option value="{{ $n }}" @selected((int)request('per_page', 10) === $n)>{{ $n }}</option>
      @endforeach
    </select>
  </form>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden cm-fadein cm-delay-2">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#EFE6D2]/60 border-b border-gray-100">
        <tr>
          <th class="px-5 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Nama</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Gramasi</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Kalori</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Lemak</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Karbo</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Protein</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($foods as $food)
          <tr class="hover:bg-gray-50 transition-colors duration-100">
            <td class="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">{{ $food->name }}</td>
            <td class="px-5 py-3.5 text-right text-gray-500">{{ $food->grammage }}g</td>
            <td class="px-5 py-3.5 text-right font-semibold text-[#2E471F]">{{ $food->calories_per_portion }}</td>
            <td class="px-5 py-3.5 text-right text-gray-500">{{ $food->total_fat }}g</td>
            <td class="px-5 py-3.5 text-right text-gray-500">{{ $food->total_carbo }}g</td>
            <td class="px-5 py-3.5 text-right text-gray-500">{{ $food->total_protein }}g</td>
            <td class="px-5 py-3.5">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.foods.edit', $food) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#2E471F] text-white
                          hover:bg-[#3d6628] transition-colors duration-150">
                  Edit
                </a>
                <form method="POST" action="{{ route('trainer.foods.destroy', $food) }}"
                      id="del-food-{{ $food->food_id }}">
                  @csrf @method('DELETE')
                  <button type="button"
                          onclick="showDeleteModal(document.getElementById('del-food-{{ $food->food_id }}'), 'Hapus makanan \'{{ addslashes($food->name) }}\'? Data tidak dapat dikembalikan.')"
                          class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-500 text-white
                                 hover:bg-red-600 transition-colors duration-150">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
              Data tidak ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- PAGINATION --}}
  <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 bg-white">
    <p class="text-xs text-gray-400">
      @if(method_exists($foods, 'total'))
        Menampilkan {{ $foods->firstItem() ?? 0 }}–{{ $foods->lastItem() ?? 0 }} dari {{ $foods->total() }}
      @endif
    </p>
    <div>
      {{ $foods->appends(['q' => request('q'), 'per_page' => request('per_page')])->links() }}
    </div>
  </div>
</div>

@endsection

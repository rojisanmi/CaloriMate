@extends('layouts.verivied')

@section('title', 'Detail: ' . $program->name)

@section('content')

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6 cm-fadein">
  <div>
    <div class="flex items-center gap-2 mb-1">
      <a href="{{ route('trainer.programs.index') }}"
         class="text-xs text-gray-400 hover:text-[#2E471F] transition-colors">
        Program Latihan
      </a>
      <span class="text-gray-300">/</span>
      <span class="text-xs text-gray-600 font-medium">{{ $program->name }}</span>
    </div>
    <h1 class="font-raleway text-2xl font-bold text-[#2E471F]">{{ $program->name }}</h1>
    <p class="text-sm text-gray-400 mt-0.5">
      @if($program->type) {{ $program->type }} &middot; @endif
      @if($program->difficulty) {{ $program->difficulty }} &middot; @endif
      {{ $program->total_duration > 0 ? $program->total_duration . ' menit' : 'Durasi belum diatur' }}
    </p>
  </div>
  <a href="{{ route('trainer.programs.items.create', $program) }}"
     class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
            shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200 self-start">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Tambah Item
  </a>
</div>

{{-- FILTER --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5 cm-fadein cm-delay-1">
  <form method="GET" action="{{ route('trainer.programs.show', $program) }}" class="flex-1 max-w-sm">
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
      </svg>
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari item latihan..."
             class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-700
                    placeholder-gray-400 focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                    transition-all duration-150">
    </div>
  </form>

  <form method="GET" action="{{ route('trainer.programs.show', $program) }}" class="flex items-center gap-2">
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
          <th class="px-5 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide w-12">No</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Nama Latihan</th>
          <th class="px-5 py-3 text-center text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Durasi</th>
          <th class="px-5 py-3 text-center text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Intensitas</th>
          <th class="px-5 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($items as $idx => $item)
          <tr class="hover:bg-gray-50 transition-colors duration-100">
            <td class="px-5 py-3.5 text-gray-400">{{ $items->firstItem() + $idx }}</td>
            <td class="px-5 py-3.5 font-medium text-gray-800">{{ $item->exercise_name }}</td>
            <td class="px-5 py-3.5 text-center text-gray-500">
              {{ $item->duration_minutes ? $item->duration_minutes . ' mnt' : '-' }}
            </td>
            <td class="px-5 py-3.5 text-center">
              @php
                $badge = match($item->intensity_level) {
                  'Rendah' => 'bg-blue-100 text-blue-700',
                  'Sedang' => 'bg-yellow-100 text-yellow-700',
                  'Tinggi' => 'bg-red-100 text-red-700',
                  default  => 'bg-gray-100 text-gray-500',
                };
              @endphp
              <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                {{ $item->intensity_level ?? '-' }}
              </span>
            </td>
            <td class="px-5 py-3.5">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.items.edit', $item) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#2E471F] text-white
                          hover:bg-[#3d6628] transition-colors duration-150">
                  Edit
                </a>
                <form method="POST" action="{{ route('trainer.items.destroy', $item) }}"
                      id="del-item-{{ $item->item_id }}">
                  @csrf @method('DELETE')
                  <button type="button"
                          onclick="showDeleteModal(document.getElementById('del-item-{{ $item->item_id }}'), 'Hapus item latihan \'{{ addslashes($item->exercise_name) }}\'?')"
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
            <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
              Belum ada item latihan. Klik "Tambah Item" untuk mulai.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="flex justify-end px-5 py-3 border-t border-gray-100">
    {{ $items->appends(['q' => request('q'), 'per_page' => request('per_page')])->links() }}
  </div>
</div>

{{-- BACK --}}
<div class="mt-4 cm-fadein cm-delay-3">
  <a href="{{ route('trainer.programs.index') }}"
     class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#2E471F] transition-colors">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    Kembali ke daftar program
  </a>
</div>

@endsection

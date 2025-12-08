@extends('layouts.verivied')

@section('title', 'Detail Program: ' . $program->name)

@section('content')
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-3xl font-extrabold text-[#2E471F]">{{ $program->name }}</h1>
      <p class="text-sm text-gray-600 mt-1">
        Tipe: {{ $program->type ?? '-' }} &middot;
        Tingkat: {{ $program->difficulty ?? '-' }} &middot;
        Durasi: {{ $program->duration_minutes ? $program->duration_minutes . ' menit' : '-' }}
      </p>
    </div>
  </div>

  {{-- Search + per_page + Tambah item --}}
  <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    {{-- Form search --}}
    <form method="GET" action="{{ route('trainer.programs.show', $program) }}" class="w-full sm:max-w-sm">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari item"
             class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:outline-none">
    </form>

    {{-- per_page + tambah item --}}
    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('trainer.programs.show', $program) }}" class="flex items-center gap-2">
        <input type="hidden" name="q" value="{{ request('q') }}">
        <label for="per_page" class="text-sm text-gray-600">Items per page</label>
        <select id="per_page" name="per_page"
                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-[#2E471F] focus:outline-none"
                onchange="this.form.submit()">
          @foreach([10,25,50,100] as $n)
            <option value="{{ $n }}" @selected((int)request('per_page', 10) === $n)>{{ $n }}</option>
          @endforeach
        </select>
      </form>

      <a href="{{ route('trainer.programs.items.create', $program) }}"
         class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
        Tambah Item
      </a>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
    <table class="w-full text-sm text-left text-gray-600">
      <thead class="text-xs uppercase bg-gray-50">
        <tr>
          <th class="px-6 py-3">No</th>
          <th class="px-6 py-3">Nama Latihan</th>
          <th class="px-6 py-3">Durasi (menit)</th>
          <th class="px-6 py-3">Intensity</th>
          <th class="px-6 py-3 text-right">Aksi</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($items as $idx => $item)
          <tr class="bg-white border-b hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">{{ $items->firstItem() + $idx }}</td>
            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->exercise_name }}</td>
            <td class="px-6 py-4">{{ $item->duration_minutes ?? '-' }}</td>
            <td class="px-6 py-4">{{ $item->intensity_level ?? '-' }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.items.edit', $item) }}"
                   class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-[#2E7D32] hover:opacity-90">
                  Edit
                </a>
                <form method="POST" action="{{ route('trainer.items.destroy', $item) }}"
                      onsubmit="return confirm('Hapus item ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
              Tidak ada item latihan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- FOOTER: pagination  --}}
    <div class="flex justify-end px-4 py-3">
      <div class="inline-flex">
        {{ $items->appends(['q'=>request('q'),'per_page'=>request('per_page')])->links() }}
      </div>
    </div>
  </div>

  {{-- Tombol kembali --}}
  <div class="mt-4">
    <a href="{{ route('trainer.programs.index') }}"
       class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium border bg-white hover:bg-gray-50">
      Kembali
    </a>
  </div>
@endsection

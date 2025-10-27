@extends('layouts.verivied')

@section('title', 'Kelola Program Latihan')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Kelola Program Latihan</h1>

    <a href="{{ route('trainer.programs.create') }}"
       class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
      Tambah Program
    </a>
  </div>

  {{-- Filter bar: search --}}
  <div class="mb-4">
    <form method="GET" action="{{ route('trainer.programs.index') }}" class="w-full sm:max-w-sm">
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari program"
             class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:outline-none">
    </form>
  </div>

  {{-- TABLE --}}
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
    <table class="w-full text-sm text-left text-gray-600">
      <thead class="text-xs uppercase bg-gray-50">
        <tr>
          <th class="px-6 py-3">Nama</th>
          <th class="px-6 py-3">Tipe</th>
          <th class="px-6 py-3">Tingkat</th>
          <th class="px-6 py-3">Durasi (menit)</th>
          <th class="px-6 py-3 text-right">Aksi</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($programs as $program)
          <tr class="bg-white border-b hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
              {{ $program->name }}
            </td>
            <td class="px-6 py-4">{{ $program->type ?? '-' }}</td>
            <td class="px-6 py-4">{{ $program->difficulty ?? '-' }}</td>
            <td class="px-6 py-4">{{ $program->duration_minutes ?? '-' }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.programs.edit', $program) }}"
                   class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-[#2E7D32] hover:opacity-90">
                  Edit
                </a>
                <form method="POST" action="{{ route('trainer.programs.destroy', $program) }}"
                      onsubmit="return confirm('Hapus program ini?')">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
              Belum ada program.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if(method_exists($programs, 'links'))
      <div class="px-4 py-3">{{ $programs->appends(['q'=>request('q')])->links() }}</div>
    @endif
  </div>
@endsection

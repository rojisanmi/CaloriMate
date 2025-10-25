@extends('layouts.verivied')

@section('title', 'Kelola Menu Makanan')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Kelola Menu Makanan</h1>

    <a href="{{ route('trainer.foods.create') }}"
       class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
      tambah items
    </a>
  </div>

  {{-- Filter bar: search + per_page --}}
  <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ route('trainer.foods.index') }}" class="w-full sm:max-w-sm">
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Search"
             class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:outline-none">
    </form>

    <form method="GET" action="{{ route('trainer.foods.index') }}" class="flex items-center gap-2">
      {{-- keep query q when changing per_page --}}
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
  </div>

  {{-- TABLE --}}
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
    <table class="w-full text-sm text-left text-gray-600">
      <thead class="text-xs uppercase bg-gray-50">
        <tr>
          <th class="px-6 py-3">Nama</th>
          <th class="px-6 py-3">Gramasi</th>
          <th class="px-6 py-3">Kalori</th>
          <th class="px-6 py-3">Lemak</th>
          <th class="px-6 py-3">Karbo</th>
          <th class="px-6 py-3">Protein</th>
          <th class="px-6 py-3 text-right">Action</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($foods as $food)
          <tr class="bg-white border-b hover:bg-gray-50">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
              {{ $food->name }}
            </th>
            <td class="px-6 py-4">{{ $food->gramasi }}</td>
            <td class="px-6 py-4">{{ $food->kalori }}</td>
            <td class="px-6 py-4">{{ $food->lemak }}</td>
            <td class="px-6 py-4">{{ $food->karbo }}</td>
            <td class="px-6 py-4">{{ $food->protein }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.foods.edit', $food) }}"
                   class="inline-flex items-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-[#2E7D32] hover:opacity-90">
                  Edit
                </a>
                <form method="POST" action="{{ route('trainer.foods.destroy', $food) }}"
                      onsubmit="return confirm('Hapus item ini?')">
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
            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
              Data tidak ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- FOOTER: info & pagination --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 py-3">
      @php
        // angka "Showing X–Y of Z"
        $from = ($foods->currentPage() - 1) * $foods->perPage() + 1;
        $to   = min($foods->currentPage() * $foods->perPage(), $foods->total());
      @endphp
      <span class="text-sm text-gray-600">
        Showing <span class="font-semibold text-gray-900">{{ $from }}</span>–
        <span class="font-semibold text-gray-900">{{ $to }}</span>
        of <span class="font-semibold text-gray-900">{{ $foods->total() }}</span>
      </span>

      {{-- Tailwind pagination --}}
      <div>
        {{ $foods->appends(['q'=>request('q'),'per_page'=>request('per_page')])->links() }}
      </div>
    </div>
  </div>
@endsection

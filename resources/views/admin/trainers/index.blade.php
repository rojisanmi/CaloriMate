@extends('layouts.admin')

@section('title', 'Kelola Trainer')

@section('content')
<div class="space-y-6 cm-fadein">

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-extrabold text-[#2E471F]">Kelola Trainer</h1>
      <p class="text-gray-500 mt-1">{{ $trainers->total() }} trainer terdaftar</p>
    </div>
    <a href="{{ route('admin.trainers.create') }}"
       class="inline-block bg-[#2E471F] text-white px-5 py-2.5 rounded-xl font-semibold hover:opacity-90 transition shadow text-sm">
      + Tambah Trainer
    </a>
  </div>

  {{-- SUCCESS / ERROR --}}
  @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
      {{ session('success') }}
    </div>
  @endif

  {{-- SEARCH & PER PAGE --}}
  <form method="GET" class="flex flex-col sm:flex-row gap-3">
    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau username..."
           class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#2E471F] focus:outline-none">
    <select name="per_page"
            class="px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#2E471F] focus:outline-none">
      @foreach([5, 10, 25] as $n)
        <option value="{{ $n }}" @selected($perPage == $n)>{{ $n }} per halaman</option>
      @endforeach
    </select>
    <button type="submit"
            class="px-5 py-2.5 bg-[#2E471F] text-white rounded-xl text-sm font-semibold hover:opacity-90 transition">
      Cari
    </button>
  </form>

  {{-- TABLE --}}
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-[#EFE6D2] text-[#2E471F] font-semibold">
        <tr>
          <th class="px-5 py-3 text-left">#</th>
          <th class="px-5 py-3 text-left">Username</th>
          <th class="px-5 py-3 text-left">Nama</th>
          <th class="px-5 py-3 text-left">Keahlian</th>
          <th class="px-5 py-3 text-left">Email</th>
          <th class="px-5 py-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($trainers as $i => $trainer)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-5 py-3 text-gray-400">{{ $trainers->firstItem() + $i }}</td>
            <td class="px-5 py-3 font-medium text-gray-800">{{ $trainer->username }}</td>
            <td class="px-5 py-3 text-gray-700">{{ $trainer->nama }}</td>
            <td class="px-5 py-3 text-gray-500">{{ $trainer->keahlian ?: '—' }}</td>
            <td class="px-5 py-3 text-gray-500">{{ $trainer->user->email ?? '—' }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.trainers.edit', $trainer->username) }}"
                   class="px-3 py-1.5 bg-[#F4A938] text-white rounded-lg text-xs font-semibold hover:opacity-90 transition">
                  Edit
                </a>
                <form method="POST" action="{{ route('admin.trainers.destroy', $trainer->username) }}" id="del-trainer-{{ $trainer->username }}">
                  @csrf @method('DELETE')
                  <button type="button"
                          onclick="showDeleteModal(document.getElementById('del-trainer-{{ $trainer->username }}'), 'Hapus trainer {{ addslashes($trainer->nama) }}? Data ini tidak dapat dikembalikan.')"
                          class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-8 text-center text-gray-400">Tidak ada trainer ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- PAGINATION --}}
  <div>{{ $trainers->links() }}</div>

</div>
@endsection

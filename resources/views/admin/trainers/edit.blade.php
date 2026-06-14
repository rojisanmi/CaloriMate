@extends('layouts.admin')

@section('title', 'Edit Trainer')

@section('content')
<div class="max-w-xl mx-auto space-y-6 cm-fadein">

  <div>
    <a href="{{ route('admin.trainers.index') }}"
       class="text-sm text-[#2E471F] hover:underline">&larr; Kembali ke daftar trainer</a>
    <h1 class="text-3xl font-extrabold text-[#2E471F] mt-2">Edit Trainer</h1>
    <p class="text-gray-500 text-sm mt-1">{{ '@' . $trainer->username }}</p>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm space-y-1">
      @foreach($errors->all() as $error)
        <div>• {{ $error }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('admin.trainers.update', $trainer->username) }}"
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-5">
    @csrf @method('PUT')

    {{-- Read-only info --}}
    <div class="bg-[#EFE6D2] rounded-xl px-4 py-3 text-sm text-[#2E471F]">
      <span class="font-semibold">Username:</span> {{ $trainer->username }} &nbsp;·&nbsp;
      <span class="font-semibold">Email:</span> {{ $trainer->user->email ?? '—' }}
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
      <input type="text" name="nama" value="{{ old('nama', $trainer->nama) }}" required maxlength="100"
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition">
      @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Keahlian</label>
      <input type="text" name="keahlian" value="{{ old('keahlian', $trainer->keahlian) }}" maxlength="255"
             class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Opsional">
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Sertifikasi</label>
      @if($trainer->sertifikasi)
        <div class="mt-2">
            <a href="{{ asset('storage/' . $trainer->sertifikasi) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm font-medium hover:bg-blue-100 transition border border-blue-200">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
               </svg>
               Lihat File Sertifikasi
            </a>
        </div>
      @else
        <p class="text-sm text-gray-500 italic mt-2">Sertifikasi belum diunggah</p>
      @endif
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Password Baru</label>
      <input type="password" name="password" minlength="8"
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Kosongkan jika tidak ingin mengubah password">
      @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit"
              class="bg-[#2E471F] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow">
        Simpan Perubahan
      </button>
      <a href="{{ route('admin.trainers.index') }}"
         class="px-6 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
        Batal
      </a>
    </div>
  </form>

</div>
@endsection

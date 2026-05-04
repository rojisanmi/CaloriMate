@extends('layouts.admin')

@section('title', 'Tambah Trainer')

@section('content')
<div class="max-w-xl mx-auto space-y-6 cm-fadein">

  <div>
    <a href="{{ route('admin.trainers.index') }}"
       class="text-sm text-[#2E471F] hover:underline">&larr; Kembali ke daftar trainer</a>
    <h1 class="text-3xl font-extrabold text-[#2E471F] mt-2">Tambah Trainer</h1>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm space-y-1">
      @foreach($errors->all() as $error)
        <div>• {{ $error }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('admin.trainers.store') }}"
        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-5">
    @csrf

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Username <span class="text-red-500">*</span></label>
      <input type="text" name="username" value="{{ old('username') }}" required maxlength="20"
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Maks. 20 karakter">
      @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Email <span class="text-red-500">*</span></label>
      <input type="email" name="email" value="{{ old('email') }}" required
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="email@contoh.com">
      @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Password <span class="text-red-500">*</span></label>
      <input type="password" name="password" required minlength="8"
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Minimal 8 karakter">
      @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
      <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="100"
             class="w-full px-4 py-2.5 border-2 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Nama lengkap trainer">
      @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-[#2E471F] mb-1">Keahlian</label>
      <input type="text" name="keahlian" value="{{ old('keahlian') }}" maxlength="255"
             class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#2E471F] focus:outline-none transition"
             placeholder="Contoh: Nutrisi, Strength Training (opsional)">
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit"
              class="bg-[#2E471F] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow">
        Simpan Trainer
      </button>
      <a href="{{ route('admin.trainers.index') }}"
         class="px-6 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
        Batal
      </a>
    </div>
  </form>

</div>
@endsection

@extends('layouts.verivied')

@section('title', 'Profil Trainer')

@section('content')

@if (session('ok'))
  <div class="mb-6 rounded-lg bg-green-500 text-white px-4 py-3">
    {{ session('ok') }}
  </div>
@endif

<form method="POST"
      action="{{ route('profile.trainer.update') }}"
      enctype="multipart/form-data">

@csrf

<div class="max-w-4xl mx-auto p-6 lg:p-8 mt-8">
  <div class="bg-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col lg:flex-row lg:items-start gap-8">

      {{-- LEFT: Avatar --}}
      <div class="lg:w-1/3 flex flex-col items-center">
        @php
          $avatar = $user->trainer->avatar ?? null;
          $avatarUrl = $avatar ? asset($avatar) : null;
        @endphp

        @if($avatarUrl)
          <img src="{{ $avatarUrl }}"
               class="w-48 h-48 rounded-lg object-cover border shadow-sm">
        @else
          <div class="w-48 h-48 flex items-center justify-center rounded-lg border bg-gray-50 text-gray-400">
            No Avatar
          </div>
        @endif

        <p class="mt-4 font-bold text-lg text-[#2E4F2A]">
          {{ $user->trainer->nama ?? $user->username }}
        </p>
        <p class="text-sm text-gray-500">@{{ $user->username }}</p>
      </div>

      {{-- RIGHT: FORM --}}
      <div class="lg:w-2/3 w-full space-y-4">

        {{-- Nama --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Nama</label>
          <input type="text"
                 name="nama"
                 value="{{ old('nama', $user->trainer->nama ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md">
        </div>

        {{-- Username --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Username</label>
          <input type="text"
                 value="{{ $user->username }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Email --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Email</label>
          <input type="email"
                 value="{{ $user->email }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Role --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Role</label>
          <input type="text"
                 value="{{ $user->role_label ?? $user->role }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Keahlian --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Keahlian</label>
          <input type="text"
                 name="keahlian"
                 value="{{ old('keahlian', $user->trainer->keahlian ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md">
        </div>

        {{-- Sertifikasi --}}
        <div class="mt-4">
            <label class="font-semibold text-[#2E4F2A] block mb-2">Sertifikasi</label>

            {{-- CARD PREVIEW --}}
            <div class="w-full h-56 flex justify-center items-center 
            border border-gray-300 rounded-md bg-gray-50 mb-3 overflow-hidden">
                @if(!empty($user->trainer->sertifikasi))
                    <img id="previewImage"
                      src="{{ asset('storage/' . $user->trainer->sertifikasi) }}"
                      class="h-full w-full object-contain object-center rounded">
                @else
                    <img id="previewImage"
                        class="max-h-48 mx-auto object-contain rounded shadow mb-3 hidden">
                @endif
            </div>

            {{-- CUSTOM FILE INPUT --}}
            <label class="flex flex-col items-center px-4 py-3 bg-white rounded-md 
               shadow cursor-pointer border border-gray-300 hover:bg-gray-100">
                <span class="text-sm text-gray-600">Klik untuk memilih file sertifikasi</span>
                <input type="file"
                      name="sertifikasi"
                      class="hidden"
                      id="sertifikasiInput">
            </label>
        </div>

        {{-- Save --}}
        <div class="pt-6 flex justify-end">
          <button type="submit"
                  class="px-6 py-2 bg-[#2E7D32] text-white font-semibold rounded-md hover:opacity-90">
            Simpan Perubahan
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('sertifikasiInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewImage');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
</form>
@endsection

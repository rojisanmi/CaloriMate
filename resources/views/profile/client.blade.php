@extends('layouts.verivied-client')

@section('title','Profil Client')

@section('content')

@if (session('ok'))
  <div class="mb-6 rounded-lg bg-green-500 text-white px-4 py-3">
    {{ session('ok') }}
  </div>
@endif

<form method="POST"
      action="{{ route('profile.client.update') }}"
      enctype="multipart/form-data">
@csrf

<div class="max-w-4xl mx-auto p-6 lg:p-8 mt-8">
  <div class="bg-[#FFFFFF] p-6 rounded-xl shadow-lg">
    <div class="flex flex-col lg:flex-row gap-8">

      {{-- LEFT: Avatar --}}
      <div class="lg:w-1/3 flex flex-col items-center">
        @php
          $avatar = $user->client->avatar ?? null;
        @endphp

        @if($avatar)
          <img src="{{ asset('storage/'.$avatar) }}"
               class="w-48 h-48 rounded-lg object-cover border shadow">
        @else
          <div class="w-48 h-48 flex items-center justify-center
                      rounded-lg border bg-gray-100 text-gray-400">
            No Avatar
          </div>
        @endif

        <p class="mt-4 font-bold text-lg text-[#2E471F]">
          {{ $user->client->nama ?? $user->username }}
        </p>
        <p class="text-sm text-gray-500">@{{ $user->username }}</p>

        {{-- Upload Avatar --}}
        <label class="mt-4 cursor-pointer text-sm text-[#2E471F] font-semibold">
          Ganti Foto
          <input type="file" name="avatar" class="hidden" onchange="previewAvatar(this)">
        </label>
      </div>

      {{-- RIGHT: FORM --}}
      <div class="lg:w-2/3 space-y-4">

        <div>
          <label class="font-semibold text-[#2E471F]">Nama</label>
          <input type="text" name="nama"
                 value="{{ old('nama', $user->client->nama ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md">
        </div>

        <div>
          <label class="font-semibold text-[#2E471F]">Username</label>
          <input type="text" readonly
                 value="{{ $user->username }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100">
        </div>

        <div>
          <label class="font-semibold text-[#2E471F]">Email</label>
          <input type="email" readonly
                 value="{{ $user->email }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100">
        </div>

        <div>
          <label class="font-semibold text-[#2E471F]">Role</label>
          <input type="text" readonly
                 value="Client"
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="font-semibold text-[#2E471F]">Berat Badan (kg)</label>
            <input type="number" name="bb"
                   value="{{ old('bb', $user->client->bb ?? '') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-md">
          </div>

          <div>
            <label class="font-semibold text-[#2E471F]">Tinggi Badan (cm)</label>
            <input type="number" name="tb"
                   value="{{ old('tb', $user->client->tb ?? '') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-md">
          </div>
        </div>

        <div class="pt-6 flex justify-end">
          <button type="submit"
                  class="px-6 py-2 bg-[#2E7D32] text-white font-semibold rounded-md">
            Simpan Perubahan
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

</form>

<script>
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.querySelector('img')?.setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

@endsection

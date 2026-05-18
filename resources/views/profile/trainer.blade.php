@extends('layouts.verivied')

@section('title', 'Profil')

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
<div class="mb-6 cm-fadein">
  <h1 class="font-raleway text-2xl sm:text-3xl font-bold text-[#2E471F]">My Profile</h1>
  <p class="text-sm text-gray-500 mt-1">Kelola data trainer dan sertifikasi kamu</p>
</div>

@if($errors->any() && !$errors->has('macro'))
  <div class="mb-4 flex items-start gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 cm-fadein">
    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
    </svg>
    <ul class="text-red-600 text-sm list-disc list-inside">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('profile.trainer.update') }}" enctype="multipart/form-data" class="space-y-6">
  @csrf

  {{-- AVATAR CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-1">
    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
      <div id="avatarPreview"
           class="h-24 w-24 rounded-full bg-[#EFE6D2] flex items-center justify-center flex-shrink-0
                  ring-4 ring-[#F5A623]/20 overflow-hidden">
        @if($user->trainer && $user->trainer->photo_url)
          <img src="{{ $user->trainer->photo_url }}" alt="{{ $user->username }}" class="h-full w-full object-cover">
        @else
          <span class="text-3xl font-bold text-[#2E471F] font-raleway">
            {{ strtoupper(substr($user->trainer->nama ?? $user->username, 0, 2)) }}
          </span>
        @endif
      </div>

      <div class="flex-1">
        <p class="font-bold text-[#2E471F] text-lg">{{ $user->trainer->nama ?? $user->username }}</p>
        <div class="flex flex-wrap items-center gap-2 mt-1">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#F5A623]/20 text-[#F5A623]">
            {{ $user->role_label }}
          </span>
          <span class="text-sm text-gray-400">{{ $user->email }}</span>
        </div>

        <div class="flex items-center gap-2 mt-3">
          <label class="cursor-pointer">
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" capture="environment" class="hidden" id="photoInput">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2E471F] text-white text-xs font-semibold
                         hover:bg-[#3d6628] transition-colors shadow-sm">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
              </svg>
              Ganti Foto
            </span>
          </label>
        </div>
        @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  {{-- PERSONAL INFO CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-2">
    <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-1">Informasi Pribadi</h2>
    <p class="text-xs text-gray-400 mb-5">Username tidak dapat diubah karena merupakan identitas akun.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Username</label>
        <input type="text" value="{{ $user->username }}" readonly
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed">
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Nama Lengkap</label>
        <input type="text" name="nama" value="{{ old('nama', $user->trainer->nama ?? '') }}" required maxlength="100"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Keahlian</label>
        <input type="text" name="keahlian" value="{{ old('keahlian', $user->trainer->keahlian ?? '') }}" required maxlength="255"
               placeholder="mis. Strength, Cardio, Nutrisi"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('keahlian') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('keahlian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

    </div>
  </div>

  {{-- SERTIFIKASI CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-3">
    <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-1">Sertifikasi</h2>
    <p class="text-xs text-gray-400 mb-5">Upload bukti sertifikasi keahlian kamu (jpg, png, atau pdf, maks 2 MB).</p>

    {{-- Preview --}}
    <div class="w-full h-56 flex justify-center items-center
                border border-gray-200 rounded-xl bg-[#EFE6D2]/30 mb-3 overflow-hidden">
      @if(!empty($user->trainer->sertifikasi))
        @php
          $sertPath = $user->trainer->sertifikasi;
          $isPdf = str_ends_with(strtolower($sertPath), '.pdf');
        @endphp
        @if($isPdf)
          <a href="{{ asset('storage/' . $sertPath) }}" target="_blank"
             class="flex flex-col items-center gap-2 text-[#2E471F] hover:text-[#3d6628] transition-colors">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
            <span class="text-sm font-semibold">Lihat Sertifikasi (PDF)</span>
          </a>
        @else
          <img id="previewImage" src="{{ asset('storage/' . $sertPath) }}"
               class="h-full w-full object-contain">
        @endif
      @else
        <div class="flex flex-col items-center gap-2 text-gray-300">
          <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
          </svg>
          <p class="text-sm">Belum ada sertifikasi</p>
        </div>
        <img id="previewImage" class="hidden h-full w-full object-contain">
      @endif
    </div>

    <label class="flex items-center justify-center gap-2 px-4 py-3 bg-white rounded-xl
                  border border-dashed {{ $errors->has('sertifikasi') ? 'border-red-300' : 'border-gray-300' }}
                  cursor-pointer hover:bg-gray-50 transition-all duration-150 text-sm font-semibold text-[#2E471F]">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
      </svg>
      Pilih file sertifikasi
      <input type="file" name="sertifikasi" class="hidden" id="sertifikasiInput"
             accept="image/jpeg,image/png,image/jpg,application/pdf" capture="environment">
    </label>
    @error('sertifikasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- ACTIONS --}}
  <div class="flex justify-end gap-3 cm-fadein cm-delay-4">
    <a href="{{ route('trainer.home') }}"
       class="px-6 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
              hover:bg-gray-50 transition-colors duration-150">
      Cancel
    </a>
    <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                   shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
      Save Changes
    </button>
  </div>

</form>

<script>
// Live photo preview
document.getElementById('photoInput')?.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (evt) => {
    document.getElementById('avatarPreview').innerHTML =
      `<img src="${evt.target.result}" alt="Preview" class="h-full w-full object-cover">`;
  };
  reader.readAsDataURL(file);
});

// Live sertifikasi preview
document.getElementById('sertifikasiInput')?.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const preview = document.getElementById('previewImage');
  if (!preview || !file.type.startsWith('image/')) return;
  const reader = new FileReader();
  reader.onload = (evt) => {
    preview.src = evt.target.result;
    preview.classList.remove('hidden');
  };
  reader.readAsDataURL(file);
});
</script>

@endsection

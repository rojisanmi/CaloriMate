@extends('auth')

@section('title', 'Register – Lengkapi Profil')

@section('content')

<a href="{{ route('register.form') }}"
   class="absolute left-4 top-4 md:left-8 md:top-6 z-20 inline-flex items-center gap-2
          bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold text-[#2E471F]
          shadow hover:shadow-md hover:bg-white transition-all duration-150">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
  </svg>
  Kembali
</a>

{{-- Kartu 2 kolom: sama gaya dengan login/register --}}
<div class="auth-card rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-xl bg-white">

  {{-- KIRI: panel hijau + dekor + maskot --}}
  <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-2 flex items-end">
    <img src="{{ asset('images/ellipse-register1.png') }}" alt="" aria-hidden="true"
         class="absolute top-16 left-8 w-24 h-24 pointer-events-none select-none">
    <img src="{{ asset('images/ellipse-register1.png') }}" alt="" aria-hidden="true"
         class="absolute bottom-20 right-12 w-20 h-20 pointer-events-none select-none">
    <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 object-contain pointer-events-none select-none">
    <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
         class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none">
  </div>

  {{-- KANAN: form (field & action TIDAK diubah) --}}
  <div class="bg-white p-8 md:p-10 lg:p-12 grid place-items-center">
    <div class="w-full max-w-sm">
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-12">
      </div>
      <h2 class="font-raleway text-3xl md:text-4xl font-bold text-[#2E471F] text-center mb-6">Lengkapi Profil</h2>

      <form method="POST" action="{{ route('register.client.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Foto Profil --}}
        <div>
          <span class="block mb-2 text-sm font-semibold text-[#2E471F]">Foto Profil</span>
          <div class="flex items-center gap-4">
            <div id="photoPreview"
                 class="h-20 w-20 rounded-full bg-[#EFE6D2] flex items-center justify-center flex-shrink-0
                        ring-2 ring-[#2E471F]/10 overflow-hidden">
              <svg class="h-10 w-10 text-[#2E471F]/40" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
              </svg>
            </div>
            <label class="flex-1 cursor-pointer">
              <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required
                     class="hidden" id="photoInput">
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                           hover:bg-[#3d6628] transition-colors shadow-sm cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                </svg>
                Pilih Foto
              </span>
              <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau WEBP. Maks 2 MB.</p>
            </label>
          </div>
          @error('photo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tinggi Badan (cm) --}}
        <div>
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Tinggi Badan (cm)</span>
          <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required
                 min="100" max="300" placeholder="Tinggi Badan (cm)"
                 class="w-full px-6 py-4 border-2 {{ $errors->has('tinggi_badan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('tinggi_badan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Berat Badan (kg) --}}
        <div>
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Berat Badan (kg)</span>
          <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" required
                 min="40" max="500" placeholder="Berat Badan (kg)"
                 class="w-full px-6 py-4 border-2 {{ $errors->has('berat_badan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('berat_badan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Gender --}}
        <div>
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Jenis Kelamin</span>
          <select name="gender" required
                  class="w-full px-6 py-4 border-2 {{ $errors->has('gender') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 text-base focus:border-[#2d5016] focus:outline-none transition">
            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih</option>
            <option value="L" {{ old('gender')==='L'?'selected':'' }}>Laki-laki</option>
            <option value="P" {{ old('gender')==='P'?'selected':'' }}>Perempuan</option>
          </select>
          @error('gender') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Umur --}}
        <div>
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Umur</span>
          <input type="number" name="umur" min="17" max="120" value="{{ old('umur') }}" required
                 placeholder="Umur"
                 class="w-full px-6 py-4 border-2 {{ $errors->has('umur') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('umur') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-center pt-6">
          <button type="submit"
                  class="bg-[#2E471F] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6628] transition shadow-lg">
            Selesai
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Live preview foto profil
  document.getElementById('photoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (evt) => {
      const preview = document.getElementById('photoPreview');
      preview.innerHTML = `<img src="${evt.target.result}" alt="Preview" class="h-full w-full object-cover">`;
    };
    reader.readAsDataURL(file);
  });
</script>
@endsection

@extends('auth')

@section('title', 'Register – Lengkapi Profil')

@section('content')
{{-- Back (opsional, bisa hapus kalau tak perlu) --}}
<a href="{{ route('register.form') }}"
   class="absolute left-4 top-4 md:left-8 md:top-6 z-20 flex items-center justify-center w-10 h-10 md:w-12 md:h-12 bg-[#2d5016] rounded-lg hover:bg-[#3d6020] transition">
  <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
  </svg>
</a>

{{-- Kartu 2 kolom: sama gaya dengan login/register --}}
<div class="rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-lg bg-white">

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
      <h2 class="text-3xl md:text-4xl font-bold text-[#2d5016] text-center mb-6">Register</h2>

      <form method="POST" action="{{ route('register.client.store') }}" class="space-y-5">
        @csrf

        @if ($errors->any())
          <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Tinggi Badan (cm) --}}
        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Tinggi Badan (cm)
          </span>
          <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required
                 placeholder="Tinggi Badan (cm)"
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        {{-- Berat Badan (kg) --}}
        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Berat Badan (kg)
          </span>
          <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" required
                 placeholder="Berat Badan (kg)"
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        {{-- Gender --}}
        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Gender
          </span>
          <select name="gender" required
                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 text-base focus:border-[#2d5016] focus:outline-none transition">
            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih</option>
            <option value="L" {{ old('gender')==='L'?'selected':'' }}>Laki-laki</option>
            <option value="P" {{ old('gender')==='P'?'selected':'' }}>Perempuan</option>
          </select>
        </label>

        {{-- Umur --}}
        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Umur
          </span>
          <input type="number" name="umur" min="5" max="120" value="{{ old('umur') }}" required
                 placeholder="Umur"
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <div class="flex justify-center pt-6">
          <button type="submit"
                  class="bg-[#2d5016] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6020] transition shadow-lg">
            Selesai
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

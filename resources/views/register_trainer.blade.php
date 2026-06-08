@extends('auth')

@section('title', 'Daftar Trainer')

@section('content')

<a href="{{ route('register.choice') }}"
   class="absolute left-4 top-4 md:left-8 md:top-6 z-20 inline-flex items-center gap-2
          bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold text-[#2E471F]
          shadow hover:shadow-md hover:bg-white transition-all duration-150">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
  </svg>
  Kembali
</a>


<div class="auth-card rounded-3xl overflow-hidden mx-auto w-full max-w-5xl
            grid grid-cols-1 lg:grid-cols-2 shadow-xl bg-white">

  {{-- KIRI: panel hijau + dekor + maskot --}}
  <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-2 flex items-end">
    <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-80 h-80 object-contain pointer-events-none select-none">
    <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
         class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none">
  </div>

  {{-- KANAN: form register trainer --}}
  <div class="bg-white p-8 md:p-10 lg:p-12 grid place-items-center">
    <div class="w-full max-w-sm">
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-12">
      </div>

      <h2 class="font-raleway text-3xl md:text-4xl font-bold text-[#2E471F] text-center mb-6">Daftar Trainer</h2>

      <form method="POST" action="{{ route('register.trainer.do') }}" class="space-y-5">
        @csrf

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Username</span>
          <input name="username" placeholder="Username" value="{{ old('username') }}" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('username') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Email</span>
          <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Nama Lengkap</span>
          <input name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Keahlian</span>
          <input name="keahlian" placeholder="cth: Yoga, HIIT, Strength Training" value="{{ old('keahlian') }}" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('keahlian') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('keahlian') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Password</span>
          <input type="password" name="password" placeholder="Password (min. 8 karakter)" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Konfirmasi Password</span>
          <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                 class="w-full px-6 py-4 border-2 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </div>

        <div class="flex justify-center pt-6">
          <button type="submit"
                  class="bg-[#2E471F] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6628] transition shadow-lg">
            Daftar
          </button>
        </div>
        <div class="flex justify-center pt-4">
          <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login.trainer') }}"
               class="font-semibold text-[#2d5016] hover:underline hover:text-[#3d6020] transition">
              Masuk
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

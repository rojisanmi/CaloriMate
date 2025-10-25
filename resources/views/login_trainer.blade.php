@extends('auth')

@section('title', 'Login')

@section('content')
<div class="rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-lg bg-white">

  {{-- KIRI: panel hijau + dekor + maskot --}}
  <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-2 flex items-end">
    <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 object-contain pointer-events-none select-none">
    <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
         class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none">
  </div>

  {{-- KANAN: form login --}}
  <div class="bg-white p-8 md:p-10 lg:p-12 grid place-items-center">
    <div class="w-full max-w-sm">
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo-warna.png') }}" alt="CaloriMate" class="h-20">
      </div>

      <h2 class="text-3xl md:text-4xl font-bold text-[#2d5016] text-center mb-6">Login Trainer</h2>

      @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm mb-4">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login.do') }}" class="space-y-5">
        @csrf
        <label class="block">
          <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>
        <label class="block">
          <input type="password" name="password" placeholder="Password" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <div class="flex justify-center pt-2">
          <button type="submit"
                  class="bg-[#2d5016] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6020] transition shadow-lg">
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

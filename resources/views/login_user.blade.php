@extends('auth')

@section('title', 'Login')

@section('content')
  {{-- CONTAINER --}}
  <div class="rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-lg bg-white">

    {{-- KIRI --}}
    <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-0 flex items-end">
      <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 object-contain pointer-events-none select-none">
      <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
        class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none">
    </div>

    {{-- KANAN--}}
    <div class="relative bg-white p-8 md:p-10 lg:p-12 flex items-center justify-center">
      {{-- LOGO --}}
      <img src="{{ asset('images/logo-warna.png') }}" alt="CaloriMate Logo"
        class="absolute top-6 md:top-8 left-1/2 -translate-x-1/2 h-16 md:h-20 lg:h-24 select-none" />

      {{-- KONTEN LOGIN --}}
      <div class="w-full max-w-sm text-center pt-12 md:pt-28 lg:pt-20">
        <h2 class="text-3xl md:text-4xl font-bold text-[#2E4F2A] mb-6">Login Client</h2>

        @if ($errors->any())
          <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm mb-4">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.do') }}" class="space-y-5">
          @csrf
          <label class="block">
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required
              class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2E4F2A] focus:outline-none transition">
          </label>
          <label class="block">
            <input type="password" name="password" placeholder="Password" required
              class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2E4F2A] focus:outline-none transition">
          </label>
          <input type="hidden" name="role" value="2">
          <div class="flex justify-center pt-2">
            <button type="submit"
              class="bg-[#2E7D32] text-white px-14 py-3 rounded-full font-bold text-lg hover:opacity-90 transition shadow-lg">
              Login
            </button>
          </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
          Belum punya akun?
          <a href="{{ route('register.form') }}" class="font-semibold text-[#2E4F2A] hover:underline">Register</a>
        </p>
      </div>
    </div>
  </div>
@endsection
@extends('auth')

@section('title', 'Login Trainer')

@section('content')

  <a href="{{ url('/login') }}"
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

    {{-- KIRI--}}
    <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-2 flex items-end">
      <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
           class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                  w-80 h-80 object-contain pointer-events-none select-none">
      <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
           class="relative z-10 w-full max-w-[360px] h-auto mx-auto select-none">
    </div>

    {{-- KANAN --}}
    <div class="bg-white px-8 md:px-10 lg:px-12
                pt-6 md:pt-8 lg:pt-5
                pb-10 lg:pb-14 grid place-items-center">
      <div class="w-full max-w-sm">
        <div class="flex justify-center mb-6">
          <img src="{{ asset('images/logo-warna.png') }}" alt="CaloriMate" class="h-16 md:h-20">
        </div>

        <h2 class="font-raleway text-3xl md:text-4xl font-bold text-[#2E471F] text-center mb-6">
          Login Trainer
        </h2>

        @if ($errors->has('login'))
          <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $errors->first('login') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.do') }}" class="space-y-5">
          @csrf

          <label class="block">
            <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Username</span>
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required
                   class="w-full px-6 py-3 border-2 {{ $errors->has('login') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          </label>

          <label class="block">
            <span class="block mb-1 text-sm font-semibold text-[#2d5016]">Password</span>
            <input type="password" name="password" placeholder="Password" required
                   class="w-full px-6 py-3 border-2 {{ $errors->has('login') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
          </label>
          <input type="hidden" name="role" value="2">

          <div class="flex justify-center pt-2">
            <button type="submit"
                    class="bg-[#2E471F] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6628] transition shadow-lg">
              Login
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

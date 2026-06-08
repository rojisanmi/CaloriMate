@extends('auth')

@section('title','Daftar')

@section('content')
  
  <a href="{{ route('home') }}"
     class="absolute left-4 top-4 md:left-8 md:top-6 z-20 inline-flex items-center gap-2
            bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold text-[#2E471F]
            shadow hover:shadow-md hover:bg-white transition-all duration-150">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    Kembali
  </a>

  <div class="auth-card rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-xl bg-white">
    {{-- KIRI --}}
    <div
      class="relative bg-[#344F1F] text-white flex items-end
             px-6 pt-6 pb-0 lg:-mr-2 lg:z-10">
      <img
        src="{{ asset('images/group-54.png') }}"
        alt=""
        aria-hidden="true"
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 object-contain pointer-events-none select-none" />
      <img
        src="{{ asset('images/mascot-register.png') }}"
        alt="CaloriMate Mascot"
        class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none" />
    </div>

    {{-- KANAN --}}
    <div class="relative bg-white p-8 md:p-10 lg:p-12 flex items-center justify-center">
      <img src="{{ asset('images/logo-warna.png') }}" alt="CaloriMate Logo"
           class="absolute top-6 md:top-8 left-1/2 -translate-x-1/2
                  h-16 md:h-20 lg:h-24 select-none" />
      <div class="w-full max-w-sm text-center pt-12 md:pt-28 lg:pt-20">
        <h2 class="text-3xl md:text-4xl font-bold text-[#2E4F2A] mb-6">Daftar</h2>

        <div class="space-y-4">
          <a href="{{ route('register.trainer.form') }}"
             class="block rounded-lg bg-[#2E7D32] px-5 py-3 font-semibold text-white shadow-md hover:opacity-90 transition">
            Daftar sebagai Trainer
          </a>
          <div class="text-sm text-[#2E4F2A]/70">Atau</div>
          <a href="{{ route('register.form') }}"
             class="block rounded-lg bg-[#2E7D32] px-5 py-3 font-semibold text-white shadow-md hover:opacity-90 transition">
            Daftar sebagai Client
          </a>
        </div>

        <div class="mt-6">
          <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login.show') }}"
               class="font-semibold text-[#2d5016] hover:underline hover:text-[#3d6020] transition">
              Masuk
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection

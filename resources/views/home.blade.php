@extends('app')
@section('title', 'CaloriMate - About')
@section('content')  
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
    {{-- LEFT copy --}}
    <div class="max-w-xl">
      <span class="inline-block text-[2rem] tracking-widest italic font-latin">about</span>
      <h1 class="text-5xl sm:text-6xl font-extrabold leading-none tracking-tight">
        CaloriMate
      </h1>
      <div class="mt-3 h-1 w-80 rounded-full bg-white"></div>

      <p class="mt-6 text-[15px] leading-relaxed opacity-95">
        Aplikasi pemantau kalori dan gaya hidup sehat yang membantu remaja mengelola kebutuhan
        kalori, memilih menu makanan, serta mengikuti program latihan sesuai kondisi tubuh.
        Dengan fitur ini, CaloriMate mendukung pengguna dalam membangun kesadaran dan disiplin
        menuju hidup sehat.
      </p>

      <div class="mt-7">
        <a href="{{ url('/login') }}"
          class="inline-flex items-center rounded-full bg-white px-6 py-2 text-sm font-semibold text-[#2E4F2A] shadow-sm hover:opacity-90 transition">
          Login
        </a>
      </div>

      {{-- mini profile card --}}
      <div class="mt-12 flex items-start gap-4">
        <img src="{{ asset('images/avatar.jpg') }}"
             alt="avatar"
             class="h-16 w-16 rounded-full object-cover ring-2 ring-white/20">
        <div class="leading-relaxed max-w-sm">
          <p class="font-bold text-lg">Rima</p>
          <p class="opacity-90 text-sm">
            sosok Maskot CaloriMate yang siap memandu anda untuk menjaga tubuh tetap bugar.
            Jangan berteriak My Kisah di depannya!
          </p>
        </div>
      </div>

      
    </div>

    {{-- RIGHT mascot --}}
    <div class="relative pt-8 lg:pt-10 overflow-hidden">
      {{-- dekor belakang (lingkaran/gelombang) --}}
        <img
        src="{{ asset('images/bg_circle_landing.png') }}"
        alt=""
        aria-hidden="true"
        class="pointer-events-none select-none
            absolute -z-10 
            right-20 top-6    
            w-100 max-w-none   
            ">
      {{-- Maskot --}}
      <img
        src="{{ asset('images/maskot_landing.png') }}"
        alt="CaloriMate Mascot"
        class="mx-auto lg:ml-auto lg:mr-0 max-h-[400px] w-auto drop-shadow-[0_10px_30px_rgba(0,0,0,0.35)] select-none"
        draggable="false" />
    </div>
  </div>
  @if (session('status'))
    <div id="flash-status"
        class="fixed top-24 left-1/2 -translate-x-1/2 z-50
                bg-emerald-600 text-white px-4 py-2 rounded-full shadow-lg">
      {{ session('status') }}
    </div>
    <script>
      setTimeout(() => document.getElementById('flash-status')?.remove(), 3500);
    </script>
  @endif


{{-- <section id="community" class="mt-24"></section>
<section id="support" class="mt-24"></section>
<section id="contact" class="mt-24"></section>
<section id="register" class="mt-24"></section> --}}
 
@endsection


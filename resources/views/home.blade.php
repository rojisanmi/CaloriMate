@extends('app')
@section('title', 'CaloriMate - About')
@section('content')  
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 xl:gap-16 items-start px-4 sm:px-6 lg:px-8">
    {{-- LEFT copy --}}
    <div class="max-w-full lg:max-w-xl">
      <span class="inline-block text-xl sm:text-2xl md:text-[2rem] tracking-widest italic font-latin -mb-2 ml-2">about</span>
      <h1 class="font-modak text-4xl sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl leading-none tracking-tight mt-3">
        CaloriMate
      </h1>
      <div class="mt-3 h-1 w-72 rounded-full bg-white"></div>

      <p class="font-instrument mt-6 text-sm sm:text-[15px] lg:text-base leading-relaxed opacity-95">
        Aplikasi pemantau kalori dan gaya hidup sehat yang membantu remaja mengelola kebutuhan
        kalori, memilih menu makanan, serta mengikuti program latihan sesuai kondisi tubuh.
        Dengan fitur ini, CaloriMate mendukung pengguna dalam membangun kesadaran dan disiplin
        menuju hidup sehat.
      </p>

      <div class="mt-6 lg:mt-7">
        <a href="{{ url('/login') }}"
          class="inline-flex items-center rounded-full bg-white px-5 sm:px-6 py-2 text-sm font-semibold text-[#2E4F2A] shadow-sm hover:opacity-90 transition">
          Login
        </a>
      </div>

      {{-- mini profile card --}}
      <div class="mt-10 lg:mt-12 flex items-start gap-3 sm:gap-4">
      <img src="{{ asset('images/maskotcilik.png') }}"
             alt="avatar"
             class="h-14 w-14 sm:h-16 sm:w-16 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0">
        <div class="leading-relaxed max-w-full lg:max-w-sm">
          <p class="font-bold text-base sm:text-lg">Rima</p>
          <p class="opacity-90 text-xs sm:text-sm">
            sosok Maskot CaloriMate yang siap memandu anda untuk menjaga tubuh tetap bugar.
            Jangan berteriak My Kisah di depannya!
          </p>
        </div>
      </div>
    </div>

    {{-- RIGHT mascot --}}
    <div class="relative pt-8 lg:pt-0 flex justify-center lg:justify-end items-end min-h-[400px] sm:min-h-[500px] lg:min-h-full">
      {{-- Dekor belakang (lingkaran/gelombang) --}} 
      <img
        src="{{ asset('images/bg_circle_landing.png') }}"
        alt=""
        aria-hidden="true"
        class="pointer-events-none select-none absolute -z-10 right-0 sm:right-5 lg:right-10 top-0 lg:top-5 w-64 sm:w-80 lg:w-72 xl:w-96 max-w-none opacity-100"
      >
      
      {{-- Maskot --}}
      <img
        src="{{ asset('images/maskot_landing.png') }}"
        alt="CaloriMate Mascot"
        class="mx-auto lg:mx-0 h-[350px] sm:h-[450px] md:h-[500px] lg:h-[500px] xl:h-[550px] 2xl:h-[600px] w-auto object-contain object-bottom drop-shadow-[0_10px_30px_rgba(0,0,0,0.35)] select-none"
        draggable="false" />
    </div>
  </div>

  @if (session('status'))
    <div id="flash-status"
        class="fixed top-20 sm:top-24 left-1/2 -translate-x-1/2 z-50
                bg-emerald-600 text-white px-4 py-2 rounded-full shadow-lg text-sm">
      {{ session('status') }}
    </div>
    <script>
      setTimeout(() => document.getElementById('flash-status')?.remove(), 3500);
    </script>
  @endif
 
@endsection
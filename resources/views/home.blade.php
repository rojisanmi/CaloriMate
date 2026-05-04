@extends('app')
@section('title', 'CaloriMate — Teman Sehat Kamu')

@section('content')

{{-- ============================================================
     HERO
============================================================ --}}
<section id="hero" class="relative min-h-[calc(100vh-96px)] flex items-center px-4 sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-16 items-center w-full">

    {{-- Copy --}}
    <div class="max-w-xl order-2 lg:order-1">
      <span class="inline-block text-xl sm:text-2xl tracking-widest italic font-latin opacity-70">your health,</span>
      <h1 class="font-modak text-5xl sm:text-6xl xl:text-7xl leading-none tracking-tight mt-1">
        CaloriMate
      </h1>
      <div class="mt-3 h-1 w-48 rounded-full bg-[#F5A623]"></div>

      <p class="font-quicksand mt-6 text-base sm:text-lg leading-relaxed opacity-90 max-w-md">
        Pantau kalori, lacak makronutrien, dan ikuti program latihan yang sesuai kondisi tubuhmu.
        Semua dalam satu aplikasi yang simpel dan menyenangkan.
      </p>

      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ url('/register') }}"
           class="inline-flex items-center rounded-full bg-[#F5A623] px-7 py-3 text-sm font-bold text-white shadow-lg hover:bg-[#e09500] transition">
          Mulai Sekarang
        </a>
        <a href="{{ url('/login') }}"
           class="inline-flex items-center rounded-full border-2 border-white/60 px-7 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
          Login
        </a>
      </div>

      {{-- Mascot mini --}}
      <div class="mt-10 flex items-center gap-3">
        <img src="{{ asset('images/maskotcilik.png') }}" alt="Rima"
             class="h-12 w-12 rounded-full object-cover ring-2 ring-[#F5A623]/60 flex-shrink-0">
        <p class="font-quicksand text-sm opacity-75 max-w-xs">
          Hai! Aku <strong>Rima</strong>, maskot CaloriMate. Aku siap menemani perjalanan sehatmu!
        </p>
      </div>
    </div>

    {{-- Mascot image --}}
    <div class="relative order-1 lg:order-2 flex justify-center lg:justify-end items-end min-h-[320px] sm:min-h-[420px]">
      <img src="{{ asset('images/bg_circle_landing.png') }}" alt="" aria-hidden="true"
           class="pointer-events-none select-none absolute -z-10 right-0 top-0 w-72 xl:w-96 opacity-90">
      <img src="{{ asset('images/maskot_landing.png') }}" alt="CaloriMate Mascot" draggable="false"
           class="relative h-[300px] sm:h-[420px] lg:h-[500px] w-auto object-contain object-bottom
                  drop-shadow-[0_10px_40px_rgba(0,0,0,0.3)] select-none">
    </div>
  </div>

  {{-- Scroll hint --}}
  <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 opacity-40 animate-bounce">
    <span class="text-xs font-quicksand tracking-wide">Scroll</span>
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
    </svg>
  </div>
</section>

{{-- ============================================================
     FITUR  (#about)
============================================================ --}}
<section id="about" class="w-full min-h-screen flex items-center bg-[#EFE6D2] text-[#2E471F]">
  <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-28 sm:py-40">

    <div class="text-center mb-12">
      <span class="text-sm font-semibold uppercase tracking-widest text-[#F5A623]">Fitur Unggulan</span>
      <h2 class="font-modak text-3xl sm:text-4xl mt-2">Apa yang bisa CaloriMate lakukan?</h2>
      <p class="font-quicksand mt-3 text-sm sm:text-base opacity-70 max-w-xl mx-auto">
        Dari mencatat makanan hingga memantau progress tubuh — semua tersedia di satu tempat.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

      @php
        $features = [
          [
            'img'   => 'images/feature-diary.png',
            'title' => 'Diary Makanan',
            'desc'  => 'Catat sarapan, makan siang, makan malam, dan camilan. Pantau kalori masuk setiap hari.',
            'color' => '#2E471F',
          ],
          [
            'img'   => 'images/feature-exercise.png',
            'title' => 'Program Latihan',
            'desc'  => 'Ikuti program latihan terstruktur dari trainer berpengalaman sesuai level dan tujuanmu.',
            'color' => '#F5A623',
          ],
          [
            'img'   => 'images/feature-stats.png',
            'title' => 'Statistik & BMI',
            'desc'  => 'Lihat BMI, target kalori, dan breakdown makro (protein, karbo, lemak) secara visual.',
            'color' => '#3B82F6',
          ],
          [
            'img'   => 'images/feature-target.png',
            'title' => 'Target Makro',
            'desc'  => 'Set target protein, karbo, dan lemak sesuai kebutuhan. Tersedia preset untuk berbagai tujuan.',
            'color' => '#22C55E',
          ],
        ];
      @endphp

      @foreach($features as $f)
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col items-center text-center">
          <div class="w-24 h-24 flex items-center justify-center mb-5 rounded-2xl"
               style="background-color: {{ $f['color'] }}12;">
            <img src="{{ asset($f['img']) }}" alt="{{ $f['title'] }}"
                 class="w-16 h-16 object-contain select-none">
          </div>
          <h3 class="font-bold text-[#2E471F] text-base mb-2">{{ $f['title'] }}</h3>
          <p class="text-sm text-gray-500 leading-relaxed font-quicksand">{{ $f['desc'] }}</p>
        </div>
      @endforeach

    </div>
  </div>
</section>

{{-- ============================================================
     CARA KERJA  (#community)
============================================================ --}}
<section id="community" class="w-full min-h-screen flex items-center bg-[#F5A623]">
  <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-28 sm:py-40">

    <div class="text-center mb-12">
      <span class="text-sm font-semibold uppercase tracking-widest text-white/70">Cara Kerja</span>
      <h2 class="font-modak text-3xl sm:text-4xl text-white mt-2">Mulai dalam 3 langkah mudah</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">

      {{-- connector line (desktop) --}}
      <div class="hidden md:block absolute top-[4.5rem] left-[calc(16.67%+1rem)] right-[calc(16.67%+1rem)] h-0.5 bg-white/30"></div>

      @php
        $steps = [
          ['no'=>'1','title'=>'Daftar Akun','desc'=>'Buat akun gratis dengan email dan username pilihanmu. Prosesnya cepat, tidak sampai 1 menit.','img'=>'images/step-1-signup.png'],
          ['no'=>'2','title'=>'Lengkapi Profil','desc'=>'Masukkan data tubuh (BB, TB, umur, gender) dan pilih target kalori serta rasio makromu.','img'=>'images/step-2-profile.png'],
          ['no'=>'3','title'=>'Mulai Tracking','desc'=>'Catat makanan harianmu, ikuti program latihan, dan pantau perkembanganmu setiap hari.','img'=>'images/step-3-tracking.png'],
        ];
      @endphp

      @foreach($steps as $s)
        <div class="relative flex flex-col items-center text-center">
          <div class="relative mb-5 z-10">
            <div class="w-36 h-36 rounded-2xl bg-white/20 flex items-center justify-center shadow-lg overflow-hidden">
              <img src="{{ asset($s['img']) }}" alt="{{ $s['title'] }}"
                   class="w-32 h-32 object-contain select-none">
            </div>
            <span class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-[#2E471F] text-white text-xs font-bold flex items-center justify-center shadow">
              {{ $s['no'] }}
            </span>
          </div>
          <h3 class="font-bold text-white text-lg mb-2">{{ $s['title'] }}</h3>
          <p class="text-white/80 text-sm font-quicksand leading-relaxed max-w-xs">{{ $s['desc'] }}</p>
        </div>
      @endforeach

    </div>
  </div>
</section>

{{-- ============================================================
     STATISTIK SINGKAT  (#support)
============================================================ --}}
<section id="support" class="w-full min-h-screen flex items-center bg-[#2E471F] text-white">
  <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-28 sm:py-40">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

      {{-- Left: text --}}
      <div>
        <span class="text-sm font-semibold uppercase tracking-widest text-[#F5A623]">Keunggulan</span>
        <h2 class="font-modak text-3xl sm:text-4xl mt-3 leading-tight">
          Dirancang khusus untuk remaja Indonesia
        </h2>
        <p class="font-quicksand mt-4 text-sm sm:text-base opacity-80 leading-relaxed">
          CaloriMate memahami kebutuhan kalori dan gaya hidup remaja Indonesia. Dari nasi padang sampai
          boba — semua bisa dicatat dan dipantau dengan mudah.
        </p>

        <ul class="mt-6 space-y-3">
          @foreach(['Basis data makanan lokal Indonesia', 'Kalkulasi BMR dengan rumus Mifflin-St Jeor', 'Program latihan dari trainer bersertifikat', 'Target makro yang bisa dikustomisasi'] as $item)
            <li class="flex items-center gap-3 text-sm font-quicksand">
              <span class="w-5 h-5 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </span>
              {{ $item }}
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Right: app mockup --}}
      <div class="flex justify-center lg:justify-end items-center">
        <img src="{{ asset('images/app-mockup.png') }}" alt="CaloriMate App Preview"
             class="w-64 sm:w-72 xl:w-80 h-auto object-contain select-none
                    drop-shadow-[0_20px_60px_rgba(0,0,0,0.35)]">
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     CTA / CONTACT  (#contact)
============================================================ --}}
<section id="contact" class="w-full min-h-screen flex items-center bg-[#EFE6D2]">
  <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-28 sm:py-40 text-center">

    <img src="{{ asset('images/maskotcilik.png') }}" alt="Rima"
         class="h-24 w-24 rounded-full object-cover mx-auto mb-6 ring-4 ring-[#F5A623]/40 shadow-lg">

    <span class="text-sm font-semibold uppercase tracking-widest text-[#F5A623]">Kontak</span>
    <h2 class="font-modak text-3xl sm:text-4xl text-[#2E471F] mt-2">Siap mulai perjalanan sehatmu?</h2>
    <p class="font-quicksand mt-4 text-sm sm:text-base text-gray-600 max-w-md mx-auto leading-relaxed">
      Bergabung sekarang dan mulai lacak kalori, pantau makro, serta ikuti program latihanmu hari ini.
    </p>

    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <a href="{{ url('/register') }}"
         class="inline-flex items-center rounded-full bg-[#2E471F] px-8 py-3 text-sm font-bold text-white shadow-lg hover:bg-[#1e3214] transition">
        Daftar Gratis Sekarang
      </a>
      <a href="{{ url('/login') }}"
         class="inline-flex items-center rounded-full border-2 border-[#2E471F] px-8 py-3 text-sm font-semibold text-[#2E471F] hover:bg-[#2E471F] hover:text-white transition">
        Sudah punya akun? Login
      </a>
    </div>

    <p class="mt-6 text-xs text-gray-400 font-quicksand">
      Dikembangkan oleh mahasiswa S1 Informatika — Telkom University &middot; 2025
    </p>
  </div>
</section>

@if (session('status'))
  <div id="flash-status"
       class="fixed top-20 sm:top-24 left-1/2 -translate-x-1/2 z-50
              bg-emerald-600 text-white px-4 py-2 rounded-full shadow-lg text-sm">
    {{ session('status') }}
  </div>
  <script>setTimeout(() => document.getElementById('flash-status')?.remove(), 3500);</script>
@endif

@endsection

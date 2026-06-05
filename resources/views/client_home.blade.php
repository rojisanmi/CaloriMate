@extends('layouts.verivied-client')

@section('title','Home')

@section('content')

<style>
  /* ── Entrance animations ───────────────────────── */
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes slideRight {
    from { opacity: 0; transform: translateX(-20px); }
    to   { opacity: 1; transform: translateX(0); }
  }
  @keyframes popIn {
    0%   { opacity: 0; transform: scale(0.88) translateY(8px); }
    70%  { transform: scale(1.03) translateY(-2px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
  }
  @keyframes floatHero {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33%       { transform: translateY(-8px) rotate(0.5deg); }
    66%       { transform: translateY(-4px) rotate(-0.3deg); }
  }
  @keyframes glowPulse {
    0%, 100% { opacity: 0.12; transform: scale(1); }
    50%       { opacity: 0.22; transform: scale(1.08); }
  }
  @keyframes barFill {
    from { width: 0%; }
    to   { width: var(--bar-w); }
  }
  @keyframes shimmer {
    0%   { background-position: -200% center; }
    100% { background-position:  200% center; }
  }
  @keyframes navCardIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0)   scale(1); }
  }
  @keyframes iconBounce {
    0%, 100% { transform: scale(1)    rotate(0deg); }
    30%       { transform: scale(1.18) rotate(-4deg); }
    60%       { transform: scale(0.95) rotate(3deg); }
  }

  /* ── Applied classes ───────────────────────────── */
  .anim-slide-up   { animation: slideUp  0.55s cubic-bezier(0.22,1,0.36,1) both; }
  .anim-slide-r    { animation: slideRight 0.5s cubic-bezier(0.22,1,0.36,1) both; }
  .anim-pop        { animation: popIn    0.6s cubic-bezier(0.22,1,0.36,1) both; }

  .hero-img-wrap   { animation: floatHero 6s ease-in-out infinite; }
  .hero-glow       { animation: glowPulse 4s ease-in-out infinite; }

  .bmi-bar         {
    width: 0%;
    animation: barFill 1.1s cubic-bezier(0.22,1,0.36,1) both;
    animation-delay: 0.8s;
  }

  /* Delay utilities */
  .d-0  { animation-delay: 0s; }
  .d-1  { animation-delay: 0.10s; }
  .d-2  { animation-delay: 0.20s; }
  .d-3  { animation-delay: 0.30s; }
  .d-4  { animation-delay: 0.45s; }
  .d-5  { animation-delay: 0.60s; }
  .d-6  { animation-delay: 0.70s; }

  /* Nav card */
  .nav-card {
    animation: navCardIn 0.55s cubic-bezier(0.22,1,0.36,1) both;
    position: relative;
    overflow: hidden;
  }
  .nav-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg,
      transparent 40%,
      rgba(245,166,35,0.18) 50%,
      transparent 60%);
    background-size: 200% 100%;
    opacity: 0;
    transition: opacity 0.2s;
  }
  .nav-card:hover::after {
    opacity: 1;
    animation: shimmer 0.7s ease forwards;
  }
  .nav-card:hover .nav-icon {
    animation: iconBounce 0.5s ease;
  }
  .nav-card:active { transform: scale(0.97) !important; }
</style>

{{-- ── HERO SECTION ─────────────────────────────── --}}
<div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-10 mb-12">

  {{-- Text + BMI --}}
  <div class="flex-1 w-full text-center lg:text-left">

    {{-- Greeting --}}
    <div class="anim-slide-r d-0">
      <p class="text-sm font-semibold text-[#F5A623] uppercase tracking-widest mb-2">
        Selamat datang kembali 👋
      </p>
      <h1 class="font-raleway text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#2E471F] leading-[1.1]">
        Halo,<br class="hidden sm:block">
        <span class="text-[#F5A623]">{{ session('user_name', 'User') }}</span>!
      </h1>
    </div>

    <p class="mt-4 text-gray-500 text-base sm:text-lg anim-slide-r d-1">
      Siap pantau kalori hari ini? Yuk mulai dari sini 💪
    </p>

    {{-- BMI Card --}}
    @php
      $bmiColors = [
        'Underweight' => ['bg' => '#DBEAFE', 'text' => '#1D4ED8', 'bar' => '#3B82F6'],
        'Normal'      => ['bg' => '#DCFCE7', 'text' => '#15803D', 'bar' => '#22C55E'],
        'Overweight'  => ['bg' => '#FEF9C3', 'text' => '#A16207', 'bar' => '#EAB308'],
        'Obese'       => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'bar' => '#EF4444'],
      ];
      $color      = $bmiColors[$category ?? ''] ?? null;
      $bmiPercent = $bmi ? min(100, max(0, (($bmi - 10) / 30) * 100)) : 0;
    @endphp

    <div class="mt-6 anim-pop d-2 max-w-md mx-auto lg:mx-0">
      @if($bmi)
        <div class="flex items-center gap-5 px-6 py-5 rounded-2xl shadow-md"
             style="background-color: {{ $color['bg'] }};">
          <div class="flex-shrink-0">
            <span class="text-5xl sm:text-6xl font-extrabold font-raleway block leading-none bmi-number"
                  data-target="{{ $bmi }}"
                  style="color: {{ $color['text'] }}">{{ $bmi }}</span>
            <span class="text-sm font-semibold block mt-1"
                  style="color: {{ $color['text'] }}">{{ $category }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold uppercase tracking-wider block opacity-80"
                  style="color: {{ $color['text'] }}">Body Mass Index</span>
            <div class="mt-2 h-2.5 rounded-full bg-black/10 overflow-hidden">
              <div class="bmi-bar h-full rounded-full"
                   style="--bar-w: {{ $bmiPercent }}%; background-color: {{ $color['bar'] }}"></div>
            </div>
            <span class="text-xs mt-1.5 opacity-70 block" style="color: {{ $color['text'] }}">
              {{ $client->bb }} kg &middot; {{ $client->tb }} cm
            </span>
          </div>
        </div>
      @else
        <a href="{{ route('profile.client.show') }}"
           class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow bg-white
                  hover:bg-white/90 hover:-translate-y-0.5 transition-all duration-200">
          <svg class="h-8 w-8 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
          </svg>
          <div>
            <p class="text-sm font-semibold text-[#2E471F]">BMI belum tersedia</p>
            <p class="text-xs text-gray-500">Lengkapi profil kamu &rarr;</p>
          </div>
        </a>
      @endif
    </div>
  </div>

  {{-- Hero image --}}
  <div class="flex-shrink-0 anim-pop d-3 relative">
    <div class="hero-glow absolute -inset-6 bg-[#F5A623]/15 rounded-full blur-3xl"></div>
    <div class="hero-img-wrap relative rounded-3xl overflow-hidden shadow-2xl ring-4 ring-white/60">
      <img src="{{ asset('images/dashboard-hero.jpeg') }}" alt="CaloriMate Dashboard"
           class="w-56 sm:w-64 lg:w-72 h-auto object-cover object-[50%_50%] select-none">
    </div>
  </div>

</div>

{{-- ── QUICK NAV ─────────────────────────────────── --}}
@php
  $quickLinks = [
    [
      'label' => 'Diary',
      'route' => 'client.diary',
      'desc'  => 'Catat makananmu',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>',
    ],
    [
      'label' => 'Exercise',
      'route' => 'client.exercise',
      'desc'  => 'Program latihan',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
    ],
    [
      'label' => 'Statistik',
      'route' => 'client.statistic',
      'desc'  => 'Lihat perkembangan',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>',
    ],
    [
      'label' => 'Riwayat',
      'route' => 'client.history',
      'desc'  => 'Histori aktivitas',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
    ],
  ];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
  @foreach($quickLinks as $i => $link)
    <a href="{{ route($link['route']) }}"
       class="nav-card group flex flex-col items-center gap-2.5 p-5 bg-white rounded-2xl shadow-sm
              hover:shadow-lg hover:-translate-y-1 transition-all duration-300 d-{{ $i + 3 }}">

      {{-- Icon circle --}}
      <div class="nav-icon h-12 w-12 rounded-xl bg-[#2E471F]/8 group-hover:bg-[#F5A623]/20
                  flex items-center justify-center transition-all duration-300
                  group-hover:scale-110">
        <svg class="h-6 w-6 text-[#2E471F] group-hover:text-[#F5A623] transition-colors duration-300"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          {!! $link['icon'] !!}
        </svg>
      </div>

      <div class="text-center">
        <span class="block text-xs sm:text-sm font-bold text-[#2E471F] group-hover:text-[#F5A623]
                     transition-colors duration-300">
          {{ $link['label'] }}
        </span>
        <span class="block text-[10px] text-gray-400 mt-0.5 leading-tight">
          {{ $link['desc'] }}
        </span>
      </div>

      {{-- Bottom accent line --}}
      <div class="w-0 group-hover:w-8 h-0.5 rounded-full bg-[#F5A623]
                  transition-all duration-300 ease-out"></div>
    </a>
  @endforeach
</div>

@endsection

@extends('layouts.verivied-client')

@section('title','Exercise')

@section('content')

{{-- FLASH MESSAGE --}}
@if(session('ok'))
<div id="flash-msg"
     class="fixed top-20 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-[#2E471F] text-white shadow-lg cm-fadein max-w-sm">
  <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24"
       stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
  </svg>
  <span class="text-sm font-medium flex-1">{{ session('ok') }}</span>
  <button onclick="document.getElementById('flash-msg').remove()"
          class="text-white/50 hover:text-white transition-colors">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
  </button>
</div>
<script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
@endif

{{-- BANNER --}}
<div class="relative mb-6 rounded-2xl overflow-hidden shadow-md cm-fadein">
  <img src="{{ asset('images/exercise-banner.jpeg') }}" alt="Exercise"
       class="w-full h-40 sm:h-52 object-cover object-[50%_48%] select-none">
  <div class="absolute inset-0 bg-gradient-to-r from-[#2E471F]/90 via-[#2E471F]/50 to-transparent"></div>
  <div class="absolute inset-0 flex flex-col justify-center px-6 sm:px-10">
    <h1 class="font-raleway text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
      Program Latihan
    </h1>
    <p class="text-sm sm:text-base text-white/85 mt-1 max-w-md">
      Pilih program yang sesuai dengan tujuan fitness kamu
    </p>
  </div>
</div>

{{-- REKOMENDASI LATIHAN --}}
@if(count($recommendations) > 0)
  <div class="mb-8 cm-fadein cm-delay-1">
    <div class="mb-3">
      <h2 class="font-raleway text-base font-bold text-[#2E471F] flex items-center gap-2">
        <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
        </svg>
        Rekomendasi Latihan Untukmu
      </h2>
      <p class="text-xs text-gray-400 mt-0.5">Dipilih berdasarkan BMI dan kondisi kalori kamu hari ini</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      @php
        $tagColors = [
          'Bakar kalori berlebih'   => ['bg' => '#FEE2E2', 'text' => '#B91C1C'],
          'Aman untuk Underweight'  => ['bg' => '#DBEAFE', 'text' => '#1D4ED8'],
          'Efektif turunkan berat'  => ['bg' => '#DCFCE7', 'text' => '#15803D'],
          'Cocok untuk pemula'      => ['bg' => '#F0FDF4', 'text' => '#166534'],
          'Intensitas ideal'        => ['bg' => '#F5F3FF', 'text' => '#6D28D9'],
          'Tantangan tinggi'        => ['bg' => '#FEF9C3', 'text' => '#A16207'],
        ];
      @endphp

      @foreach($recommendations as $rec)
        @php
          $rp  = $rec['program'];
          $rtag = $rec['tag'];
          $rtc  = $tagColors[$rtag] ?? ['bg' => '#EFE6D2', 'text' => '#2E471F'];

          $diffVal = strtolower($rp['difficulty'] ?? '');
          if (in_array($diffVal, ['low','rendah','beginner','pemula','mudah','easy'])) {
            $diffLabel = 'Mudah'; $diffBg = '#DCFCE7'; $diffText = '#15803D';
          } elseif (in_array($diffVal, ['high','tinggi','advanced','lanjutan','hard','sulit'])) {
            $diffLabel = 'Sulit'; $diffBg = '#FEE2E2'; $diffText = '#B91C1C';
          } else {
            $diffLabel = 'Sedang'; $diffBg = '#FEF9C3'; $diffText = '#A16207';
          }
        @endphp

        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
          {{-- Top accent --}}
          <div class="h-1 bg-gradient-to-r from-[#F5A623] to-[#2E471F]"></div>

          <div class="p-5 flex flex-col gap-3">
            {{-- Title + difficulty --}}
            <div class="flex items-start justify-between gap-2">
              <h3 class="font-bold text-[#2E471F] text-sm leading-tight line-clamp-2">{{ $rp['title'] }}</h3>
              <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full"
                    style="background-color: {{ $diffBg }}; color: {{ $diffText }}">
                {{ $diffLabel }}
              </span>
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-[#F5A623]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ $rp['duration'] ?? '—' }} menit
              </span>
              <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-[#F5A623]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/>
                </svg>
                ~{{ $rp['estimated_calories'] }} kkal
              </span>
            </div>

            {{-- Reason tag --}}
            <span class="inline-flex self-start text-[10px] font-semibold px-2 py-0.5 rounded-full"
                  style="background-color: {{ $rtc['bg'] }}; color: {{ $rtc['text'] }}">
              {{ $rtag }}
            </span>

            {{-- CTA --}}
            <a href="{{ route('client.exercise.show', $rp['id']) }}"
               class="mt-auto flex items-center justify-center gap-1.5 py-2.5 rounded-xl
                      bg-[#2E471F] text-white text-xs font-semibold
                      hover:bg-[#3d6628] transition-colors">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/>
              </svg>
              Lihat Program
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif

{{-- DIVIDER --}}
@if(count($recommendations) > 0)
  <div class="flex items-center gap-3 mb-6">
    <div class="flex-1 h-px bg-gray-200"></div>
    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Semua Program</span>
    <div class="flex-1 h-px bg-gray-200"></div>
  </div>
@endif

{{-- PROGRAM GRID --}}
@if(count($programs) > 0)
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($programs as $i => $program)
      <a href="{{ route('client.exercise.show', $program['id']) }}"
         class="group bg-white rounded-2xl overflow-hidden shadow-sm
                hover:shadow-lg hover:-translate-y-1 transition-all duration-250
                cm-fadein cm-delay-{{ min($i + 1, 4) }}">

        {{-- Card top accent --}}
        <div class="h-1.5 bg-gradient-to-r from-[#2E471F] to-[#F5A623]"></div>

        <div class="p-5">
          {{-- Icon --}}
          <div class="h-12 w-12 rounded-xl bg-[#F5A623]/15 group-hover:bg-[#F5A623]/25
                      flex items-center justify-center mb-4 transition-colors duration-200">
            <svg class="h-6 w-6 text-[#F5A623]" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
            </svg>
          </div>

          {{-- Title --}}
          <h3 class="font-bold text-[#2E471F] text-base mb-2 group-hover:text-[#F5A623]
                     transition-colors duration-200 line-clamp-2">
            {{ $program['title'] }}
          </h3>

          <p class="text-xs text-gray-400 mb-4 line-clamp-2">
            Program latihan yang dirancang khusus untuk mencapai target fitness kamu.
          </p>

          {{-- CTA --}}
          <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <span class="text-xs font-semibold text-[#2E471F] group-hover:text-[#F5A623]
                         transition-colors duration-200">
              Lihat Detail
            </span>
            <div class="h-7 w-7 rounded-full bg-[#EFE6D2] group-hover:bg-[#F5A623]
                        flex items-center justify-center transition-all duration-200
                        group-hover:translate-x-0.5">
              <svg class="h-4 w-4 text-[#2E471F]" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </div>

      </a>
    @endforeach
  </div>
@else
  <div class="flex flex-col items-center justify-center py-20 text-center cm-fadein cm-delay-1">
    <div class="h-16 w-16 rounded-2xl bg-[#EFE6D2] flex items-center justify-center mb-4">
      <svg class="h-8 w-8 text-[#2E471F]/40" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
      </svg>
    </div>
    <p class="text-gray-400 text-sm">Belum ada program latihan tersedia.</p>
  </div>
@endif

@endsection

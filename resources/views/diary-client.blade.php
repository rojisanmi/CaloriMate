@extends('layouts.verified-client')

@section('title','Diary')

@section('content')
<section class="relative text-center">

    {{-- ORANGE CURVE TOP --}}
    <div class="absolute inset-x-0 -top-10 h-48 bg-[#F4A938] rounded-b-[60%]"></div>

    {{-- SUMMARY CARD --}}
    <div class="relative z-10 flex justify-center mt-6">
        <div class="bg-[#2E471F] text-white rounded-xl px-6 py-4 shadow-lg w-[260px]">
            <p class="text-sm">Sisa Kalori</p>
            <p class="text-xl font-bold">{{ $remainingCalories }}</p>
            <div class="h-px bg-white/30 my-2"></div>
            <p class="text-sm">Konsumsi Kalori</p>
            <p class="text-xl font-bold">{{ $consumedCalories }}</p>
        </div>
    </div>

    {{-- SEGMENT LIST --}}
    <div class="relative z-10 mt-14 flex flex-col gap-4 max-w-md mx-auto">

        {{-- ITEM --}}
        @php
          $segments = [
            ['label'=>'Makan Pagi', 'icon'=>'🌅'],
            ['label'=>'Makan Siang', 'icon'=>'☀️'],
            ['label'=>'Makan Malam', 'icon'=>'🌇'],
            ['label'=>'Camilan/Lainnya', 'icon'=>'🌙'],
          ];
        @endphp

        @foreach($segments as $segment)
        <div class="flex items-center justify-between bg-[#EFE6D2] px-5 py-4 rounded-xl shadow">
            <div class="flex items-center gap-3 text-[#2E471F] font-semibold">
                <span class="text-xl">{{ $segment['icon'] }}</span>
                <span>{{ $segment['label'] }}</span>
            </div>

            {{-- ADD BUTTON --}}
            <button
              class="h-7 w-7 flex items-center justify-center
                     rounded bg-[#2E471F] text-white font-bold
                     hover:bg-[#243A18]">
              +
            </button>
        </div>
        @endforeach

    </div>

    {{-- ORANGE CURVE BOTTOM --}}
    <div class="mt-16 h-28 bg-[#F4A938] rounded-t-[60%]"></div>

</section>
@endsection

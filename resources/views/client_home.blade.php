@extends('layouts.verivied-client')

@section('title','Client Home')

@section('content')
<section class="relative min-h-[calc(100vh-180px)] flex flex-col -mx-10 sm:-mx-12 lg:-mx-14">
    {{-- Text Content - DIPERBAIKI DENGAN RELATIVE DAN Z-INDEX --}}
    <div class="text-center pt-8 sm:pt-12 md:pt-34 px-4 relative z-10">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#000000]"
            style="font-family: 'Raleway', sans-serif; font-weight: 600;">
            Hello {{ $user->name ?? 'User' }},
        </h1>

        <p class="mt-3 sm:mt-15 text-lg sm:text-xl md:text-2xl text-[#FFFFFF]/90"
           style="font-family: 'Quicksand', sans-serif; font-weight: 400;">
            ready to track your calories today?
        </p>

        {{-- Spacer untuk mendorong gelombang ke bawah --}}
        <div class="flex-1"></div>
    </div>

    {{-- Gelombang Orange di Bawah - DIPERBAIKI DENGAN Z-0 --}}
    <div class="absolute -bottom-10 left-0 right-0 w-full z-0">
        {{-- SVG Gelombang --}}
        <svg class="w-full h-auto" viewBox="0 0 1800 550" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="display: block;">
            <path 
                fill="#F5A623" 
                fill-opacity="1" 
                d="M0,160 C240,100 480,220 720,180 C960,140 1200,200 1800,160 L1800,550 L0,550 Z">
            </path>
        </svg>
    </div>
</section>
@endsection
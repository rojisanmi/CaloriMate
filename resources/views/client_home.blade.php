@extends('layouts.verivied-client')

@section('title','Client Home')

@section('content')
<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fadeInDown {
        animation: fadeInDown 0.8s ease-out;
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-fadeInLeft {
        animation: fadeInLeft 1s ease-out;
    }

    .animate-fadeInRight {
        animation: fadeInRight 1s ease-out;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .animate-scaleIn {
        animation: scaleIn 0.6s ease-out;
    }

    .delay-200 {
        animation-delay: 0.2s;
        animation-fill-mode: both;
    }

    .delay-400 {
        animation-delay: 0.4s;
        animation-fill-mode: both;
    }

    .delay-600 {
        animation-delay: 0.6s;
        animation-fill-mode: both;
    }

    .smoothie-bowl-shadow {
        box-shadow: 0 20px 60px rgba(245, 166, 35, 0.3);
    }

    .gradient-overlay {
        background: linear-gradient(135deg, rgba(245, 166, 35, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    }
</style>

<section class="relative min-h-[calc(100vh-180px)] flex flex-col 
                -mx-10 sm:-mx-12 lg:-mx-14 overflow-visible pb-[200px]">


    
    {{-- Main Content Container --}}
    <div class="relative z-10 flex-1 flex flex-col lg:flex-row items-center justify-between px-6 sm:px-12 lg:px-20 pt-8 sm:pt-12 lg:pt-20 pb-">
        
        {{-- Left Side - Text Content --}}
        <div class="flex-1 text-center lg:text-left mb-12 lg:mb-0 max-w-2xl">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#000000] animate-fadeInDown"
                style="font-family: 'Raleway', sans-serif; font-weight: 600;">
                Hello <span class="text-[#F5A623]">{{ $user->name ?? 'User' }}</span>,
            </h1>

            <p class="mt-6 text-xl sm:text-2xl lg:text-3xl text-[#4A4A4A] animate-fadeInDown delay-200"
               style="font-family: 'Quicksand', sans-serif; font-weight: 400;">
                ready to track your calories today?
            </p>


        </div>

        {{-- Right Side - Smoothie Bowl Image --}}
        <div class="flex-1 flex justify-center lg:justify-end max-w-xl animate-fadeInRight delay-400">
            <div class="relative">
                {{-- Decorative Circle Background --}}
                <div class="absolute inset-0 bg-[#F5A623]/10 rounded-full blur-3xl animate-pulse"></div>
                
                {{-- Main Image Container --}}
                <div class="relative smoothie-bowl-shadow rounded-3xl overflow-hidden animate-float">
                <img src="{{ asset('images/Berry Bliss.jpg') }}" 
     alt="Healthy Smoothie Bowl" 
     class="w-full h-auto rounded-3xl"
     style="max-width: 320px;">
                    
                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 gradient-overlay rounded-3xl"></div>
                    
                    {{-- Floating Badge --}}
                    <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-sm rounded-full px-6 py-3 shadow-lg animate-scaleIn delay-600">
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl">🥗</span>
                            <span class="font-bold text-[#F5A623]" style="font-family: 'Raleway', sans-serif;">
                                Aja Klalen Mangan
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Decorative Elements --}}
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-[#F5A623]/20 rounded-full blur-xl animate-pulse"></div>
                <div class="absolute -top-4 -right-4 w-32 h-32 bg-[#F5A623]/15 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>
        </div>
    </div>

    {{-- Gelombang Orange di Bawah - TIDAK DIUBAH --}}
    <div class="absolute -bottom-10 -left-20 -right-20 w-[calc(100%+160px)] z-0 pointer-events-none">
    {{-- SVG Gelombang --}}
    <svg class="block w-full h-[550px] -mb-[1px]" viewBox="-200 0 2200 550" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path 
            fill="#F5A623" 
            fill-opacity="1" 
            d="M-200,160 C40,100 280,220 520,180 C760,140 1000,200 1800,160 C2000,140 2200,160 2200,160 L2200,550 L-200,550 Z">
        </path>
    </svg>
</div>
</section>
@endsection
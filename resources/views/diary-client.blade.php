@extends('layouts.verivied-client')

@section('title', 'Diary')

@section('content')
<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.5s ease-out;
    }

    .animate-delay-1 {
        animation-delay: 0.1s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-2 {
        animation-delay: 0.2s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-3 {
        animation-delay: 0.3s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-4 {
        animation-delay: 0.4s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    @keyframes popIn {
        0% {
            opacity: 0;
            transform: scale(0.3) rotate(-10deg);
        }
        50% {
            transform: scale(1.1) rotate(5deg);
        }
        70% {
            transform: scale(0.95) rotate(-2deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes sparkle {
        0%, 100% {
            opacity: 0;
            transform: scale(0) rotate(0deg);
        }
        50% {
            opacity: 1;
            transform: scale(1) rotate(180deg);
        }
    }

    @keyframes slideOutRight {
        0% {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateX(120%) scale(0.8);
        }
    }

    .flash-message {
        animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .flash-message:hover {
        animation: float 1.5s ease-in-out infinite;
    }

    .flash-message::before,
    .flash-message::after {
        content: '✨';
        position: absolute;
        font-size: 20px;
        animation: sparkle 1.5s ease-in-out infinite;
    }

    .flash-message::before {
        top: -10px;
        left: 10px;
        animation-delay: 0.3s;
    }

    .flash-message::after {
        bottom: -10px;
        right: 10px;
        animation-delay: 0.6s;
    }

    .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #34d399);
        border-radius: 0 0 0 16px;
        animation: shrink 4s linear forwards;
    }

    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>

<section class="relative text-center px-4 sm:px-6 py-6">

    {{-- ORANGE CURVE TOP --}}
    <div class="absolute -inset-x-8 -top-24 h-40 sm:h-48 bg-[#F4A938] rounded-b-[60%] -z-10 animate-fade-in-down"></div>

    {{-- FLASH MESSAGE SUCCESS --}}
@if(session('ok'))
    <div id="flash-message"
         class="flash-message fixed top-4 left-1/2 -translate-x-1/2 z-50
                max-w-md w-[90%] sm:w-full relative overflow-hidden
                bg-gradient-to-r from-green-400 via-emerald-400 to-teal-400 
                text-white
                px-6 py-4 rounded-2xl shadow-2xl
                flex items-center gap-3">
        
        <span class="text-3xl animate-bounce">🎉</span>
        <span class="text-sm font-bold flex-1">{{ session('ok') }}</span>
        <button onclick="closeFlash()" 
                class="text-white/80 hover:text-white transition-colors text-xl font-bold">
            ×
        </button>
        
        <div class="progress-bar"></div>
    </div>

    <script>
        function closeFlash() {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.animation = 'slideOutRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards';
                setTimeout(() => flash.remove(), 500);
            }
        }

        setTimeout(closeFlash, 4000);
    </script>
@endif


    {{-- SUMMARY CARD --}}
    <div class="relative z-10 flex justify-center mt-2 pt-6 animate-scale-in">
        <div class="bg-[#2E471F] text-white rounded-xl px-5 sm:px-6 py-4 shadow-lg w-full max-w-[260px] sm:w-[260px]">
            <p class="text-sm">Sisa Kalori</p>
            <p class="text-xl font-bold">{{ $remainingCalories }}</p>
            <div class="h-px bg-white/30 my-2"></div>
            <p class="text-sm">Konsumsi Kalori</p>
            <p class="text-xl font-bold">{{ $consumedCalories }}</p>
        </div>
    </div>

    {{-- SEGMENT LIST --}}
    <div class="relative z-10 mt-12 flex flex-col gap-4 w-full max-w-md mx-auto">

        @php
            $segments = [
                ['label' => 'Makan Pagi', 'icon' => '🌅', 'category' => 'breakfast'],
                ['label' => 'Makan Siang', 'icon' => '☀️', 'category' => 'lunch'],
                ['label' => 'Makan Malam', 'icon' => '🌇', 'category' => 'dinner'],
                ['label' => 'Camilan/Lainnya', 'icon' => '🌙', 'category' => 'snack'],
            ];
        @endphp

        @foreach($segments as $index => $segment)
            <div class="flex items-center justify-between bg-white px-4 sm:px-5 py-3 sm:py-4 rounded-xl 
                shadow transition-all duration-200 ease-out
                hover:shadow-xl hover:-translate-y-1 hover:ring-1 hover:ring-gray-200
                animate-fade-in-up animate-delay-{{ $index + 1 }}">
                <div class="flex items-center gap-3 text-[#2E471F] font-semibold">
                    <span class="text-lg sm:text-xl">{{ $segment['icon'] }}</span>
                    <span class="text-sm sm:text-base">{{ $segment['label'] }}</span>
                </div>

                {{-- ADD BUTTON --}}
                <a href="{{ route('client.diary.add', $segment['category']) }}"
                   class="h-7 w-7 flex items-center justify-center rounded-md bg-[#2E471F] text-white font-bold text-lg
                  transition-all duration-200 ease-in-out
                  hover:bg-[#3D6B2A] hover:shadow-[0_0_8px_2px_rgba(46,71,31,0.6)]
                  active:scale-95
                  focus:outline-none focus:ring-2 focus:ring-[#2E471F] focus:ring-opacity-50">
                    +
                </a>
            </div>
        @endforeach

    </div>

    {{-- ORANGE CURVE BOTTOM --}}
    <div class="absolute -inset-x-8 -bottom-8 sm:-bottom-10 h-16 sm:h-20 bg-[#F4A938] rounded-t-[60%] -z-10 animate-fade-in-up"></div>

</section>
@endsection
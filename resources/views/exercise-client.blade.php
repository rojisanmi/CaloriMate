@extends('layouts.verivied-client')

@section('title','Exercise')

@section('content')

<style>
    /* Animation Keyframes */
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
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes popIn {
        0% {
            opacity: 0;
            transform: translate(-50%, -50px) scale(0.3) rotate(-10deg);
        }
        50% {
            transform: translate(-50%, 0) scale(1.1) rotate(5deg);
        }
        70% {
            transform: translate(-50%, 0) scale(0.95) rotate(-2deg);
        }
        100% {
            opacity: 1;
            transform: translate(-50%, 0) scale(1) rotate(0deg);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translate(-50%, 0px);
        }
        50% {
            transform: translate(-50%, -8px);
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
            transform: translate(-50%, 0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translate(50%, 0) scale(0.8);
        }
    }

    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }

    /* Animation Classes */
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out both;
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

    /* Custom Scrollbar - Modern & Minimal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #2E471F;
        border-radius: 100px;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #1a2912;
        opacity: 1;
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth page entrance */
    @media (prefers-reduced-motion: no-preference) {
        * {
            scroll-behavior: smooth;
        }
    }
</style>

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

<section class="max-w-7xl mx-auto px-4 py-8">

    {{-- HEADER SECTION --}}
    <div class="mb-10 animate-fade-in-down">
        <h1 class="text-4xl font-bold text-[#2E471F] mb-2">
            Program Latihan
        </h1>
        <p class="text-gray-600 text-lg">
            Pilih program yang sesuai dengan tujuan fitness Anda
        </p>
    </div>

    {{-- PROGRAM GRID --}}
    <div class="overflow-y-auto max-h-[600px] pr-2 custom-scrollbar">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-4">

            @foreach($programs as $index => $program)
            <a href="{{ route('client.exercise.show', $program['id']) }}" 
               class="group relative bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl 
                      transition-all duration-300 hover:-translate-y-1 border border-gray-100
                      animate-fade-in-up"
               style="animation-delay: {{ $index * 0.1 }}s">
                
                {{-- Decorative gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-br from-[#2E471F]/5 to-transparent 
                            opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                {{-- Card Content --}}
                <div class="relative p-6">
                    
                    {{-- Icon Circle --}}
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#F4A938] to-[#E89820] 
                                flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6
                                transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white group-hover:scale-110 transition-transform duration-300" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    
                    {{-- Title --}}
                    <h3 class="font-bold text-xl text-[#2E471F] mb-3 group-hover:text-[#F4A938] 
                               transition-colors duration-300">
                        {{ $program['title'] }}
                    </h3>
                    
                    {{-- Description placeholder --}}
                    <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                        Program latihan yang dirancang khusus untuk mencapai target fitness Anda
                    </p>
                    
                    {{-- Action Button --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-[#2E471F] text-sm font-semibold group-hover:text-[#F4A938] 
                                     transition-colors">
                            Lihat Detail
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#2E471F] group-hover:bg-[#F4A938] 
                                    flex items-center justify-center transition-all duration-300 
                                    group-hover:translate-x-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    
                </div>

            </a>
            @endforeach

        </div>
    </div>

</section>

@endsection
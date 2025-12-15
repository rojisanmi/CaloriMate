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
</style>

<section class="relative text-center px-4 sm:px-6 py-6">

    {{-- ORANGE CURVE TOP --}}
    <div class="absolute -inset-x-8 -top-24 h-40 sm:h-48 bg-[#F4A938] rounded-b-[60%] -z-10 animate-fade-in-down"></div>

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
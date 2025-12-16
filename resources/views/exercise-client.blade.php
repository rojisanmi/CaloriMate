@extends('layouts.verivied-client')

@section('title','Exercise')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">

    {{-- POPUP SUCCESS NOTIFICATION --}}
    @if (session('ok'))
    <div id="alert-success"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999]
                rounded-xl bg-emerald-500 text-white px-6 py-4 shadow-2xl text-center
                border border-emerald-400 backdrop-blur-sm animate-slide-down">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 animate-bounce-subtle" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('ok') }}</span>
      </div>
    </div>

    <script>
      setTimeout(() => {
        const el = document.getElementById('alert-success');
        if (el) {
          el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
          el.style.opacity = '0';
          el.style.transform = 'translate(-50%, -20px)';
          setTimeout(() => el.remove(), 400);
        }
      }, 3000);
    </script>
    @endif

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

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translate(-50%, -30px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    @keyframes bounceSubtle {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    /* Animation Classes */
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out both;
    }

    .animate-slide-down {
        animation: slideDown 0.5s ease-out;
    }

    .animate-bounce-subtle {
        animation: bounceSubtle 1s ease-in-out 2;
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
@endsection
@extends('layouts.verivied-client')

@section('title','Exercise')

@section('content')
<section class="max-w-6xl mx-auto px-4">

    {{-- POPUP SUCCESS NOTIFICATION --}}
    @if (session('ok'))
    <div id="alert-success"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999]
                rounded-lg bg-emerald-600 text-white px-6 py-3 shadow-lg text-center">
      {{ session('ok') }}
    </div>

    <script>
      // otomatis hilang perlahan setelah 3 detik
      setTimeout(() => {
        const el = document.getElementById('alert-success');
        if (el) {
          el.style.transition = 'opacity 0.4s ease';
          el.style.opacity = '0';
          setTimeout(() => el.remove(), 400);
        }
      }, 3000);
    </script>
    @endif

    <div class="bg-[#FFFFFF] rounded-[40px] p-8 shadow max-w-5xl mx-auto">

        {{-- TITLE --}}
        <h2 class="text-center text-3xl font-extrabold text-[#2E471F] mb-8">
            Program Latihan
        </h2>

        {{-- PROGRAM GRID WITH SCROLLING --}}
        <div class="overflow-y-auto max-h-[500px] pr-2 scrollbar-thin scrollbar-thumb-[#2E471F] scrollbar-track-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-2">

                @foreach($programs as $program)
                <a href="{{ route('client.exercise.show', $program['id']) }}" 
                class="group block bg-gradient-to-br from-[#F4A938] to-[#E89820] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 min-h-[180px] flex items-center">
                    
                    {{-- Card Content --}}
                    <div class="p-8 w-full">
                        <h4 class="font-bold text-2xl text-white mb-4 group-hover:text-[#2E471F] transition-colors">
                            {{ $program['title'] }}
                        </h4>
                        
                        <div class="flex items-center justify-between mt-6">
                            <span class="text-white/90 text-sm font-medium">
                                Lihat Detail
                            </span>
                            <svg class="w-5 h-5 text-white group-hover:translate-x-1 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>

                </a>
                @endforeach

            </div>
        </div>

    </div>

</section>

<style>
    /* Custom Scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 8px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #2E471F;
        border-radius: 10px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #1a2912;
    }
</style>
@endsection
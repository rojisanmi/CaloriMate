@extends('layouts.verivied')

@section('title', 'Kelola Program Latihan')

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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
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

    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
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

    /* Staggered animation for cards */
    .card-stagger {
        opacity: 0;
        animation: scaleIn 0.5s ease-out forwards;
    }

    .card-stagger:nth-child(1) { animation-delay: 0.1s; }
    .card-stagger:nth-child(2) { animation-delay: 0.15s; }
    .card-stagger:nth-child(3) { animation-delay: 0.2s; }
    .card-stagger:nth-child(4) { animation-delay: 0.25s; }
    .card-stagger:nth-child(5) { animation-delay: 0.3s; }
    .card-stagger:nth-child(6) { animation-delay: 0.35s; }
    .card-stagger:nth-child(7) { animation-delay: 0.4s; }
    .card-stagger:nth-child(8) { animation-delay: 0.45s; }
    .card-stagger:nth-child(n+9) { animation-delay: 0.5s; }
</style>

  {{-- ALERT SUCCESS --}}
  @if (session('ok'))
    <div id="alert-success"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999]
                rounded-lg bg-green-500 text-white px-6 py-3 shadow-lg text-center
                animate-fade-in-down">
      {{ session('ok') }}
    </div>

    <script>
      setTimeout(() => {
        const el = document.getElementById('alert-success');
        if (el) {
          el.style.transition = 'opacity 0.5s ease';
          el.style.opacity = '0';
          setTimeout(() => el.remove(), 500);
        }
      }, 3000);
    </script>
  @endif

  {{-- TITLE --}}
  <div class="mb-6 animate-fade-in-down">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">
      Kelola Program Latihan
    </h1>
  </div>

  {{-- SEARCH BAR --}}
  <div class="mb-6 animate-slide-in-left animate-delay-1">
    <form method="GET"
          action="{{ route('trainer.programs.index') }}"
          class="w-full max-w-sm">
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari program"
             class="w-full rounded-lg border border-gray-300 bg-white
                    px-4 py-2 text-gray-700 placeholder-gray-400
                    focus:border-[#2E471F] focus:outline-none
                    transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/30">
    </form>
  </div>

  {{-- PROGRAM CARDS --}}
  <div class="bg-white p-6 rounded-lg shadow-md
              max-h-[65vh] overflow-y-auto
              animate-fade-in-up animate-delay-2">

    {{-- CARDS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

      {{-- ADD CARD --}}
      <a href="{{ route('trainer.programs.create') }}"
         class="group flex items-center justify-center rounded-lg
                border-2 border-dashed border-gray-300 p-6
                cursor-pointer hover:border-green-600 
                transition-all duration-300 hover:shadow-lg hover:scale-[1.02]
                card-stagger">
        <div class="text-center">
          <div class="mx-auto mb-3 h-12 w-12 flex items-center justify-center
                      rounded-full border border-gray-300
                      group-hover:border-green-600 transition-all duration-300
                      group-hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-7 w-7 text-gray-500 group-hover:text-green-600 transition-colors duration-300"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-gray-700 group-hover:text-green-600 transition-colors duration-300">
            Tambah Program Baru
          </div>
          <div class="text-xs text-gray-400 mt-1">
            Klik untuk membuat program latihan
          </div>
        </div>
      </a>

      {{-- PROGRAM CARDS --}}
      @forelse ($programs as $program)
        <div class="flex flex-col justify-between rounded-lg border bg-white shadow-sm
                    transition-all duration-300 ease-out
                    hover:scale-[1.02] hover:shadow-lg
                    card-stagger">

          <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
              {{ $program->name }}
            </h3>

            @if(!empty($program->description))
              <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                {{ \Illuminate\Support\Str::limit($program->description, 120) }}
              </p>
            @else
              <p class="text-sm text-gray-600 mb-3">
                <span class="font-medium">Tipe:</span> {{ $program->type ?? '-' }}<br>
                <span class="font-medium">Tingkat:</span> {{ $program->difficulty ?? '-' }}<br>
                <span class="font-medium">Durasi:</span>
                {{ $program->total_duration > 0 ? $program->total_duration . ' menit' : '-' }}
              </p>
            @endif
          </div>

          <div class="border-t px-5 py-3 bg-gray-50 rounded-b-lg">
            <div class="flex items-center justify-between gap-3">
              <a href="{{ route('trainer.programs.show', $program) }}"
                 class="inline-flex items-center justify-center rounded-md
                        px-3 py-2 text-sm font-semibold
                        bg-[#2E7D32] text-white hover:bg-[#1B5E20]
                        transition-all duration-200 hover:shadow-md
                        active:scale-95">
                Lihat Detail
              </a>

              <a href="{{ route('trainer.programs.edit', $program) }}"
                 class="inline-flex items-center rounded-md
                        px-3 py-2 text-sm font-semibold
                        bg-white border border-gray-200
                        text-gray-700 hover:bg-gray-100 hover:shadow-md
                        transition-all duration-200 active:scale-95">
                Edit
              </a>
            </div>
          </div>

        </div>
      @empty
        <div class="col-span-full text-center py-12 text-gray-500">
          Belum ada program selain tombol tambah.
        </div>
      @endforelse

    </div>

    {{-- PAGINATION --}}
    @if(method_exists($programs, 'links'))
      <div class="mt-6">
        {{ $programs->appends(['q' => request('q')])->links() }}
      </div>
    @endif

  </div>
@endsection
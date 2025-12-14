@extends('layouts.verivied')

@section('title', 'Kelola Program Latihan')

@section('content')
  @if (session('ok'))
  <div id="alert-success"
       class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999]
              rounded-lg bg-green-500 text-white px-6 py-3 shadow-lg
              text-center animate-fade-in-down">
    {{ session('ok') }}
  </div>

  <script>
    // otomatis hilang perlahan setelah 3 detik
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
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Kelola Program Latihan</h1>
  </div>

  {{-- Filter bar: search --}}
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('trainer.programs.index') }}" class="w-full sm:max-w-sm">
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari program"
             class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 placeholder-gray-400 focus:border-[#2E471F] focus:outline-none">
    </form>

   
  </div>

  {{-- CARDS GRID --}}
  <div class="bg-white p-6 rounded-lg shadow-md">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      {{-- Add card (always visible) --}}
      <a href="{{ route('trainer.programs.create') }}"
         class="group flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-6 cursor-pointer hover:border-green-600 transition-colors">
        <div class="text-center">
          <div class="mx-auto mb-3 h-12 w-12 flex items-center justify-center rounded-full border border-gray-300 group-hover:border-green-600">
            {{-- plus icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-500 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </div>
          <div class="text-sm font-semibold text-gray-700">Tambah Program Baru</div>
          <div class="text-xs text-gray-400 mt-1">Klik untuk membuat program latihan</div>
        </div>
      </a>

      {{-- Program cards --}}
      @forelse ($programs as $program)
        <div class="flex flex-col justify-between rounded-lg border bg-white shadow-sm 
            transition-transform duration-300 ease-out 
            hover:scale-102 hover:shadow-lg">
          <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $program->name }}</h3>

            @if(!empty($program->description))
              <p class="text-sm text-gray-600 mb-3 line-clamp-3">{{ \Illuminate\Support\Str::limit($program->description, 120) }}</p>
            @else
              <p class="text-sm text-gray-600 mb-3">
                <span class="font-medium">Tipe:</span> {{ $program->type ?? '-' }}<br>
                <span class="font-medium">Tingkat:</span> {{ $program->difficulty ?? '-' }}<br>
                <span class="font-medium">Durasi:</span> {{ $program->duration_minutes ? $program->duration_minutes . ' menit' : '-' }}
              </p>
            @endif
          </div>

          <div class="border-t px-5 py-3 bg-gray-50 rounded-b-lg">
            <div class="flex items-center justify-between gap-3">
              <a href="{{ route('trainer.programs.show', $program) }}"
                 class="inline-flex items-center justify-center rounded-md px-3 py-2 text-sm font-semibold bg-[#2E7D32] text-white hover:opacity-90">
                Lihat Detail
              </a>

              {{-- Edit button (menggantikan tampilan durasi kecil) --}}
              <div class="flex items-center gap-2">
                <a href="{{ route('trainer.programs.edit', $program) }}"
                   class="inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-100">
                  Edit
                </a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12 text-gray-500">
          Belum ada program lain selain tombol tambah. Klik <span class="font-semibold text-gray-700">Tambah Program Baru</span> untuk membuatnya.
        </div>
      @endforelse
    </div>

    {{-- pagination --}}
    @if(method_exists($programs, 'links'))
      <div class="mt-6">{{ $programs->appends(['q' => request('q')])->links() }}</div>
    @endif
  </div>
@endsection

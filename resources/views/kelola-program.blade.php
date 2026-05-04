@extends('layouts.verivied')

@section('title', 'Kelola Program Latihan')

@section('content')

{{-- FLASH --}}
@if(session('ok'))
<div id="flash-msg"
     class="fixed top-20 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-[#2E471F] text-white shadow-lg cm-fadein max-w-sm">
  <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24"
       stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
  </svg>
  <span class="text-sm font-medium flex-1">{{ session('ok') }}</span>
  <button onclick="document.getElementById('flash-msg').remove()"
          class="text-white/50 hover:text-white transition-colors">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
  </button>
</div>
<script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
@endif

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 cm-fadein">
  <div>
    <h1 class="font-raleway text-2xl font-bold text-[#2E471F]">Kelola Program Latihan</h1>
    <p class="text-sm text-gray-400 mt-0.5">Buat dan atur program latihan untuk klien</p>
  </div>
</div>

{{-- SEARCH --}}
<div class="mb-5 cm-fadein cm-delay-1">
  <form method="GET" action="{{ route('trainer.programs.index') }}">
    <div class="relative max-w-sm">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
      </svg>
      <input type="text" name="q" value="{{ request('q') }}"
             placeholder="Cari program..."
             class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-700
                    placeholder-gray-400 focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none
                    transition-all duration-150">
    </div>
  </form>
</div>

{{-- PROGRAM GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 cm-fadein cm-delay-2">

  {{-- ADD CARD --}}
  <a href="{{ route('trainer.programs.create') }}"
     class="group flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200
            p-8 hover:border-[#2E471F] hover:bg-[#2E471F]/5 transition-all duration-200 min-h-[160px]">
    <div class="h-11 w-11 rounded-full border-2 border-gray-200 group-hover:border-[#2E471F]
                flex items-center justify-center mb-3 transition-colors duration-200">
      <svg class="h-6 w-6 text-gray-400 group-hover:text-[#2E471F] transition-colors duration-200"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
      </svg>
    </div>
    <p class="text-sm font-semibold text-gray-500 group-hover:text-[#2E471F] transition-colors duration-200 text-center">
      Tambah Program
    </p>
  </a>

  {{-- PROGRAM CARDS --}}
  @forelse($programs as $program)
    <div class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100
                hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

      {{-- Top accent --}}
      <div class="h-1 bg-gradient-to-r from-[#2E471F] to-[#F5A623] rounded-t-2xl"></div>

      <div class="flex-1 p-5">
        <div class="h-10 w-10 rounded-xl bg-[#F5A623]/15 flex items-center justify-center mb-3">
          <svg class="h-5 w-5 text-[#F5A623]" fill="none" viewBox="0 0 24 24"
               stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
          </svg>
        </div>

        <h3 class="font-bold text-[#2E471F] text-sm leading-snug mb-2 line-clamp-2">
          {{ $program->name }}
        </h3>

        <div class="space-y-0.5 text-xs text-gray-400">
          @if($program->type)
            <p><span class="font-medium text-gray-600">Tipe:</span> {{ $program->type }}</p>
          @endif
          @if($program->difficulty)
            <p><span class="font-medium text-gray-600">Tingkat:</span> {{ $program->difficulty }}</p>
          @endif
          @if($program->total_duration > 0)
            <p><span class="font-medium text-gray-600">Durasi:</span> {{ $program->total_duration }} mnt</p>
          @endif
        </div>
      </div>

      <div class="px-5 py-3 border-t border-gray-100 flex items-center gap-2">
        <a href="{{ route('trainer.programs.show', $program) }}"
           class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#2E471F] text-white
                  hover:bg-[#3d6628] transition-colors duration-150">
          Detail
        </a>
        <a href="{{ route('trainer.programs.edit', $program) }}"
           class="flex-1 text-center px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200
                  text-gray-600 hover:bg-gray-50 transition-colors duration-150">
          Edit
        </a>
      </div>

    </div>
  @empty
    {{-- empty state is handled by the Add card always being visible --}}
  @endforelse

</div>

{{-- PAGINATION --}}
@if(method_exists($programs, 'links') && $programs->lastPage() > 1)
  <div class="mt-6">
    {{ $programs->appends(['q' => request('q')])->links() }}
  </div>
@endif

@endsection

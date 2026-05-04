@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8 cm-fadein">

  {{-- HEADER --}}
  <div>
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Dashboard</h1>
    <p class="text-gray-500 mt-1">Ringkasan statistik CaloriMate hari ini</p>
  </div>

  {{-- STAT CARDS --}}
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
      <div class="text-3xl font-extrabold text-[#2E471F]">{{ $stats['total_clients'] }}</div>
      <div class="text-sm text-gray-500 mt-1">Total Client</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
      <div class="text-3xl font-extrabold text-[#2E471F]">{{ $stats['total_trainers'] }}</div>
      <div class="text-sm text-gray-500 mt-1">Total Trainer</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
      <div class="text-3xl font-extrabold text-[#F4A938]">{{ $stats['active_today'] }}</div>
      <div class="text-sm text-gray-500 mt-1">Aktif Hari Ini</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
      <div class="text-3xl font-extrabold text-[#2E471F]">{{ $stats['total_foods'] }}</div>
      <div class="text-sm text-gray-500 mt-1">Total Makanan</div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
      <div class="text-3xl font-extrabold text-[#2E471F]">{{ $stats['total_programs'] }}</div>
      <div class="text-sm text-gray-500 mt-1">Total Program</div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- TOP FOODS --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
      <h2 class="text-lg font-bold text-[#2E471F] mb-4">Makanan Paling Sering Dicatat</h2>
      @if($topFoods->isEmpty())
        <p class="text-gray-400 text-sm">Belum ada data konsumsi.</p>
      @else
        <ol class="space-y-3">
          @foreach($topFoods as $i => $item)
            <li class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-full bg-[#EFE6D2] text-[#2E471F] font-bold text-sm flex items-center justify-center flex-shrink-0">
                {{ $i + 1 }}
              </span>
              <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-800 truncate">{{ $item->food->name ?? '—' }}</div>
                <div class="text-xs text-gray-400">{{ $item->total_portions }} porsi total</div>
              </div>
              <span class="text-sm font-semibold text-[#F4A938]">{{ $item->total_portions }}x</span>
            </li>
          @endforeach
        </ol>
      @endif
    </div>

    {{-- TOP PROGRAMS --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
      <h2 class="text-lg font-bold text-[#2E471F] mb-4">Program Latihan Terpopuler</h2>
      @if($topPrograms->isEmpty())
        <p class="text-gray-400 text-sm">Belum ada data program.</p>
      @else
        <ol class="space-y-3">
          @foreach($topPrograms as $i => $item)
            <li class="flex items-center gap-3">
              <span class="w-7 h-7 rounded-full bg-[#EFE6D2] text-[#2E471F] font-bold text-sm flex items-center justify-center flex-shrink-0">
                {{ $i + 1 }}
              </span>
              <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-800 truncate">{{ $item->program->name ?? '—' }}</div>
                <div class="text-xs text-gray-400">{{ $item->program->type ?? '' }} · {{ $item->program->difficulty ?? '' }}</div>
              </div>
              <span class="text-sm font-semibold text-[#F4A938]">{{ $item->total }}x</span>
            </li>
          @endforeach
        </ol>
      @endif
    </div>
  </div>

  {{-- QUICK ACTION --}}
  <div class="flex gap-3">
    <a href="{{ route('admin.trainers.create') }}"
       class="inline-block bg-[#2E471F] text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow">
      + Tambah Trainer
    </a>
    <a href="{{ route('admin.trainers.index') }}"
       class="inline-block bg-white border border-[#2E471F] text-[#2E471F] px-6 py-3 rounded-xl font-semibold hover:bg-[#EFE6D2] transition shadow-sm">
      Kelola Trainer
    </a>
  </div>

</div>
@endsection

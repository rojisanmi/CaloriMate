@extends('layouts.verivied')

@section('title','Dashboard')

@section('content')

{{-- GREETING --}}
<div class="flex items-center justify-between gap-6 mb-8 cm-fadein">
  <div>
    <h1 class="font-raleway text-2xl sm:text-3xl font-extrabold text-[#2E471F] leading-tight">
      Halo, <span class="text-[#F5A623]">{{ optional(\App\Models\Trainer::where('username', session('user_id'))->first())->nama ?? session('user_name', 'Trainer') }}</span>!
    </h1>
    <p class="text-sm text-gray-500 mt-1">Berikut ringkasan aktivitas platform CaloriMate.</p>
  </div>
  <img src="{{ asset('images/maskotcilik.png') }}" alt="CaloriMate"
       class="w-16 sm:w-20 h-auto select-none drop-shadow-md flex-shrink-0 cm-scalein cm-delay-1">
</div>

{{-- STAT CARDS --}}
@php
  $cards = [
    ['label' => 'Total Client',        'value' => $totalClients,  'sub' => 'terdaftar',             'color' => '#2E471F', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
    ['label' => 'Client Aktif 7 Hari', 'value' => $activeClients, 'sub' => 'unik dengan aktivitas', 'color' => '#F5A623', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>'],
    ['label' => 'Total Makanan',        'value' => $totalFoods,    'sub' => 'di database',           'color' => '#3B82F6', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>'],
    ['label' => 'Total Program',        'value' => $totalPrograms, 'sub' => 'program latihan',       'color' => '#22C55E', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>'],
  ];
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  @foreach($cards as $i => $card)
    <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200 cm-fadein cm-delay-{{ $i + 1 }}">
      <div class="h-10 w-10 rounded-xl flex items-center justify-center mb-3"
           style="background-color: {{ $card['color'] }}18;">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"
             style="color: {{ $card['color'] }}">{!! $card['icon'] !!}</svg>
      </div>
      <p class="text-2xl font-extrabold font-raleway" style="color: {{ $card['color'] }}">
        {{ number_format($card['value']) }}
      </p>
      <p class="text-xs font-semibold text-[#2E471F] mt-0.5">{{ $card['label'] }}</p>
      <p class="text-[10px] text-gray-400 mt-0.5">{{ $card['sub'] }}</p>
    </div>
  @endforeach
</div>

{{-- CHART + TOP FOODS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

  {{-- Bar chart --}}
  <div class="bg-white rounded-2xl p-5 shadow-sm cm-fadein cm-delay-2">
    <h3 class="font-semibold text-[#2E471F] text-sm mb-0.5">Client Aktif per Hari</h3>
    <p class="text-xs text-gray-400 mb-4">7 hari terakhir — jumlah client unik yang mencatat aktivitas</p>
    <div style="height:200px; position:relative;">
      <canvas id="activityChart"></canvas>
    </div>
  </div>

  {{-- Top 5 foods --}}
  <div class="bg-white rounded-2xl p-5 shadow-sm cm-fadein cm-delay-3">
    <h3 class="font-semibold text-[#2E471F] text-sm mb-0.5">Top 5 Makanan Terpopuler</h3>
    <p class="text-xs text-gray-400 mb-4">Paling sering dicatat oleh client</p>
    @if($topFoods->count())
      @php $maxFreq = $topFoods->max('frequency'); @endphp
      <div class="space-y-3">
        @foreach($topFoods as $i => $food)
          @php $pct = $maxFreq > 0 ? ($food->frequency / $maxFreq) * 100 : 0; @endphp
          <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-300 w-4 flex-shrink-0 text-right">{{ $i + 1 }}</span>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-1">
                <p class="text-xs font-semibold text-[#2E471F] truncate">{{ $food->name }}</p>
                <span class="text-[10px] text-gray-400 flex-shrink-0 ml-2">{{ $food->frequency }}×</span>
              </div>
              <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
                <div class="h-full rounded-full bg-[#F5A623]" style="width:{{ $pct }}%"></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-xs text-gray-300 italic text-center py-8">Belum ada data konsumsi.</p>
    @endif
  </div>

</div>

{{-- TOP PROGRAMS --}}
@if($topPrograms->count())
<div class="mb-8 cm-fadein cm-delay-3">
  <h3 class="font-semibold text-[#2E471F] text-sm mb-3">Program Latihan Terpopuler</h3>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    @foreach($topPrograms as $i => $prog)
      @php
        $medals = ['🥇','🥈','🥉'];
        $dv = strtolower($prog->difficulty ?? '');
        if (in_array($dv, ['low','rendah','beginner','pemula'])) { $dl='Mudah'; $db='#DCFCE7'; $dt='#15803D'; }
        elseif (in_array($dv, ['high','tinggi','advanced','lanjutan'])) { $dl='Sulit'; $db='#FEE2E2'; $dt='#B91C1C'; }
        else { $dl='Sedang'; $db='#FEF9C3'; $dt='#A16207'; }
      @endphp
      <div class="bg-white rounded-2xl p-5 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <span class="text-2xl flex-shrink-0">{{ $medals[$i] ?? '🏅' }}</span>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-[#2E471F] text-sm leading-tight">{{ $prog->name }}</p>
          <div class="flex items-center gap-2 mt-1.5">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                  style="background-color:{{ $db }};color:{{ $dt }}">{{ $dl }}</span>
            <span class="text-xs text-gray-400">{{ $prog->usage_count }}× digunakan</span>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

{{-- QUICK NAV --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 cm-fadein cm-delay-4">
  <a href="{{ route('trainer.foods.index') }}"
     class="group flex items-center gap-4 p-5 bg-white rounded-2xl shadow-sm
            hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="h-12 w-12 rounded-xl bg-[#2E471F]/10 group-hover:bg-[#F5A623]/20
                flex items-center justify-center flex-shrink-0 transition-colors duration-200">
      <svg class="h-6 w-6 text-[#2E471F] group-hover:text-[#F5A623] transition-colors"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
      </svg>
    </div>
    <div>
      <p class="font-semibold text-[#2E471F] group-hover:text-[#F5A623] transition-colors">Kelola Makanan</p>
      <p class="text-xs text-gray-400 mt-0.5">Tambah, edit, dan hapus data makanan</p>
    </div>
    <svg class="h-5 w-5 text-gray-300 group-hover:text-[#F5A623] ml-auto transition-colors"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
  </a>

  <a href="{{ route('trainer.programs.index') }}"
     class="group flex items-center gap-4 p-5 bg-white rounded-2xl shadow-sm
            hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="h-12 w-12 rounded-xl bg-[#2E471F]/10 group-hover:bg-[#F5A623]/20
                flex items-center justify-center flex-shrink-0 transition-colors duration-200">
      <svg class="h-6 w-6 text-[#2E471F] group-hover:text-[#F5A623] transition-colors"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
      </svg>
    </div>
    <div>
      <p class="font-semibold text-[#2E471F] group-hover:text-[#F5A623] transition-colors">Kelola Latihan</p>
      <p class="text-xs text-gray-400 mt-0.5">Buat dan kelola program latihan</p>
    </div>
    <svg class="h-5 w-5 text-gray-300 group-hover:text-[#F5A623] ml-auto transition-colors"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('activityChart');
  if (!ctx) return;
  new Chart(ctx.getContext('2d'), {
    type: 'bar',
    data: {
      labels: @json($activityChart['labels']),
      datasets: [{
        label: 'Client Aktif',
        data: @json($activityChart['values']),
        backgroundColor: 'rgba(245,166,35,0.8)',
        borderColor: 'rgba(245,166,35,1)',
        borderWidth: 1,
        borderRadius: 6,
        maxBarThickness: 40,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(46,71,31,0.95)',
          callbacks: { label: ctx => `${ctx.parsed.y} client aktif` }
        }
      },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          ticks: { precision: 0, font: { family: "'Quicksand',sans-serif" } },
          grid: { color: 'rgba(0,0,0,0.04)' }
        }
      }
    }
  });
});
</script>

@endsection

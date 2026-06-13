@extends('layouts.verivied-client')

@section('title','Statistik')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

  {{-- PAGE HEADER --}}
  <div class="cm-fadein">
    <h2 class="font-raleway text-2xl font-bold text-[#2E471F]">Statistik Harian</h2>
    <p class="text-sm text-gray-500 mt-1">
      {{ isset($today) ? \Carbon\Carbon::parse($today)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
    </p>
  </div>

  {{-- KALORI SUMMARY --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 cm-fadein cm-delay-1">
    @php
      $summaryItems = [
        ['label' => 'Kalori Masuk',  'value' => $statistik['kalori_masuk'],  'color' => '#F5A623'],
        ['label' => 'Kalori Keluar', 'value' => $statistik['kalori_keluar'], 'color' => '#2E471F'],
        ['label' => 'Selisih',       'value' => $statistik['selisih'],       'color' => '#3B82F6'],
      ];
    @endphp
    @foreach($summaryItems as $item)
      <div class="bg-white rounded-2xl px-5 py-4 shadow-sm text-center">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $item['label'] }}</p>
        <p class="text-2xl font-bold font-raleway" style="color: {{ $item['color'] }}">
          {{ $item['value'] }}
        </p>
        <p class="text-xs text-gray-400 mt-0.5">kkal</p>
      </div>
    @endforeach
  </div>

  {{-- MAKRO TARGET VS AKTUAL --}}
  @php
    $macroRows = [
      ['label' => 'Protein', 'color' => '#3B82F6', 'bg' => '#DBEAFE', 'val' => $totalProtein, 'target' => $macroTargets['protein']],
      ['label' => 'Karbo',   'color' => '#22C55E', 'bg' => '#DCFCE7', 'val' => $totalKarbo,   'target' => $macroTargets['carbo']],
      ['label' => 'Lemak',   'color' => '#F97316', 'bg' => '#FFEDD5', 'val' => $totalLemak,   'target' => $macroTargets['fat']],
    ];
  @endphp
  <div class="cm-fadein cm-delay-2">
    <h3 class="font-semibold text-[#2E471F] mb-3">Target Makro vs Aktual</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      @foreach($macroRows as $m)
        @php $pct = $m['target'] > 0 ? min(100, ($m['val'] / $m['target']) * 100) : 0; @endphp
        <div class="rounded-2xl px-5 py-4 text-center" style="background-color: {{ $m['bg'] }}">
          <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: {{ $m['color'] }}">{{ $m['label'] }}</p>
          <p class="text-3xl font-extrabold font-raleway" style="color: {{ $m['color'] }}">
            {{ $m['val'] }}<span class="text-sm font-normal">g</span>
          </p>
          <p class="text-xs text-gray-500 mb-2">dari {{ $m['target'] }}g target</p>
          <div class="h-2 rounded-full bg-black/10 overflow-hidden">
            <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $m['color'] }}"></div>
          </div>
          <p class="text-xs mt-1 font-semibold" style="color: {{ $m['color'] }}">{{ round($pct) }}%</p>
        </div>
      @endforeach
    </div>
  </div>

  {{-- PIE CHART --}}
  <div class="bg-white rounded-2xl p-6 shadow-sm cm-fadein cm-delay-3">
    <h3 class="font-semibold text-[#2E471F] mb-4 text-center">Perbandingan Nutrisi Harian</h3>
    <div class="max-w-sm mx-auto">
      <canvas id="nutritionPieChart" height="220"></canvas>
    </div>
  </div>

  {{-- RIWAYAT MAKANAN --}}
  <div class="bg-white rounded-2xl shadow-sm overflow-hidden cm-fadein cm-delay-3">
    <div class="px-5 py-4 border-b border-gray-100">
      <h3 class="font-semibold text-[#2E471F]">Riwayat Makanan Hari Ini</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-[#EFE6D2]/60">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Makanan</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Kategori</th>
            <th class="px-4 py-3 text-center text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Porsi</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Kalori</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Protein</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Lemak</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Karbo</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($foodsToday as $food)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 font-medium text-gray-800">{{ $food['nama'] }}</td>
              <td class="px-4 py-3 capitalize text-gray-500">{{ $food['kategori'] }}</td>
              <td class="px-4 py-3 text-center text-gray-600">{{ $food['porsi'] }}</td>
              <td class="px-4 py-3 text-right font-semibold text-[#2E471F]">
                {{ number_format($food['kalori'], 0, ',', '.') }}
              </td>
              <td class="px-4 py-3 text-right text-gray-500">{{ number_format($food['protein'], 1, ',', '.') }}g</td>
              <td class="px-4 py-3 text-right text-gray-500">{{ number_format($food['lemak'], 1, ',', '.') }}g</td>
              <td class="px-4 py-3 text-right text-gray-500">{{ number_format($food['karbo'], 1, ',', '.') }}g</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center">
                <img src="{{ asset('images/empty/empty-diary.png') }}" alt=""
                     class="w-32 h-auto opacity-90 mx-auto mb-3 select-none">
                <p class="text-sm text-gray-400">Belum ada makanan yang dicatat hari ini.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- EVALUASI PROGRESS MINGGUAN --}}
  @if(isset($weeklyEval))
  @php
    $eval     = $weeklyEval;
    $calPct   = $eval['calorieTarget'] > 0 ? min(100, ($eval['avgCaloriesIn'] / $eval['calorieTarget']) * 100) : 0;
    $barColor = $calPct > 120 ? '#DC2626' : ($calPct < 80 ? '#D97706' : '#16A34A');
  @endphp
  <div class="cm-fadein cm-delay-4">

    <h3 class="font-semibold text-[#2E471F] mb-1">Evaluasi Progress Mingguan</h3>
    <p class="text-xs text-gray-400 mb-3">
      {{ \Carbon\Carbon::parse($eval['weekStart'])->translatedFormat('d M') }} –
      {{ \Carbon\Carbon::parse($eval['weekEnd'])->translatedFormat('d M Y') }}
    </p>

    {{-- 4 stat cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
      @php
        $weekCards = [
          ['label' => 'Hari Aktif',      'value' => $eval['activeDays'] . '/7',                              'sub' => 'hari dicatat'],
          ['label' => 'Hari Latihan',    'value' => $eval['exerciseDays'],                                   'sub' => 'dari 7 hari'],
          ['label' => 'Rata-rata Masuk', 'value' => number_format($eval['avgCaloriesIn'],  0, ',', '.'),     'sub' => 'kkal / hari'],
          ['label' => 'Rata-rata Keluar','value' => number_format($eval['avgCaloriesOut'], 0, ',', '.'),     'sub' => 'kkal / hari'],
        ];
      @endphp
      @foreach($weekCards as $wc)
        <div class="bg-white rounded-2xl px-4 py-4 shadow-sm text-center">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ $wc['label'] }}</p>
          <p class="text-2xl font-bold font-raleway text-[#2E471F]">{{ $wc['value'] }}</p>
          <p class="text-xs text-gray-400 mt-0.5">{{ $wc['sub'] }}</p>
        </div>
      @endforeach
    </div>

    {{-- Calorie bar + macro rows in one card --}}
    <div class="bg-white rounded-2xl px-5 py-5 shadow-sm mb-4 space-y-4">

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <p class="text-xs font-semibold text-[#2E471F]">Rata-rata Kalori Harian</p>
          <p class="text-xs text-gray-400">
            {{ number_format($eval['avgCaloriesIn'],0,',','.') }} / {{ number_format($eval['calorieTarget'],0,',','.') }} kkal
          </p>
        </div>
        <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
          <div class="h-full rounded-full" style="width: {{ $calPct }}%; background-color: {{ $barColor }}"></div>
        </div>
        <p class="text-xs mt-1 font-medium" style="color: {{ $barColor }}">{{ round($calPct) }}% dari target</p>
      </div>

      <div class="border-t border-gray-100"></div>

      <div>
        <p class="text-xs font-semibold text-[#2E471F] mb-3">Rata-rata Makro Mingguan</p>
        <div class="space-y-3">
          @php
            $wMacros = [
              ['label' => 'Protein', 'val' => $eval['avgProtein'], 'target' => $macroTargets['protein'], 'color' => '#3B82F6'],
              ['label' => 'Karbo',   'val' => $eval['avgKarbo'],   'target' => $macroTargets['carbo'],   'color' => '#22C55E'],
              ['label' => 'Lemak',   'val' => $eval['avgLemak'],   'target' => $macroTargets['fat'],     'color' => '#F97316'],
            ];
          @endphp
          @foreach($wMacros as $wm)
            @php $pct = $wm['target'] > 0 ? min(100, ($wm['val'] / $wm['target']) * 100) : 0; @endphp
            <div>
              <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-medium text-gray-600">{{ $wm['label'] }}</span>
                <span class="text-xs text-gray-400">{{ $wm['val'] }}g / {{ $wm['target'] }}g</span>
              </div>
              <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                <div class="h-full rounded-full" style="width: {{ $pct }}%; background-color: {{ $wm['color'] }}"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Saran perbaikan --}}
    @if(count($eval['tips']))
    <div class="bg-white rounded-2xl px-5 py-5 shadow-sm">
      <h3 class="font-semibold text-[#2E471F] mb-3">Saran Perbaikan</h3>
      <div class="space-y-3">
        @foreach($eval['tips'] as $tip)
          @php
            $bColor = $tip['type'] === 'success' ? '#16A34A' : ($tip['type'] === 'danger' ? '#DC2626' : '#D97706');
          @endphp
          <div class="pl-3 border-l-4 py-0.5" style="border-color: {{ $bColor }}">
            <p class="text-sm text-gray-600 leading-relaxed">{{ $tip['text'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>
  @endif

  {{-- RIWAYAT AKTIVITAS --}}
  <div class="bg-white rounded-2xl shadow-sm overflow-hidden cm-fadein cm-delay-4">
    <div class="px-5 py-4 border-b border-gray-100">
      <h3 class="font-semibold text-[#2E471F]">Riwayat Aktivitas Hari Ini</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-[#EFE6D2]/60">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Aktivitas</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Waktu</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-[#2E471F] uppercase tracking-wide">Kalori</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($aktivitas as $item)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-gray-500">{{ $item['tanggal'] }}</td>
              <td class="px-4 py-3 font-medium text-gray-800">{{ $item['nama'] }}</td>
              <td class="px-4 py-3 text-gray-500">{{ $item['waktu'] }}</td>
              <td class="px-4 py-3 text-right font-semibold text-[#2E471F]">{{ $item['kalori'] }} kkal</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">
                Tidak ada aktivitas latihan hari ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('nutritionPieChart');
  if (!canvas) return;

  const rawData = @json($nutritionChartData ?? ['labels' => [], 'values' => []]);
  const ctx = canvas.getContext('2d');

  if (window.nutritionPieInstance) window.nutritionPieInstance.destroy();

  window.nutritionPieInstance = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: rawData.labels,
      datasets: [{
        data: rawData.values,
        backgroundColor: [
          'rgba(59, 130, 246, 0.9)',
          'rgba(249, 115, 22, 0.9)',
          'rgba(34, 197, 94, 0.9)',
        ],
        borderColor: 'white',
        borderWidth: 3,
        hoverOffset: 12,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      animation: { animateRotate: true, animateScale: true, duration: 1200, easing: 'easeInOutQuart' },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            padding: 16,
            font: { size: 13, family: "'Quicksand', sans-serif" },
            color: '#2E471F'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(46,71,31,0.95)',
          titleColor: '#fff',
          bodyColor: '#fff',
          padding: 10,
          callbacks: {
            label: function (ctx) {
              const label = ctx.label || '';
              const value = ctx.parsed || 0;
              const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
              const pct = ((value / total) * 100).toFixed(1);
              const unit = label.includes('Kalori') ? ' kkal' : ' g';
              return `${label}: ${value}${unit} (${pct}%)`;
            }
          }
        }
      }
    }
  });
});
</script>

@endsection

@extends('layouts.verivied-client')

@section('title','Riwayat')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

  {{-- PAGE HEADER --}}
  <div class="cm-fadein">
    <h2 class="font-raleway text-2xl font-bold text-[#2E471F]">Riwayat Aktivitas</h2>
    <p class="text-sm text-gray-500 mt-1">Pantau pola kalori dan latihan kamu dari waktu ke waktu</p>
  </div>

  {{-- DATE RANGE FILTER --}}
  <form method="GET" action="{{ route('client.history') }}" id="dateRangeForm">
    <div class="flex flex-wrap items-end gap-3 cm-fadein cm-delay-1">

      <div>
        <label class="block text-xs font-semibold text-[#2E471F] mb-1.5">Dari</label>
        <input type="date" name="date_from"
               value="{{ $dateFrom->format('Y-m-d') }}"
               max="{{ now()->format('Y-m-d') }}"
               class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-[#2E471F]
                      text-sm font-semibold shadow-sm focus:outline-none focus:border-[#2E471F]
                      transition-colors cursor-pointer">
      </div>

      <div class="flex items-end pb-2.5">
        <span class="text-sm text-gray-400 font-medium">—</span>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#2E471F] mb-1.5">Sampai</label>
        <input type="date" name="date_to"
               value="{{ $dateTo->format('Y-m-d') }}"
               max="{{ now()->format('Y-m-d') }}"
               class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-[#2E471F]
                      text-sm font-semibold shadow-sm focus:outline-none focus:border-[#2E471F]
                      transition-colors cursor-pointer">
      </div>

      <button type="submit"
              class="px-5 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                     hover:bg-[#3d6628] transition shadow-sm">
        Tampilkan
      </button>

    </div>
  </form>

  {{-- CHART --}}
  <div class="bg-white rounded-2xl p-5 shadow-sm cm-fadein cm-delay-2">
    <h3 class="font-semibold text-[#2E471F] mb-4">Kalori Masuk &amp; Keluar</h3>
    <div style="height: 220px; position: relative;">
      <canvas id="historyChart"></canvas>
    </div>
  </div>

  {{-- AKTIVITAS LATIHAN --}}
  <div class="cm-fadein cm-delay-3">
    <h3 class="font-semibold text-[#2E471F] mb-3">Aktivitas Latihan</h3>
    @if(isset($activities) && count($activities))
      <div class="space-y-3">
        @foreach($activities as $activity)
          <div class="bg-white rounded-xl px-5 py-4 shadow-sm flex justify-between items-center
                      hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div>
              <p class="font-semibold text-[#2E471F] text-sm">{{ $activity['program_name'] }}</p>
              <p class="text-xs text-gray-400 mt-0.5">
                {{ \Carbon\Carbon::parse($activity['date'])->translatedFormat('d F Y') }}
              </p>
            </div>
            <div class="text-right">
              <p class="text-xs text-gray-400 mb-0.5">Kalori Keluar</p>
              <p class="font-bold text-[#2E471F]">
                {{ number_format($activity['calories_out'], 0, ',', '.') }} kkal
              </p>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="bg-white rounded-2xl shadow-sm flex flex-col items-center justify-center py-10 text-center">
        <img src="{{ asset('images/empty/empty-history.png') }}" alt=""
             class="w-40 h-auto opacity-90 mb-3 select-none">
        <p class="text-sm text-gray-400">Belum ada aktivitas latihan pada periode ini.</p>
      </div>
    @endif
  </div>

  {{-- RIWAYAT KONSUMSI --}}
  <div class="cm-fadein cm-delay-4">
    <h3 class="font-semibold text-[#2E471F] mb-3">Riwayat Konsumsi</h3>
    @forelse($histories as $history)
      <div class="bg-white rounded-xl px-5 py-4 shadow-sm flex justify-between items-center
                  hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 mb-3">
        <div>
          <p class="font-semibold text-[#2E471F] text-sm">{{ $history['name'] }}</p>
          <p class="text-xs text-gray-400 mt-0.5">
            {{ \Carbon\Carbon::parse($history['date'])->translatedFormat('d F Y') }}
          </p>
        </div>
        <div class="text-right">
          <p class="text-xs text-gray-400 mb-0.5">Kalori Masuk</p>
          <p class="font-bold text-[#2E471F]">{{ $history['calories'] }} kkal</p>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow-sm flex flex-col items-center justify-center py-10 text-center">
        <img src="{{ asset('images/empty/empty-history.png') }}" alt=""
             class="w-40 h-auto opacity-90 mb-3 select-none">
        <p class="text-sm text-gray-400">Tidak ada riwayat konsumsi pada periode ini.</p>
      </div>
    @endforelse
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Validasi: date_from tidak boleh lebih dari date_to
  const fromInput = document.querySelector('[name="date_from"]');
  const toInput   = document.querySelector('[name="date_to"]');
  if (fromInput && toInput) {
    fromInput.addEventListener('change', () => { if (toInput.value && fromInput.value > toInput.value) toInput.value = fromInput.value; });
    toInput.addEventListener('change',   () => { if (fromInput.value && toInput.value < fromInput.value) fromInput.value = toInput.value; });
  }

  // Chart
  const chartEl = document.getElementById('historyChart');
  if (!chartEl) return;

  const rawData = @json($chartData ?? ['labels' => [], 'calori_in' => [], 'calori_out' => []]);
  if (window.historyChartInstance) window.historyChartInstance.destroy();

  window.historyChartInstance = new Chart(chartEl.getContext('2d'), {
    type: 'bar',
    data: {
      labels: rawData.labels,
      datasets: [
        {
          label: 'Kalori Masuk',
          data: rawData.calori_in,
          backgroundColor: 'rgba(245,166,35,0.8)',
          borderColor: 'rgba(245,166,35,1)',
          borderWidth: 1,
          borderRadius: 6,
          maxBarThickness: 40,
        },
        {
          label: 'Kalori Keluar',
          data: rawData.calori_out,
          backgroundColor: 'rgba(46,71,31,0.8)',
          borderColor: 'rgba(46,71,31,1)',
          borderWidth: 1,
          borderRadius: 6,
          maxBarThickness: 40,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: true, labels: { usePointStyle: true, font: { family: "'Quicksand',sans-serif" } } },
        tooltip: {
          backgroundColor: 'rgba(46,71,31,0.95)',
          callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} kkal` }
        }
      },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0,0,0,0.04)' },
          ticks: { callback: v => v + ' kkal', font: { family: "'Quicksand',sans-serif" } }
        }
      }
    }
  });
});
</script>

@endsection

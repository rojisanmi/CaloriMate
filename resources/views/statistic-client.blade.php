@extends('layouts.verivied-client')

@section('title','Statistic')

@section('content')
<section class="max-w-5xl mx-auto">

    <div class="bg-[#FFFFFF] rounded-xl p-8 shadow">

        {{-- DETAIL STATISTIK --}}
        <h2 class="text-center font-bold text-[#2E471F] mb-4">
            Detail Statistik Harian
        </h2>

        <p class="text-center text-sm text-gray-500 mb-6">
            Statistik ini menampilkan ringkasan kalori dan nutrisi untuk hari ini
            ({{ isset($today) ? \Carbon\Carbon::parse($today)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}).
        </p>

        <div class="bg-[#F2EAD3] rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

                <div>
                    <p class="text-sm text-gray-500">Kalori Masuk</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['kalori_masuk'] }} kkal
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kalori Keluar</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['kalori_keluar'] }} kkal
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Selisih</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['selisih'] }} kkal
                    </p>
                </div>

            </div>
        </div>

        {{-- PIE CHART NUTRISI HARIAN --}}
        <div class="mb-8">
            <h3 class="font-semibold text-[#2E471F] mb-3 text-center">
                Perbandingan Nutrisi Harian dari Makanan yang Dikonsumsi
            </h3>
            <div class="max-w-md mx-auto bg-[#F7F7F7] rounded-xl p-4 md:p-6 border border-gray-100">
                <canvas id="nutritionPieChart" height="200"></canvas>
            </div>
        </div>

        {{-- RIWAYAT MAKANAN HARI INI --}}
        <div class="mb-8">
            <h3 class="font-semibold text-[#2E471F] mb-2">
                Riwayat Makanan Hari Ini
            </h3>

            <div class="bg-white rounded-lg overflow-hidden border">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-[#2E471F]">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Makanan</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-center">Porsi</th>
                            <th class="px-4 py-2 text-right">Kalori</th>
                            <th class="px-4 py-2 text-right">Protein (g)</th>
                            <th class="px-4 py-2 text-right">Lemak (g)</th>
                            <th class="px-4 py-2 text-right">Karbo (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($foodsToday as $food)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $food['nama'] }}</td>
                            <td class="px-4 py-2 capitalize">{{ $food['kategori'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $food['porsi'] }}</td>
                            <td class="px-4 py-2 text-right">
                                {{ number_format($food['kalori'], 0, ',', '.') }} kkal
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ number_format($food['protein'], 1, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ number_format($food['lemak'], 1, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                {{ number_format($food['karbo'], 1, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">
                                Belum ada makanan yang dicatat untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIWAYAT AKTIVITAS --}}
        <h3 class="font-semibold text-[#2E471F] mb-2">
            Riwayat Aktivitas (Latihan) Hari Ini
        </h3>

        <div class="bg-white rounded-lg overflow-hidden border">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-[#2E471F]">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Aktivitas</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-right">Kalori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item['tanggal'] }}</td>
                        <td class="px-4 py-2">{{ $item['nama'] }}</td>
                        <td class="px-4 py-2">{{ $item['waktu'] }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ $item['kalori'] }} kkal
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Tidak ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('nutritionPieChart');
    if (!canvas) return;

    const rawData = @json($nutritionChartData ?? ['labels' => [], 'values' => []]);

    const ctx = canvas.getContext('2d');

    if (window.nutritionPieInstance) {
        window.nutritionPieInstance.destroy();
    }

    window.nutritionPieInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: rawData.labels,
            datasets: [{
                data: rawData.values,
                backgroundColor: [
                    'rgba(244, 169, 56, 0.9)',   // Kalori
                    'rgba(56, 189, 248, 0.9)',   // Protein
                    'rgba(244, 63, 94, 0.9)',    // Lemak
                    'rgba(52, 211, 153, 0.9)',   // Karbo
                ],
                borderColor: 'white',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
@extends('layouts.verivied-client')

@section('title','Statistic')

@section('content')

<style>
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

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
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

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.7s ease-out;
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

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .chart-loading {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>

<section class="max-w-5xl mx-auto">

    <div class="bg-[#FFFFFF] rounded-xl p-8 shadow animate-fade-in-up">

        {{-- DETAIL STATISTIK --}}
        <h2 class="text-center font-bold text-[#2E471F] mb-4 animate-scale-in">
            Detail Statistik Harian
        </h2>

        <p class="text-center text-sm text-gray-500 mb-6 animate-scale-in animate-delay-1">
            Statistik ini menampilkan ringkasan kalori dan nutrisi untuk hari ini
            ({{ isset($today) ? \Carbon\Carbon::parse($today)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}).
        </p>

        <div class="bg-[#F2EAD3] rounded-lg p-6 mb-8 animate-scale-in animate-delay-2">
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
        <div class="mb-8 animate-scale-in animate-delay-3">
            <h3 class="font-semibold text-[#2E471F] mb-3 text-center">
                Perbandingan Nutrisi Harian dari Makanan yang Dikonsumsi
            </h3>
            <div class="max-w-md mx-auto bg-[#F7F7F7] rounded-xl p-4 md:p-6 border border-gray-100">
                <canvas id="nutritionPieChart" height="200"></canvas>
            </div>
        </div>

        {{-- RIWAYAT MAKANAN HARI INI --}}
        <div class="mb-8 animate-slide-in-left animate-delay-4">
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
                        <tr class="border-t hover:bg-gray-50 transition-colors">
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
        <div class="animate-slide-in-left animate-delay-4">
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
                        <tr class="border-t hover:bg-gray-50 transition-colors">
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

    // Konfigurasi animasi yang lebih menarik
    window.nutritionPieInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: rawData.labels,
            datasets: [{
                data: rawData.values,
                backgroundColor: [
                    'rgba(244, 169, 56, 0.9)',   // Kalori - Orange
                    'rgba(56, 189, 248, 0.9)',   // Protein - Blue
                    'rgba(244, 63, 94, 0.9)',    // Lemak - Red
                    'rgba(52, 211, 153, 0.9)',   // Karbo - Green
                ],
                borderColor: 'white',
                borderWidth: 3,
                hoverOffset: 15, // Efek zoom saat hover
                hoverBorderWidth: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            
            
            animation: {
                animateRotate: true,      
                animateScale: true,      
                duration: 2000,          
                easing: 'easeInOutQuart', 
                
                
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default') {
                        delay = context.dataIndex * 200; 
                    }
                    return delay;
                },
                
               
                onComplete: function(animation) {
                    console.log('Chart animation completed!');
                }
            },
            
           
            hover: {
                mode: 'nearest',
                intersect: true,
                animationDuration: 400,
            },
            
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 15,
                        font: {
                            size: 13,
                            family: "'Inter', sans-serif"
                        },
                        color: '#2E471F'
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            
                           
                            let unit = '';
                            if (label.includes('Kalori')) {
                                unit = ' kkal';
                            } else {
                                unit = ' g';
                            }
                            
                            return `${label}: ${value}${unit} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    
    setTimeout(() => {
        canvas.style.transform = 'scale(1)';
        canvas.style.opacity = '1';
    }, 100);
});
</script>

@endsection
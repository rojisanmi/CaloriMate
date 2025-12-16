@extends('layouts.verivied-client')

@section('title','History')

@section('content')
<section class="max-w-5xl mx-auto">

    {{-- FILTER & CHART PERIODE --}}
    <form method="GET" action="{{ route('client.history') }}" id="periodForm">
        <div class="bg-[#FFFFFF] rounded-xl p-6 shadow mb-6">

            {{-- Custom Dropdown --}}
            <div class="relative">
                <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
                
                <button type="button" 
                        id="dropdownButton"
                        class="w-full px-4 py-3 rounded-lg bg-white border border-gray-300
                               text-[#2E471F] font-semibold focus:outline-none focus:border-[#2E471F]
                               flex items-center justify-between transition-all duration-200
                               hover:border-[#2E471F]">
                    <span id="selectedText">
                        @if($period == 'daily' || $period == '1_day' || $period == '7_days')
                            7 Hari Terakhir (Harian)
                        @elseif($period == 'weekly')
                            Mingguan
                        @else
                            Bulanan
                        @endif
                    </span>
                    <svg id="dropdownIcon" 
                         class="w-5 h-5 transition-transform duration-300" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div id="dropdownMenu" 
                     class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg
                            opacity-0 invisible transform scale-95 origin-top
                            transition-all duration-300 ease-out">
                    
                    <div class="py-1">
                        <button type="button"
                                data-value="daily"
                                data-text="7 Hari Terakhir (Harian)"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ in_array($period,['daily','1_day','7_days']) ? 'bg-[#F0F4EC] font-semibold' : '' }}">
                            <span class="text-[#2E471F]">7 Hari Terakhir (Harian)</span>
                        </button>
                        
                        <button type="button"
                                data-value="weekly"
                                data-text="Mingguan"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ $period=='weekly'?'bg-[#F0F4EC] font-semibold':'' }}">
                            <span class="text-[#2E471F]">Mingguan</span>
                        </button>
                        
                        <button type="button"
                                data-value="monthly"
                                data-text="Bulanan"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ $period=='monthly'?'bg-[#F0F4EC] font-semibold':'' }}">
                            <span class="text-[#2E471F]">Bulanan</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- CHART --}}
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-[#2E471F] mb-3">
                    Visualisasi Kalori Masuk & Keluar
                </h3>
                <div class="bg-[#F7F7F7] rounded-xl p-4 md:p-6 border border-gray-100">
                    <canvas id="historyChart" height="120"></canvas>
                </div>
            </div>

            {{-- AKTIVITAS LATIHAN --}}
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-[#2E471F] mb-3">
                    Aktivitas Latihan
                </h3>

                @if(isset($activities) && count($activities))
                    <div class="space-y-3">
                        @foreach($activities as $activity)
                            <div class="bg-white rounded-lg px-5 py-4 shadow-sm flex justify-between items-center
                                        border border-gray-100 hover:shadow-md hover:-translate-y-0.5
                                        transition-all duration-200">
                                <div>
                                    <p class="font-semibold text-[#2E471F]">
                                        {{ $activity['program_name'] }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity['date'])->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">Kalori Keluar</p>
                                    <p class="text-[#2E471F] font-bold">
                                        {{ number_format($activity['calories_out'], 0, ',', '.') }} kkal
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">
                        Belum ada aktivitas latihan pada periode ini.
                    </p>
                @endif
            </div>

            {{-- RESULT LIST --}}
            <div class="mt-8 space-y-4">

                @forelse($histories as $history)
                <div class="bg-white rounded-lg px-5 py-4 shadow-sm flex justify-between
                            transform transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    <div>
                        <p class="font-semibold text-[#2E471F]">
                            {{ $history['name'] }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($history['date'])->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="text-[#2E471F] font-bold">
                        {{ $history['calories'] }} kkal
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-6">
                    Tidak ada data pada periode ini
                </p>
                @endforelse

            </div>
        </div>
    </form>

</section>

{{-- Chart.js & Dropdown Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');
    const selectedText = document.getElementById('selectedText');
    const periodInput = document.getElementById('periodInput');
    const form = document.getElementById('periodForm');
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    // Toggle dropdown
    dropdownButton.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleDropdown();
    });

    // Handle item selection
    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const text = this.getAttribute('data-text');
            
            // Update UI
            selectedText.textContent = text;
            periodInput.value = value;
            
            // Remove active class from all items
            dropdownItems.forEach(i => {
                i.classList.remove('bg-[#F0F4EC]', 'font-semibold');
            });
            
            // Add active class to selected item
            this.classList.add('bg-[#F0F4EC]', 'font-semibold');
            
            // Close dropdown with animation
            closeDropdown();
            
            // Submit form after animation
            setTimeout(() => {
                form.submit();
            }, 200);
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            closeDropdown();
        }
    });

    // Toggle function
    function toggleDropdown() {
        if (dropdownMenu.classList.contains('opacity-0')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    }

    // Open dropdown
    function openDropdown() {
        dropdownMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
        dropdownMenu.classList.add('opacity-100', 'visible', 'scale-100');
        dropdownIcon.style.transform = 'rotate(180deg)';
    }

    // Close dropdown
    function closeDropdown() {
        dropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
        dropdownMenu.classList.remove('opacity-100', 'visible', 'scale-100');
        dropdownIcon.style.transform = 'rotate(0deg)';
    }
    // ====== CHART LOGIC ======
    const chartElement = document.getElementById('historyChart');
    if (chartElement) {
        const rawData = @json($chartData ?? ['labels' => [], 'calori_in' => [], 'calori_out' => []]);

        const ctx = chartElement.getContext('2d');

        // Destroy old chart if re-rendered via hot reload
        if (window.historyChartInstance) {
            window.historyChartInstance.destroy();
        }

        window.historyChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rawData.labels,
                datasets: [
                    {
                        label: 'Kalori Masuk',
                        data: rawData.calori_in,
                        backgroundColor: 'rgba(244, 169, 56, 0.8)',
                        borderColor: 'rgba(244, 169, 56, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 40,
                    },
                    {
                        label: 'Kalori Keluar',
                        data: rawData.calori_out,
                        backgroundColor: 'rgba(46, 71, 31, 0.8)',
                        borderColor: 'rgba(46, 71, 31, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 40,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y || 0;
                                return `${label}: ${value} kkal`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' kkal';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection
@extends('layouts.verivied-client')

@section('title','History')

@section('content')
<section class="max-w-4xl mx-auto">

    {{-- FILTER --}}
    <form method="GET" action="{{ route('client.history') }}" id="periodForm">
        <div class="bg-[#FFFFFF] rounded-xl p-6 shadow">

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
                        @if($period == '1_day')
                            1 Hari Terakhir
                        @elseif($period == '7_days')
                            7 Hari Terakhir
                        @else
                            1 Bulan Terakhir
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
                                data-value="1_day"
                                data-text="1 Hari Terakhir"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ $period=='1_day'?'bg-[#F0F4EC] font-semibold':'' }}">
                            <span class="text-[#2E471F]">1 Hari Terakhir</span>
                        </button>
                        
                        <button type="button" 
                                data-value="7_days"
                                data-text="7 Hari Terakhir"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ $period=='7_days'?'bg-[#F0F4EC] font-semibold':'' }}">
                            <span class="text-[#2E471F]">7 Hari Terakhir</span>
                        </button>
                        
                        <button type="button" 
                                data-value="1_month"
                                data-text="1 Bulan Terakhir"
                                class="dropdown-item w-full text-left px-4 py-3 hover:bg-[#F0F4EC] 
                                       transition-colors duration-150 {{ $period=='1_month'?'bg-[#F0F4EC] font-semibold':'' }}">
                            <span class="text-[#2E471F]">1 Bulan Terakhir</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RESULT --}}
            <div class="mt-6 space-y-4">

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
});
</script>

@endsection
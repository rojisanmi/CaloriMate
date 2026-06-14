@extends('layouts.verivied-client')

@section('title', 'Diary')

@section('content')

{{-- FLASH MESSAGE --}}
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
<div class="mb-6 cm-fadein">
  <h1 class="font-raleway text-2xl sm:text-3xl font-bold text-[#2E471F]">Diary Hari Ini</h1>
  <p class="text-sm text-gray-500 mt-1">Catat makanan yang kamu konsumsi hari ini</p>
</div>

{{-- TOP STATS: KALORI + MAKRO --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

  {{-- Calorie summary (spans 2 cols on lg) --}}
  @php $calPct = $dailyCaloriesTarget > 0 ? min(100, ($consumedCalories / $dailyCaloriesTarget) * 100) : 0; @endphp
  <div class="lg:col-span-2 bg-[#2E471F] text-white rounded-2xl px-6 py-5 shadow cm-fadein">
    <div class="flex items-center justify-between mb-3">
      <div>
        <p class="text-xs text-white/60 uppercase tracking-wider font-semibold">Konsumsi Kalori</p>
        <p class="text-3xl sm:text-4xl font-bold font-raleway mt-1">
          {{ round($consumedCalories) }}
          <span class="text-base font-normal text-white/60">/ {{ round($dailyCaloriesTarget) }} kkal</span>
        </p>
      </div>
      <div class="text-right">
        <p class="text-xs text-white/60 uppercase tracking-wider font-semibold">Sisa</p>
        <p class="text-2xl font-bold text-[#F5A623] mt-1">{{ round($remainingCalories) }}</p>
        <p class="text-xs text-white/40">kkal</p>
      </div>
    </div>
    <div class="h-2.5 rounded-full bg-white/20 overflow-hidden">
      <div class="h-full rounded-full bg-[#F5A623] transition-all" style="width: {{ $calPct }}%"></div>
    </div>
  </div>

  {{-- Macros --}}
  @php
    $macros = [
      ['label' => 'Protein', 'color' => '#3B82F6', 'consumed' => round($consumedProtein, 1), 'target' => $macroTargets['protein']],
      ['label' => 'Karbo',   'color' => '#22C55E', 'consumed' => round($consumedCarbo, 1),   'target' => $macroTargets['carbo']],
      ['label' => 'Lemak',   'color' => '#F97316', 'consumed' => round($consumedFat, 1),     'target' => $macroTargets['fat']],
    ];
  @endphp
  <div class="bg-white rounded-2xl px-5 py-4 shadow-sm cm-fadein cm-delay-1">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Target Makro</p>
    <div class="space-y-2.5">
      @foreach($macros as $m)
        @php $pct = $m['target'] > 0 ? min(100, ($m['consumed'] / $m['target']) * 100) : 0; @endphp
        <div>
          <div class="flex justify-between text-xs mb-1">
            <span class="font-semibold" style="color: {{ $m['color'] }}">{{ $m['label'] }}</span>
            <span class="text-gray-400">{{ $m['consumed'] }}g / {{ $m['target'] }}g</span>
          </div>
          <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full rounded-full"
                 style="width: {{ $pct }}%; background-color: {{ $m['color'] }}"></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>

{{-- REKOMENDASI MENU --}}
@if($calorieGoalReached)
  <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-2xl cm-fadein cm-delay-2">
    <svg class="h-7 w-7 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    <div>
      <p class="font-semibold text-emerald-800 text-sm">Target kalori hari ini sudah tercapai!</p>
      <p class="text-xs text-emerald-600 mt-0.5">Pertahankan pola makan sehat kamu ya.</p>
    </div>
  </div>
@elseif(count($recommendations) > 0)
  <div class="mb-6 cm-fadein cm-delay-2">
    <div class="mb-3">
      <h2 class="font-raleway text-base font-bold text-[#2E471F] flex items-center gap-2">
        <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
        </svg>
        Rekomendasi {{ $catLabels[$defaultCategory] ?? 'Makananmu' }}
      </h2>
      <p class="text-xs text-gray-400 mt-0.5">Disesuaikan dengan sisa kalori &amp; waktu makanmu saat ini</p>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-3 -mx-4 px-4 sm:-mx-0 sm:px-0"
         style="scrollbar-width:none; -ms-overflow-style:none;">
      @php
        $catLabels = ['breakfast' => 'Sarapan', 'lunch' => 'Makan Siang', 'dinner' => 'Makan Malam', 'snack' => 'Camilan'];
        $tagColors = [
          'Tinggi protein' => ['bg' => '#DBEAFE', 'text' => '#1D4ED8'],
          'Sumber karbo'   => ['bg' => '#DCFCE7', 'text' => '#15803D'],
          'Sumber lemak'   => ['bg' => '#FEF9C3', 'text' => '#A16207'],
          'Tinggi kalori'  => ['bg' => '#FEE2E2', 'text' => '#B91C1C'],
          'Rendah kalori'  => ['bg' => '#F0FDF4', 'text' => '#166534'],
          'Seimbang'       => ['bg' => '#F5F3FF', 'text' => '#6D28D9'],
          'Cocok untukmu'  => ['bg' => '#EFE6D2', 'text' => '#2E471F'],
        ];
      @endphp

      @foreach($recommendations as $rec)
        @php $rf = $rec['food']; $rtag = $rec['tag']; $rtc = $tagColors[$rtag] ?? $tagColors['Cocok untukmu']; @endphp
        <div class="flex-shrink-0 w-44 bg-white rounded-2xl shadow-sm p-4 flex flex-col gap-2.5
                    hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
          <p class="font-semibold text-[#2E471F] text-sm leading-tight line-clamp-2 min-h-[2.5rem]">
            {{ $rf->name }}
          </p>
          <div class="flex items-baseline gap-1">
            <span class="text-xl font-bold text-[#F5A623]">{{ $rf->calories_per_portion }}</span>
            <span class="text-xs text-gray-400">kkal</span>
          </div>
          <div class="flex flex-wrap gap-1 text-[10px] font-semibold">
            <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded-full">P {{ round($rf->total_protein ?? 0) }}g</span>
            <span class="bg-green-50 text-green-600 px-1.5 py-0.5 rounded-full">K {{ round($rf->total_carbo ?? 0) }}g</span>
            <span class="bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded-full">L {{ round($rf->total_fat ?? 0) }}g</span>
          </div>
          <span class="inline-flex self-start text-[10px] font-semibold px-2 py-0.5 rounded-full"
                style="background-color: {{ $rtc['bg'] }}; color: {{ $rtc['text'] }}">
            {{ $rtag }}
          </span>
          <form action="{{ route('client.diary.store') }}" method="POST" class="mt-auto">
            @csrf
            <input type="hidden" name="food_id" value="{{ $rf->food_id }}">
            <input type="hidden" name="portions" value="1">
            <input type="hidden" name="category" value="{{ $defaultCategory }}">
            <button type="submit"
                    class="w-full py-2 rounded-xl bg-[#2E471F] text-white text-xs font-semibold
                           hover:bg-[#3d6628] active:scale-95 transition-all">
              + {{ $catLabels[$defaultCategory] }}
            </button>
          </form>
        </div>
      @endforeach
    </div>
  </div>
@endif

{{-- MEAL SEGMENTS --}}
@php
  $segments = [
    ['label' => 'Makan Pagi',      'category' => 'breakfast', 'icon' => 'icon-breakfast.png'],
    ['label' => 'Makan Siang',     'category' => 'lunch',     'icon' => 'icon-lunch.png'],
    ['label' => 'Makan Malam',     'category' => 'dinner',    'icon' => 'icon-dinner.png'],
    ['label' => 'Camilan',         'category' => 'snack',     'icon' => 'icon-snack.png'],
  ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
  @foreach($segments as $i => $seg)
    @php
      $foods = $foodsByCategory[$seg['category']] ?? [];
      $totalCal = 0;
      foreach ($foods as $f) $totalCal += $f['calories'];
    @endphp
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col cm-fadein cm-delay-{{ $i + 1 }}">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-50">
        <div class="flex items-center gap-3 mb-3">
          <div class="h-12 w-12 rounded-xl bg-[#EFE6D2] flex items-center justify-center flex-shrink-0 p-2">
            <img src="{{ asset('images/categories/' . $seg['icon']) }}" alt="{{ $seg['label'] }}"
                 class="h-full w-full object-contain select-none">
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-[#2E471F] text-base truncate">{{ $seg['label'] }}</p>
            <p class="text-xs text-gray-400">
              {{ count($foods) }} item &middot; {{ round($totalCal) }} kkal
            </p>
          </div>
        </div>
        <a href="{{ route('client.diary.add', $seg['category']) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl
                  bg-[#2E471F] text-white text-sm font-semibold
                  hover:bg-[#3d6628] hover:shadow-md transition-all duration-200">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
          </svg>
          Tambah Makanan
        </a>
      </div>

      {{-- Foods list --}}
      <div class="flex-1 p-5 pt-3">
        @if(count($foods) > 0)
          <ul class="space-y-2">
            @foreach($foods as $f)
              <li class="flex items-start justify-between gap-2 text-sm">
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-700 truncate">{{ $f['name'] }}</p>
                  <p class="text-xs text-gray-400">{{ $f['portions'] }} porsi</p>
                </div>
                <span class="text-xs font-semibold text-[#F5A623] whitespace-nowrap">
                  {{ round($f['calories']) }} kkal
                </span>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-xs text-gray-300 italic text-center py-4">Belum ada makanan</p>
        @endif
      </div>

    </div>
  @endforeach
</div>

{{-- MAPS: RESTORAN SEHAT TERDEKAT --}}
<div class="mt-8 mb-6 cm-fadein cm-delay-4">
  <div class="mb-4">
    <h2 class="font-raleway text-lg font-bold text-[#2E471F] flex items-center gap-2">
      <svg class="h-6 w-6 text-[#F5A623]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
      </svg>
      Restoran Sehat Terdekat
    </h2>
    <p class="text-sm text-gray-500 mt-1">Temukan tempat makan sehat di sekitarmu.</p>
  </div>
  
  <div class="bg-white rounded-2xl shadow-sm p-2">
    <div id="diaryMap" class="w-full h-80 rounded-xl z-0"></div>
    <button id="recenterDiaryMap" class="absolute bottom-4 right-4 z-10 bg-white p-2 rounded-full shadow-md text-[#2E471F] hover:text-[#F5A623] hover:bg-gray-50 transition-colors hidden" title="Pusatkan ke lokasi saya">
      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 22a10 10 0 100-20 10 10 0 000 20z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
      </svg>
    </button>
    <div id="mapLoading" class="w-full h-80 absolute top-0 left-0 flex items-center justify-center bg-white/80 rounded-xl z-10">
      <div class="text-[#2E471F] flex flex-col items-center">
        <svg class="animate-spin h-8 w-8 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm font-semibold">Mencari lokasi Anda...</span>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const mapLoading = document.getElementById('mapLoading');
    if (!navigator.geolocation) {
        mapLoading.innerHTML = '<span class="text-red-500 font-semibold">Geolocation tidak didukung oleh browser Anda.</span>';
        return;
    }

    // Geolocation options for better accuracy
    const geoOptions = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition((position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        mapLoading.style.display = 'none';
        
        // Fix for default Leaflet icon paths
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
          iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
          iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        });

        const map = L.map('diaryMap').setView([lat, lon], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Recenter button logic
        const recenterBtn = document.getElementById('recenterDiaryMap');
        recenterBtn.classList.remove('hidden');
        recenterBtn.addEventListener('click', () => {
            map.flyTo([lat, lon], 14);
        });

        // Marker User
        L.marker([lat, lon]).addTo(map)
         .bindPopup('<b>Lokasi Anda</b>')
         .openPopup();

        // Query Overpass API for restaurants (around 15km) - broadened for better results
        const overpassQuery = `
            [out:json];
            (
              node["amenity"="restaurant"]["diet:vegan"="yes"](around:15000,${lat},${lon});
              node["amenity"="restaurant"]["diet:vegetarian"="yes"](around:15000,${lat},${lon});
              node["amenity"="restaurant"]["health_food"="yes"](around:15000,${lat},${lon});
              node["shop"="health_food"](around:15000,${lat},${lon});
              node["amenity"="restaurant"](around:5000,${lat},${lon});
              node["amenity"="cafe"](around:5000,${lat},${lon});
            );
            out body;
        `;
        const overpassUrl = 'https://overpass-api.de/api/interpreter';
        
        fetch(overpassUrl, {
            method: 'POST',
            body: overpassQuery
        })
        .then(res => res.json())
        .then(data => {
            data.elements.forEach(el => {
                if (el.lat && el.lon && el.tags && el.tags.name) {
                    const name = el.tags.name;
                    const hours = el.tags.opening_hours || 'Tersedia info di Google Maps';
                    
                    const popupContent = `
                        <div class="text-center">
                            <h3 class="font-bold text-[#2E471F] mb-1">${name}</h3>
                            <p class="text-xs text-gray-500 mb-3">Jam Buka: ${hours}</p>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${el.lat},${el.lon}" target="_blank" 
                               class="inline-block bg-[#2E471F] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#3d6628] transition-colors">
                               Navigasi ke sini
                            </a>
                        </div>
                    `;
                    
                    // Create green icon for healthy restaurant
                    const greenIcon = new L.Icon({
                      iconUrl: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-2x-green.png',
                      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                      iconSize: [25, 41],
                      iconAnchor: [12, 41],
                      popupAnchor: [1, -34],
                      shadowSize: [41, 41]
                    });

                    L.marker([el.lat, el.lon], {icon: greenIcon}).addTo(map)
                     .bindPopup(popupContent);
                }
            });
        })
        .catch(err => console.error("Error fetching Overpass API:", err));

    }, (error) => {
        mapLoading.innerHTML = '<span class="text-red-500 font-semibold px-4 text-center">Gagal mendeteksi lokasi. Pastikan izin lokasi diaktifkan.</span>';
    }, geoOptions);
});
</script>

@endsection

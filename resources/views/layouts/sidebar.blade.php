@php
  $name = session('user_name', 'Trainer');
@endphp

{{-- OVERLAY: tutup seluruh layar (termasuk header) --}}
<div id="sidebarOverlay"
     class="fixed inset-0 z-[90] bg-black/30 transition-opacity duration-200
            opacity-0 pointer-events-none"></div>

{{-- DRAWER: dari atas sampai bawah, di atas header --}}
<aside id="trainerSidebar"
  class="fixed inset-y-0 left-0 z-[100] h-screen w-[300px] bg-white shadow-xl
         pt-6 pb-6 pl-4 pr-4 transform -translate-x-full transition-transform duration-200
         rounded-none lg:rounded-r-lg">

  {{-- header sidebar: hamburger di KIRI lalu nama --}}
  <div class="sticky top-0 bg-white pb-4">
    <div class="px-1">
      {{-- HAMBURGER (untuk close) --}}
      <button id="btnToggleSidebarInside"
              class="inline-flex items-center justify-center h-10 w-10 rounded-md hover:bg-gray-100 focus:outline-none"
              aria-label="Close Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <div class="ml-2">
        <div class="text-lg font-semibold">{{ $name }}</div>
        <div class="text-sm text-gray-500 -mt-0.5">Trainer</div>
      </div>
    </div>
    <div class="h-px bg-gray-200 mt-4"></div>
  </div>

  {{-- menu --}}
  <nav class="space-y-1 pr-2">
    @php
      $isActive = fn($cond) => $cond
        ? 'bg-[#F4A938] text-white rounded-full px-4 py-3 font-semibold'
        : 'rounded-full px-4 py-3 hover:bg-gray-100';
    @endphp

    <a href="{{ route('trainer.home') }}" class="flex items-center gap-3 {{ $isActive(request()->routeIs('trainer.home')) }}">Home</a>
    <a href="{{ url('/trainer/profile') }}" class="flex items-center gap-3 {{ $isActive(request()->is('trainer/profile')) }}">Profile</a>
    <a href="{{ url('/trainer/foods') }}" class="flex items-center gap-3 {{ $isActive(request()->is('trainer/foods*')) }}">Kelola makanan</a>
    <a href="{{ url('/trainer/workouts') }}" class="flex items-center gap-3 {{ $isActive(request()->is('trainer/workouts*')) }}">Kelola latihan</a>
  </nav>
</aside>


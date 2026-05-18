@php
  $username = session('user_name', 'Client');
  $initials = strtoupper(substr($username, 0, 2));

  $navItems = [
    [
      'route' => 'client.home',
      'match' => 'client.home',
      'label' => 'Home',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
    ],
    [
      'route' => 'client.diary',
      'match' => 'client.diary*',
      'label' => 'Diary',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>',
    ],
    [
      'route' => 'client.exercise',
      'match' => 'client.exercise*',
      'label' => 'Exercise',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/>',
    ],
    [
      'route' => 'client.statistic',
      'match' => 'client.statistic',
      'label' => 'Statistik',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>',
    ],
    [
      'route' => 'client.history',
      'match' => 'client.history',
      'label' => 'Riwayat',
      'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
    ],
  ];
@endphp

{{-- OVERLAY --}}
<div id="sidebarClientOverlay"
     class="fixed inset-0 z-[90] bg-black/50 backdrop-blur-sm transition-opacity duration-300
            opacity-0 pointer-events-none"></div>

{{-- DRAWER --}}
<aside id="clientSidebar"
       class="fixed inset-y-0 left-0 z-[100] h-screen w-72
              bg-[#2E471F] flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out
              shadow-2xl">

  {{-- HEADER --}}
  <div class="flex items-center justify-between px-5 h-16 border-b border-white/10 flex-shrink-0">
    <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-9 w-auto select-none">
    <button id="btnToggleSidebarInside"
            class="flex items-center justify-center h-9 w-9 rounded-lg text-white/70
                   hover:bg-white/10 hover:text-white transition-all duration-150"
            aria-label="Tutup sidebar">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  {{-- USER INFO --}}
  <a href="{{ route('profile.client.show') }}" class="px-5 py-4 border-b border-white/10 flex-shrink-0 hover:bg-white/5 transition-colors block group">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
        <span class="text-xs font-bold text-[#2E471F] tracking-wide">{{ $initials }}</span>
      </div>
      <div class="min-w-0">
        <p class="text-white font-semibold text-sm leading-tight truncate group-hover:text-[#F5A623] transition-colors">{{ $username }}</p>
        <p class="text-white/50 text-xs mt-0.5">Lihat Profil</p>
      </div>
    </div>
  </a>

  {{-- NAV ITEMS --}}
  <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
    @foreach($navItems as $item)
      @php $active = request()->routeIs($item['match']); @endphp
      <a href="{{ route($item['route']) }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                {{ $active
                    ? 'bg-[#F5A623] text-[#1e3a16] font-semibold shadow-sm'
                    : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
        <svg class="h-5 w-5 flex-shrink-0 {{ $active ? 'text-[#1e3a16]' : 'text-white/60' }}"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          {!! $item['icon'] !!}
        </svg>
        {{ $item['label'] }}
        @if($active)
          <span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#1e3a16]/40"></span>
        @endif
      </a>
    @endforeach
  </nav>

  {{-- LOGOUT --}}
  <div class="px-3 py-4 border-t border-white/10 flex-shrink-0">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                     text-white/60 hover:bg-red-500/15 hover:text-red-300 transition-all duration-150">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
        </svg>
        Logout
      </button>
    </form>
  </div>

</aside>

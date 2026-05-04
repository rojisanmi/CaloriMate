@php
  $username = session('user_name', 'Admin');
  $initials = strtoupper(substr($username, 0, 2));
@endphp

{{-- OVERLAY --}}
<div id="sidebarOverlay"
     class="fixed inset-0 z-[90] bg-black/50 backdrop-blur-sm transition-opacity duration-300
            opacity-0 pointer-events-none"></div>

{{-- DRAWER --}}
<aside id="adminSidebar"
       class="fixed inset-y-0 left-0 z-[100] h-screen w-72
              bg-[#2E471F] flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out
              shadow-2xl">

  {{-- HEADER --}}
  <div class="flex items-center justify-between px-5 h-16 border-b border-white/10 flex-shrink-0">
    <div class="flex items-center gap-2">
      <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-9 w-auto select-none">
      <span class="text-xs bg-[#F5A623] text-[#1e3a16] px-2 py-0.5 rounded-full font-bold tracking-wide">ADMIN</span>
    </div>
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
  <div class="px-5 py-4 border-b border-white/10 flex-shrink-0">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0 shadow-sm">
        <span class="text-xs font-bold text-[#2E471F] tracking-wide">{{ $initials }}</span>
      </div>
      <div class="min-w-0">
        <p class="text-white font-semibold text-sm leading-tight truncate">{{ $username }}</p>
        <p class="text-white/50 text-xs mt-0.5">Administrator</p>
      </div>
    </div>
  </div>

  {{-- NAV ITEMS --}}
  <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
    @php
      $navItems = [
        [
          'href'  => route('admin.home'),
          'match' => request()->routeIs('admin.home'),
          'label' => 'Dashboard',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>',
        ],
        [
          'href'  => route('admin.trainers.index'),
          'match' => request()->routeIs('admin.trainers.*'),
          'label' => 'Kelola Trainer',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
        ],
      ];
    @endphp

    @foreach($navItems as $item)
      <a href="{{ $item['href'] }}"
         class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                {{ $item['match']
                    ? 'bg-[#F5A623] text-[#1e3a16] font-semibold shadow-sm'
                    : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
        <svg class="h-5 w-5 flex-shrink-0 {{ $item['match'] ? 'text-[#1e3a16]' : 'text-white/60' }}"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
          {!! $item['icon'] !!}
        </svg>
        {{ $item['label'] }}
        @if($item['match'])
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

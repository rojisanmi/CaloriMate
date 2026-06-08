@php
  $username = session('user_name', 'Trainer');
  $initials = strtoupper(substr($username, 0, 2));
  $sidebarTrainerPhoto = optional(\App\Models\Trainer::where('username', session('user_id'))->first())->photo_url;

  $navItems = [
    [
      'route'   => 'trainer.home',
      'match'   => 'trainer.home',
      'label'   => 'Home',
      'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
    ],
    [
      'route'   => 'trainer.foods.index',
      'match'   => 'trainer.foods.*',
      'label'   => 'Kelola Makanan',
      'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>',
    ],
    [
      'route'   => 'trainer.programs.index',
      'match'   => 'trainer.programs.*',
      'label'   => 'Kelola Latihan',
      'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>',
    ],
  ];
@endphp

{{-- OVERLAY --}}
<div id="sidebarOverlay"
     class="fixed inset-0 z-[90] bg-black/50 backdrop-blur-sm transition-opacity duration-300
            opacity-0 pointer-events-none"></div>

{{-- DRAWER --}}
<aside id="trainerSidebar"
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
  <a href="{{ route('profile.trainer.show') }}" class="px-5 py-4 border-b border-white/10 flex-shrink-0 hover:bg-white/5 transition-colors block group">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
        @if($sidebarTrainerPhoto)
          <img src="{{ $sidebarTrainerPhoto }}" alt="{{ $username }}" class="h-full w-full object-cover">
        @else
          <span class="text-xs font-bold text-[#2E471F] tracking-wide">{{ $initials }}</span>
        @endif
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

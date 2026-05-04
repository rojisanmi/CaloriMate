<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Client') — CaloriMate</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Raleway:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Quicksand', sans-serif; }
    .font-raleway { font-family: 'Raleway', sans-serif; }

    /* Subtle background pattern using CSS gradient */
    .cm-bg {
      background-color: #EFE6D2;
      background-image:
        radial-gradient(circle at 12% 18%, rgba(245,166,35,0.08) 0%, transparent 35%),
        radial-gradient(circle at 88% 82%, rgba(46,71,31,0.06) 0%, transparent 35%),
        radial-gradient(circle at 50% 50%, rgba(46,71,31,0.025) 0%, transparent 50%);
      background-attachment: fixed;
    }
    .cm-bg::before {
      content: '';
      position: fixed;
      inset: 0;
      z-index: -1;
      background-image:
        radial-gradient(circle, rgba(46,71,31,0.04) 1px, transparent 1px);
      background-size: 24px 24px;
      pointer-events: none;
    }

    @keyframes cm-fadein {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes cm-scalein {
      from { opacity: 0; transform: scale(0.95); }
      to   { opacity: 1; transform: scale(1); }
    }
    .cm-fadein  { animation: cm-fadein  0.4s ease-out both; }
    .cm-scalein { animation: cm-scalein 0.35s ease-out both; }
    .cm-delay-1 { animation-delay: 0.08s; }
    .cm-delay-2 { animation-delay: 0.16s; }
    .cm-delay-3 { animation-delay: 0.24s; }
    .cm-delay-4 { animation-delay: 0.32s; }
  </style>
</head>

<body class="min-h-screen cm-bg antialiased flex flex-col">

  {{-- HEADER --}}
  <header class="fixed inset-x-0 top-0 z-50 bg-[#2E471F] shadow-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-3">

      {{-- Hamburger --}}
      <button id="btnOpenSidebar"
              class="flex items-center justify-center h-9 w-9 rounded-lg text-white/80
                     hover:bg-white/10 hover:text-white transition-all duration-150 flex-shrink-0"
              aria-label="Buka menu">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
      </button>

      {{-- Logo --}}
      <a href="{{ route('client.home') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-10 w-auto select-none">
      </a>

      {{-- Page title --}}
      <span class="hidden sm:block text-white/50 text-sm font-medium ml-1">
        @yield('title', 'Dashboard')
      </span>

      <div class="flex-1"></div>

      {{-- AVATAR DROPDOWN --}}
      @php
        $uname = session('user_name', 'C');
        $ini = strtoupper(substr($uname, 0, 2));
        $clientPhoto = optional(\App\Models\Client::where('username', session('user_id'))->first())->photo_url;
      @endphp
      <div class="relative" id="avatarMenuWrap">
        <button id="avatarMenuBtn" type="button"
                class="flex items-center gap-2.5 px-1 py-1 rounded-full hover:bg-white/10 transition-colors duration-150">
          <div class="h-9 w-9 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0 overflow-hidden ring-2 ring-white/20">
            @if($clientPhoto)
              <img src="{{ $clientPhoto }}" alt="{{ $uname }}" class="h-full w-full object-cover">
            @else
              <span class="text-xs font-bold text-[#2E471F]">{{ $ini }}</span>
            @endif
          </div>
          <span class="hidden md:block text-white/90 text-sm font-medium pr-2">{{ $uname }}</span>
          <svg class="hidden md:block h-4 w-4 text-white/60 pr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <div id="avatarMenu"
             class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 origin-top-right">
          <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-semibold text-[#2E471F] truncate">{{ $uname }}</p>
            <p class="text-xs text-gray-400">Client</p>
          </div>
          <a href="{{ route('profile.client.show') }}"
             class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#2E471F] hover:bg-[#EFE6D2] transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            Profil & Pengaturan
          </a>
          <div class="border-t border-gray-100 mt-1 pt-1">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                      class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                </svg>
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </header>

  {{-- MAIN --}}
  <main class="flex-1 pt-16">
    @include('layouts.sidebar-client')
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </div>
  </main>

  {{-- FOOTER --}}
  <footer class="mt-auto w-full bg-[#2E471F]">
    <div class="py-3 text-center text-xs text-white/50">©2025 CaloriMate · Telkom University</div>
  </footer>

  @include('layouts._delete-modal')

  <script>
    const drawer  = document.getElementById('clientSidebar');
    const overlay = document.getElementById('sidebarClientOverlay');
    const btnTop  = document.getElementById('btnOpenSidebar');
    const btnIn   = document.getElementById('btnToggleSidebarInside');

    function lockScroll(lock) {
      document.documentElement.style.overflow =
      document.body.style.overflow = lock ? 'hidden' : '';
    }
    function openSidebar()   { drawer.classList.remove('-translate-x-full'); overlay.classList.remove('opacity-0','pointer-events-none'); lockScroll(true); }
    function closeSidebar()  { drawer.classList.add('-translate-x-full'); overlay.classList.add('opacity-0','pointer-events-none'); lockScroll(false); }
    function toggleSidebar() { drawer.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar(); }

    btnTop?.addEventListener('click', toggleSidebar);
    btnIn?.addEventListener('click',  toggleSidebar);
    overlay?.addEventListener('click', closeSidebar);
    window.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

    // Avatar dropdown
    const avatarBtn  = document.getElementById('avatarMenuBtn');
    const avatarMenu = document.getElementById('avatarMenu');
    avatarBtn?.addEventListener('click', e => {
      e.stopPropagation();
      avatarMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', e => {
      if (!avatarBtn?.contains(e.target) && !avatarMenu?.contains(e.target)) {
        avatarMenu?.classList.add('hidden');
      }
    });
  </script>

</body>
</html>

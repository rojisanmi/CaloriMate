<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') — CaloriMate</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Raleway:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Quicksand', sans-serif; }
    .font-raleway { font-family: 'Raleway', sans-serif; }

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

<body class="min-h-screen bg-[#EFE6D2] antialiased flex flex-col">

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
      <a href="{{ route('admin.home') }}" class="flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-10 w-auto select-none">
      </a>

      <span class="text-xs bg-[#F5A623] text-[#1e3a16] px-2 py-0.5 rounded-full font-bold tracking-wide">ADMIN</span>

      <div class="flex-1"></div>

      {{-- User avatar --}}
      @php $uname = session('user_name', 'A'); $ini = strtoupper(substr($uname, 0, 2)); @endphp
      <div class="flex items-center gap-2.5">
        <div class="h-8 w-8 rounded-full bg-[#F5A623] flex items-center justify-center flex-shrink-0">
          <span class="text-xs font-bold text-[#2E471F]">{{ $ini }}</span>
        </div>
        <span class="hidden md:block text-white/80 text-sm font-medium">{{ $uname }}</span>
      </div>

    </div>
  </header>

  {{-- MAIN --}}
  <main class="flex-1 pt-16">
    @include('layouts.sidebar-admin')
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </div>
  </main>

  {{-- FOOTER --}}
  <footer class="mt-auto w-full bg-[#2E471F]">
    <div class="py-3 text-center text-xs text-white/50">2026 CaloriMate · Telkom University</div>
  </footer>

  @include('layouts._delete-modal')

  <script>
    const drawer  = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
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
  </script>

</body>
</html>

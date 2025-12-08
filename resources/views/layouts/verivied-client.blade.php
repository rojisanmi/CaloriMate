<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Client')</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-[#EFE6D2] antialiased flex flex-col">

  {{-- HEADER --}}
  <header class="fixed inset-x-0 top-0 z-60 bg-[#2E471F] text-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center gap-4">

      {{-- Toggle Sidebar --}}
      <button id="btnOpenSidebar"
              class="inline-flex items-center justify-center h-10 w-10 rounded-md hover:bg-white/10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      {{-- Logo --}}
      <a href="{{ route('client.home') }}" class="text-2xl font-extrabold tracking-tight">
        <img src="{{ asset('images/logo.png') }}" class="h-8 md:h-9 w-auto" draggable="false">
      </a>
    </div>
  </header>

  <main class="flex-1 pt-16">
    {{-- Sidebar client --}}
    @include('client.sidebar')

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
      @yield('content')
    </div>
  </main>

  {{-- FOOTER --}}
  <footer class="mt-auto w-full bg-[#2E471F] text-white/90">
    <div class="py-3 text-center text-sm">©CaloriMate</div>
  </footer>

  {{-- SCRIPT SIDEBAR --}}
  <script>
    const drawer  = document.getElementById('clientSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const btnTop  = document.getElementById('btnOpenSidebar');
    const btnIn   = document.getElementById('btnToggleSidebarInside');

    function lockScroll(lock){
        document.documentElement.style.overflow = document.body.style.overflow = lock ? 'hidden' : '';
    }

    function openSidebar(){ drawer.classList.remove('-translate-x-full'); overlay.classList.remove('opacity-0','pointer-events-none'); lockScroll(true); }
    function closeSidebar(){ drawer.classList.add('-translate-x-full'); overlay.classList.add('opacity-0','pointer-events-none'); lockScroll(false); }
    function toggleSidebar(){ drawer.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar(); }

    btnTop?.addEventListener('click', toggleSidebar);
    btnIn?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', closeSidebar);
  </script>

</body>
</html>

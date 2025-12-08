@php
  $name = session('user_name', auth()->user()->name ?? 'User');
@endphp

<div id="sidebarOverlay"
     class="fixed inset-0 z-[90] bg-black/30 opacity-0 pointer-events-none transition-opacity"></div>

<aside id="clientSidebar"
  class="fixed inset-y-0 left-0 z-[100] h-screen w-[260px] bg-white shadow-xl
         pt-6 pb-6 pl-4 pr-4 transform -translate-x-full transition-transform">

  <div class="sticky top-0 bg-white pb-4 flex items-center gap-3">
      <button id="btnToggleSidebarInside"
              class="inline-flex items-center justify-center h-10 w-10 rounded-md hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <div>
        <div class="text-lg font-semibold">{{ $name }}</div>
        <div class="text-sm text-gray-500 -mt-0.5">User</div>
      </div>
  </div>

  <div class="h-px bg-gray-200 mt-4"></div>

  @php
    $active = fn($route) =>
      request()->routeIs($route) ? 'bg-[#F4A938] text-white font-semibold' : 'hover:bg-gray-100';
  @endphp

  <nav class="space-y-1 mt-4">
    <a href="{{ route('client.home') }}" class="block px-4 py-3 rounded-full {{ $active('client.home') }}">Home</a>
    <a href="{{ route('client.profile') }}" class="block px-4 py-3 rounded-full {{ $active('client.profile') }}">Profile</a>
    <a href="{{ route('client.diary') }}" class="block px-4 py-3 rounded-full {{ $active('client.diary') }}">Diary</a>
    <a href="{{ route('client.exercise') }}" class="block px-4 py-3 rounded-full {{ $active('client.exercise') }}">Exercise</a>
    <a href="{{ route('client.statistic') }}" class="block px-4 py-3 rounded-full {{ $active('client.statistic') }}">Statistic</a>
    <a href="{{ route('client.history') }}" class="block px-4 py-3 rounded-full {{ $active('client.history') }}">History</a>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
      @csrf
      <button type="submit" class="w-full text-left px-4 py-3 rounded-full hover:bg-gray-100">
        Logout
      </button>
    </form>
  </nav>
</aside>

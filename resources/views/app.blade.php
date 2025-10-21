<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Home')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-[#344F1F] text-white antialiased">
  {{-- NAVBAR (exact layout from screenshot) --}}
  <header class="w-full">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center">
        {{-- Left: Brand --}}
        <a href="/home" class="shrink-0 flex items-center gap-2">
          {{-- Logo text (simple mark; ganti ke svg logo-mu kalau ada) --}}
          <span class="text-2xl font-extrabold tracking-tight">CaloriMate</span>
        </a>

        {{-- Center: Menu --}}
        <ul class="mx-auto hidden md:flex items-center gap-10 text-[15px] font-semibold">
          <li>
            <a href="#community"
               class="opacity-90 hover:opacity-100 transition">Community</a>
          </li>
          <li class="relative">
            {{-- ACTIVE (About) --}}
            <a href="#about" class="opacity-100">About</a>
            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 block h-1 w-12 rounded-full bg-white"></span>
          </li>
          <li>
            <a href="#support"
               class="opacity-90 hover:opacity-100 transition">Support</a>
          </li>
          <li>
            <a href="#contact"
               class="opacity-90 hover:opacity-100 transition">Contact</a>
          </li>
        </ul>

        {{-- Right: Register button --}}
        <div class="ml-auto">
          <a href="#register"
             class="inline-flex items-center rounded-full bg-white px-6 py-2 text-sm font-semibold text-[#2E4F2A] shadow-sm hover:opacity-90 transition">
            Register
          </a>
        </div>
      </div>
    </nav>
  </header>

  {{-- PAGE CONTENT --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    @yield('content')
  </main>
</body>
</html>

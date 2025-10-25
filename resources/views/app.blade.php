<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Home')</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mrs+Sheppards&display=swap" rel="stylesheet">
  <style>
    .font-latin { font-family: "Mrs Sheppards", cursive; }
  </style>
</head>


<body class="min-h-screen bg-[#344F1F] text-white antialiased flex flex-col">

@hasSection('hide_nav')
    {{-- no navbar --}}
@else

  {{-- 1) NAVBAR fixed di atas --}}
  <header class="fixed inset-x-0 top-0 z-50 bg-[#344F1F]/95 backdrop-blur supports-[backdrop-filter]:bg-[#344F1F]/85 shadow-sm">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center">
        {{-- caloriMate --}}
        <a href="/home" class="shrink-0 flex items-center gap-2">
          <span class="text-2xl font-extrabold tracking-tight">CaloriMate</span>
        </a>

        {{--  Menu --}}
        <ul class="mx-auto hidden md:flex items-center gap-10 text-[15px] font-semibold">
          <li><a href="#community" class="opacity-90 hover:opacity-100 transition">Community</a></li>
          <li class="relative">
            <a href="#about" class="opacity-100">About</a>
            <span class="absolute left-1/2 -translate-x-1/2 -bottom-2 block h-1 w-12 rounded-full bg-white"></span>
          </li>
          <li><a href="#support" class="opacity-90 hover:opacity-100 transition">Support</a></li>
          <li><a href="#contact" class="opacity-90 hover:opacity-100 transition">Contact</a></li>
        </ul>

        {{-- Register --}}
        <div class="ml-auto">
          <a href="{{ url('/register') }}"
             class="inline-flex items-center rounded-full bg-white px-6 py-2 text-sm font-semibold text-[#2E4F2A] shadow-sm hover:opacity-90 transition">
            Register
          </a>
        </div>
      </div>
    </nav>
  </header>
@endif

  {{-- 2) MAIN CONTENT AREA --}}
  {{-- PAGE CONTENT: padding top buat ruang navbar (h-20 ≈ 80px => pt-24 aman) --}}
  <main class="{{ View::hasSection('hide_nav') ? 'pt-6' : 'pt-24' }} mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-full flex-1">
    @yield('content')
  </main>

  {{-- 3) FOOTER full-bleed, selalu di bawah --}}
  <footer class="mt-auto w-full bg-white">
    {{-- teks/copyright --}}
    <div class="py-3 text-center text-lg opacity-75 text-black">©CaloriMate</div>
  </footer>
</body>
</html>

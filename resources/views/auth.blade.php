<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Auth')</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  {{-- font (opsional, sama seperti app) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mrs+Sheppards&display=swap" rel="stylesheet">
  <style>.font-display{font-family:"Mrs Sheppards",cursive}</style>
</head>

{{-- BG KUSUS AUTH --}}
<body class="min-h-screen antialiased text-[#2E4F2A] flex flex-col
             bg-[#EFE6D2]"> 
   <main class="relative flex-1 overflow-hidden min-h-[calc(100vh-40px)]">
    
    {{-- Atas-tengah --}}
    <span
      class="pointer-events-none select-none absolute -z-10
             top-[-22px] left-1/2 -translate-x-1/2
             h-16 w-16 rounded-full bg-[#F6B357]">
    </span>

    {{-- Kiri  --}}
    <span
      class="pointer-events-none select-none absolute -z-10
             top-1/2 -translate-y-1/2 left-[-70px]
             h-40 w-40 rounded-full bg-[#F6B357]">
    </span>

    {{-- Kanan-bawah --}}
    <span
      class="pointer-events-none select-none absolute -z-10
             bottom-2 right-30
             h-20 w-20 rounded-full bg-[#F6B357]">
    </span>
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </div>
  </main>


  {{-- FOOTER --}}
    <footer class="mt-auto w-full bg-white">
    {{-- teks/copyright --}}
    <div class="py-3 text-center text-lg opacity-75 text-black">©CaloriMate</div>
  </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'CaloriMate') — CaloriMate</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Raleway:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Quicksand', sans-serif; }
    .font-raleway { font-family: 'Raleway', sans-serif; }

    @keyframes auth-fadein {
      from { opacity: 0; transform: translateY(16px) scale(0.98); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .auth-card { animation: auth-fadein 0.45s ease-out both; }
  </style>
</head>

<body class="min-h-screen antialiased bg-[#EFE6D2] flex flex-col">

  <main class="relative flex-1 overflow-hidden min-h-[calc(100vh-44px)] flex items-center justify-center">

    {{-- Decorative blobs --}}
    <span class="pointer-events-none select-none absolute -z-10
                 top-[-22px] left-1/2 -translate-x-1/2
                 h-16 w-16 rounded-full bg-[#F5A623]/60"></span>
    <span class="pointer-events-none select-none absolute -z-10
                 top-1/2 -translate-y-1/2 left-[-70px]
                 h-40 w-40 rounded-full bg-[#F5A623]/40"></span>
    <span class="pointer-events-none select-none absolute -z-10
                 bottom-2 right-20
                 h-20 w-20 rounded-full bg-[#F5A623]/40"></span>

    <div class="mx-auto max-w-6xl w-full px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </div>

  </main>

  <footer class="mt-auto w-full bg-white">
    <div class="py-3 text-center text-sm text-gray-400">©2025 CaloriMate · Telkom University</div>
  </footer>

</body>
</html>

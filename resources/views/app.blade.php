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
  <link href="https://fonts.googleapis.com/css2?family=Mrs+Sheppards&family=Modak&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    .font-latin { font-family: "Mrs Sheppards", cursive; }
    .font-latin { font-family: "Mrs Sheppards", cursive; }
  .font-modak { font-family: "Modak", system-ui; }
  .font-instrument {
    font-family: 'Instrument Sans', sans-serif;}
    .font-raleway { font-family: "Raleway", sans-serif; }
  .font-quicksand { font-family: "Quicksand", sans-serif; }
  html { scroll-behavior: smooth; }
  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 100%;
    height: 2px;
    border-radius: 9999px;
    background: #F5A623;
    transition: transform 0.25s ease;
  }
  .nav-link.active {
    opacity: 1;
  }
  .nav-link.active::after {
    transform: translateX(-50%) scaleX(1);
  }
  </style>
</head>


<body class="min-h-screen bg-[#344F1F] text-white antialiased flex flex-col">

@hasSection('hide_nav')
    {{-- no navbar --}}
@else

  {{-- NAVBAR --}}
  <header class="fixed inset-x-0 top-0 z-50 bg-[#344F1F]/95 backdrop-blur supports-[backdrop-filter]:bg-[#344F1F]/85 shadow-sm">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center">
        {{-- Logo CaloriMate --}}
        <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-2">
          <img src="{{ asset('images/logo.png') }}" 
               alt="CaloriMate" 
               class="h-14 sm:h-16 w-auto object-contain ml-4">
        </a>

        {{-- Menu --}}
        <ul class="mx-auto hidden md:flex items-center gap-10 text-[15px] font-semibold" id="navMenu">
          <li><a href="#hero" data-section="hero"        class="nav-link relative opacity-70 hover:opacity-100 transition-opacity">Home</a></li>
          <li><a href="#about"     data-section="about"     class="nav-link relative opacity-70 hover:opacity-100 transition-opacity">Fitur</a></li>
          <li><a href="#community" data-section="community" class="nav-link relative opacity-70 hover:opacity-100 transition-opacity">Cara Kerja</a></li>
          <li><a href="#support"   data-section="support"   class="nav-link relative opacity-70 hover:opacity-100 transition-opacity">Keunggulan</a></li>
          <li><a href="#contact"   data-section="contact"   class="nav-link relative opacity-70 hover:opacity-100 transition-opacity">Kontak</a></li>
        </ul>

        {{-- Register --}}
        <div class="ml-2">
          <a href="{{ url('/register') }}"
             class="inline-flex items-center rounded-full bg-[#F5A623] px-6 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition">
            Daftar
          </a>
        </div>

        {{-- Login --}}
        <div class="ml-4">
          <a href="{{ url('/login') }}"
             class="inline-flex items-center rounded-full bg-white px-6 py-2 text-sm font-semibold text-[#2E4F2A] shadow-sm hover:opacity-90 transition">
            Masuk
          </a>
        </div>
      </div>
    </nav>
  </header>
@endif

 {{-- MAIN CONTENT AREA --}}
  <main class="{{ View::hasSection('hide_nav') ? 'pt-6' : 'pt-24' }} w-full flex-1">
    @yield('content')
  </main>

  {{-- Active nav highlight via Intersection Observer --}}
  <script>
    (function () {
      const links = document.querySelectorAll('.nav-link[data-section]');
      if (!links.length) return;

      function setActive(id) {
        links.forEach(l => l.classList.toggle('active', l.dataset.section === id));
      }

      // Aktifkan "Home" secara default saat halaman baru dibuka
      setActive('hero');

      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) setActive(entry.target.id);
        });
      }, { rootMargin: '-20% 0px -75% 0px', threshold: 0 });

      links.forEach(l => {
        const el = document.getElementById(l.dataset.section);
        if (el) observer.observe(el);
      });
    })();
  </script>

  {{-- FOOTER --}}
  <footer class="mt-auto w-full bg-white">
    {{-- teks/copyright --}}
    <div class="py-3 text-center text-lg opacity-75 text-black">©CaloriMate</div>
  </footer>
</body>
</html>
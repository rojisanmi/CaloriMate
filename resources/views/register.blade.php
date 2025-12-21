@extends('auth')

@section('title', 'Register')

@section('content')

<a href="{{ route('home') }}"
   class="absolute left-4 top-4 md:left-8 md:top-6 z-20 flex items-center justify-center w-10 h-10 md:w-12 md:h-12 bg-[#2d5016] rounded-lg hover:bg-[#3d6020] transition">
  <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
  </svg>
</a>


<div class="rounded-3xl overflow-hidden mx-auto w-full max-w-5xl
            grid grid-cols-1 lg:grid-cols-2 shadow-lg bg-white">

  {{-- KIRI: panel hijau + dekor + maskot --}}
  <div class="relative bg-[#2d5016] text-white px-6 pt-6 pb-2 flex items-end">
    {{-- dekor dalam kartu (pakai asset() bukan path absolut) --}}
    <img src="{{ asset('images/ellipse-register1.png') }}" alt="" aria-hidden="true"
         class="absolute top-16 left-8 w-24 h-24 pointer-events-none select-none">
    <img src="{{ asset('images/ellipse-register1.png') }}" alt="" aria-hidden="true"
         class="absolute bottom-20 right-12 w-20 h-20 pointer-events-none select-none">

    {{-- shape besar belakang --}}
    <img src="{{ asset('images/group-54.png') }}" alt="" aria-hidden="true"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-80 h-80 object-contain pointer-events-none select-none">

    {{-- maskot --}}
    <img src="{{ asset('images/mascot-register.png') }}" alt="CaloriMate Mascot"
         class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none">
  </div>

  {{-- KANAN: form register (isi TIDAK diubah) --}}
  <div class="bg-white p-8 md:p-10 lg:p-12 grid place-items-center">
    <div class="w-full max-w-sm">
      {{-- logo --}}
      <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="CaloriMate" class="h-12">
      </div>

      <h2 class="text-3xl md:text-4xl font-bold text-[#2d5016] text-center mb-6">Register</h2>

      <form method="POST" action="{{ route('register.do') }}" class="space-y-5">
        @csrf

        @if ($errors->any())
          <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm">
            <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Email
          </span>
          <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Username
          </span>
          <input name="username" placeholder="Username" value="{{ old('username') }}" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Password
          </span>
          <input type="password" name="password" placeholder="Password" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <label class="block">
          <span class="block mb-1 text-sm font-semibold text-[#2d5016]">
            Confirm Password
          </span>
          <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                 class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
        </label>

        <div class="flex justify-center pt-6">
          <button type="submit"
                  class="bg-[#2d5016] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6020] transition shadow-lg">
            Selanjutnya
          </button>
        </div>
        <div class="flex justify-center pt-4">
          <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login.client') }}"
              class="font-semibold text-[#2d5016] hover:underline hover:text-[#3d6020] transition">
              Login
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

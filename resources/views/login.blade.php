@extends('auth')

@section('title','Login')

@section('content')
<div class="rounded-3xl overflow-hidden mx-auto w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 shadow-lg bg-white">

  {{-- KIRI --}}
  <div
    class="relative bg-[#344F1F] text-white flex items-end
           px-6 pt-6 pb-0 lg:-mr-2 lg:z-10">
    {{-- dekor lingkaran --}}
    <img
      src="{{ asset('images/group-54.png') }}"
      alt=""
      aria-hidden="true"
      class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 object-contain pointer-events-none select-none" />
    {{-- maskot --}}
    <img
      src="{{ asset('images/mascot-register.png') }}"
      alt="CaloriMate Mascot"
      class="relative z-10 w-full max-w-[420px] h-auto mx-auto select-none" />
  </div>

  {{-- KANAN--}}
  <div class="relative bg-white p-8 md:p-10 lg:p-12 flex items-center justify-center">
      <img src="{{ asset('images/logo-warna.png') }}" alt="CaloriMate Logo" class="absolute top-6 md:top-8 left-1/2 -translate-x-1/2
              h-16 md:h-20 lg:h-24 select-none" />
      {{-- KONTEN LOGIN --}}
      <div class="w-full max-w-sm text-center pt-12 md:pt-28 lg:pt-20">
        <h2 class="text-3xl md:text-4xl font-bold text-[#2E4F2A] mb-6">Login</h2>

        <div class="space-y-4">
          <a href="{{ url('/login/trainer') }}"
            class="block rounded-lg bg-[#2E7D32] px-5 py-3 font-semibold text-white shadow-md hover:opacity-90">
            Login as Trainer
          </a>
          <div class="text-sm text-[#2E4F2A]/70">Or</div>
          <a href="{{ url('/login/user') }}"
            class="block rounded-lg bg-[#2E7D32] px-5 py-3 font-semibold text-white shadow-md hover:opacity-90">
            Login as Client
          </a>
        </div>
      </div>
  </div>
</div>
@endsection

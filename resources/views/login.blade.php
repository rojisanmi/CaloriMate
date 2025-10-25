@extends('auth')

@section('title', 'Login')

@section('content')
<div class="overflow-hidden mx-auto max-w-4xl bg-white rounded-3xl shadow-lg grid grid-cols-1 lg:grid-cols-2 mt-10 lg:min-h-[550px]">
    {{-- Kiri --}}
  <div
    class="bg-[#344F1F] text-white p-6 rounded-3xl h-full
           lg:rounded-tr-3xl lg:rounded-br-3xl
           lg:-mr-2 lg:z-10 ">   
    <img src="{{ asset('images/maskot_login.png') }}"
         class="max-h-[430px] w-auto mx-auto" alt="">
  </div>

  {{-- Kanan --}}
  <div class="bg-white rounded-3xl p-8  flex flex-col items-center justify-center">
    <div class="text-2xl font-extrabold">
      <span class="text-[#2E4F2A]">Calori</span><span class="text-[#F2A94A]">Mate</span>
    </div>
    <h2 class="mt-4 text-2xl font-semibold text-[#2E4F2A]">Login</h2>

    <div class="mt-8 w-full max-w-xs space-y-4">
      <a href="{{ url('/login/trainer') }}"
         class="block rounded-lg bg-[#2E7D32] px-5 py-3 text-center font-semibold text-white shadow-md hover:opacity-90">
        Login as Trainer
      </a>
      <div class="text-center text-sm text-[#2E4F2A]/70">Or</div>
      <a href="{{ url('/login/user') }}"
         class="block rounded-lg bg-[#2E7D32] px-5 py-3 text-center font-semibold text-white shadow-md hover:opacity-90">
        Login as User
      </a>
    </div>
  </div>
</div>
@endsection

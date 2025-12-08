@extends('layouts.verivied-client')

@section('title','Client Home')

@section('content')
  <section class="text-center py-10">
    <h1 class="text-4xl md:text-5xl font-extrabold text-[#2E471F]">
      Hello {{ $user->name ?? 'User' }},
    </h1>

    <p class="mt-4 text-xl md:text-2xl text-[#2E471F]/90">
      ready to track your calories today?
    </p>

    {{-- Gelombang --}}
    <div class="mt-16">
      <div class="w-full h-40 bg-[#F4A938] rounded-b-[50%]"></div>
      <div class="w-full h-14 bg-[#2E471F]"></div>
    </div>
  </section>
@endsection
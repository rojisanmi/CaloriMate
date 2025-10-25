@extends('layouts.verivied')

@section('title','Trainer Home')

@section('content')
  <section class="text-center py-10 md:py-16">
    <h1 class="text-4xl md:text-5xl font-extrabold text-[#2E471F]">
      Hello Trainer,
    </h1>
    <p class="mt-4 text-xl md:text-3xl text-[#2E471F]/90">
      Let’s Keep Everything Running Smoothly!
    </p>

    <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
      <a href="{{ url('/trainer/foods') }}"
         class="inline-block rounded-lg bg-[#2E471F] text-white font-semibold px-8 py-3 shadow hover:opacity-90">
        Kelola Makanan
      </a>
      <a href="{{ url('/trainer/workouts') }}"
         class="inline-block rounded-lg bg-[#2E471F] text-white font-semibold px-8 py-3 shadow hover:opacity-90">
        Kelola Latihan
      </a>
    </div>
  </section>
@endsection

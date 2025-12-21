@extends('layouts.verivied')

@section('title','Trainer Home')

@section('content')
<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.5s ease-out;
    }

    .animate-delay-1 {
        animation-delay: 0.1s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-2 {
        animation-delay: 0.2s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-3 {
        animation-delay: 0.3s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
</style>

<section class="text-center py-10 md:py-16">
    <h1 class="text-4xl md:text-5xl font-extrabold text-[#2E471F] animate-fade-in-down">
      Hello Trainer,
    </h1>
    <p class="mt-4 text-xl md:text-3xl text-[#2E471F]/90 animate-fade-in-down animate-delay-1">
      Let's Keep Everything Running Smoothly!
    </p>

    <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
      <a href="{{ url('/trainer/foods') }}"
         class="inline-block rounded-lg bg-[#2E471F] text-white font-semibold px-8 py-3 shadow hover:opacity-90 
         transition-all duration-200 hover:shadow-xl hover:-translate-y-1
         animate-scale-in animate-delay-2">
        Kelola Makanan
      </a>
      <a href="{{ url('/trainer/programs') }}"
         class="inline-block rounded-lg bg-[#2E471F] text-white font-semibold px-8 py-3 shadow hover:opacity-90
         transition-all duration-200 hover:shadow-xl hover:-translate-y-1
         animate-scale-in animate-delay-3">
        Kelola Latihan
      </a>
    </div>
</section>
@endsection
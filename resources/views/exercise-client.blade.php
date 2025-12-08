@extends('layouts.verivied-client')

@section('title','Exercise')

@section('content')
<section class="max-w-6xl mx-auto">

    <div class="bg-[#EFE6D2] rounded-[40px] p-10 shadow">

        {{-- TITLE --}}
        <h2 class="text-center text-3xl font-extrabold text-[#2E471F] mb-10">
            Program Latihan
        </h2>

        {{-- PROGRAM GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($programs as $program)
            <div class="bg-[#F4A938] rounded-xl p-4 text-white shadow">

                <div class="flex items-center gap-4">
                    <img src="{{ asset($program['image']) }}"
                         class="w-14 h-14 rounded-full object-cover">

                    <div>
                        <h4 class="font-bold leading-tight">
                            {{ $program['title'] }}
                        </h4>

                        <div class="mt-2 flex gap-2">
                            <a href="{{ route('client.exercise.show', $program['id']) }}"
                              class="px-3 py-1 bg-white text-[#2E471F] text-xs rounded-full font-semibold">
                              Lihat Detail
                            </a>

                            <form method="POST" action="{{ route('client.exercise.start', $program['id']) }}" class="inline">
                              @csrf
                              <button type="submit"
                                class="px-3 py-1 bg-[#2E471F] text-white text-xs rounded-full font-semibold">
                                Mulai Latihan
                              </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach

        </div>
    </div>

</section>
@endsection

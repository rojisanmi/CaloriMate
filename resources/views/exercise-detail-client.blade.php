@extends('layouts.verivied-client')

@section('title', $program->name)

@section('content')
<section class="max-w-4xl mx-auto">

    <div class="bg-[#FFFFFF] rounded-xl p-8 shadow">

        <h2 class="text-2xl font-bold text-[#2E471F] mb-2">{{ $program->name }}</h2>

        <div class="flex gap-4 text-sm text-[#2E471F]/80 mb-6">
            @if($program->type)
                <span>Tipe: {{ $program->type }}</span>
            @endif
            @if($program->difficulty)
                <span>Kesulitan: {{ $program->difficulty }}</span>
            @endif
            @if($program->duration_minutes)
                <span>Durasi: {{ $program->duration_minutes }} menit</span>
            @endif
        </div>

        <h3 class="font-semibold text-[#2E471F] mb-3">Daftar Latihan</h3>

        <div class="bg-white rounded-lg overflow-hidden border mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-[#2E471F]">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama Latihan</th>
                        <th class="px-4 py-2 text-left">Durasi</th>
                        <th class="px-4 py-2 text-left">Intensitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($program->items as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item->exercise_name }}</td>
                        <td class="px-4 py-2">{{ $item->duration_minutes }} menit</td>
                        <td class="px-4 py-2">{{ ucfirst($item->intensity_level) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500">
                            Belum ada item latihan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('client.exercise') }}"
               class="flex-1 text-center py-3 rounded-lg border border-[#2E471F] text-[#2E471F] font-semibold hover:bg-gray-100">
                Kembali
            </a>
            <form method="POST" action="{{ route('client.exercise.start', $program->program_id) }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-3 rounded-lg bg-[#2E471F] text-white font-semibold hover:opacity-90">
                    Mulai Latihan
                </button>
            </form>
        </div>
    </div>

</section>
@endsection

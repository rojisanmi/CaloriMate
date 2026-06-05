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
            {{-- Tombol ini sekarang membuka modal konfirmasi --}}
            <button id="btnMulaiLatihan"
               onclick="document.getElementById('readyModal').classList.remove('hidden'); void document.getElementById('readyModal').offsetWidth; document.getElementById('modalCard').classList.remove('scale-75','opacity-0'); document.getElementById('modalCard').classList.add('scale-100','opacity-100')"
               class="flex-1 text-center py-3 rounded-lg bg-[#2E471F] text-white font-semibold hover:opacity-90 cursor-pointer transition-all">
                Mulai Latihan
            </button>
        </div>
    </div>

</section>

{{-- ====================== MODAL KONFIRMASI SIAP LATIHAN ====================== --}}
<div id="readyModal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
     style="background: rgba(0,0,0,0.55); backdrop-filter: blur(6px);">

    {{-- Klik backdrop untuk tutup --}}
    <div class="absolute inset-0" onclick="closeReadyModal()"></div>

    {{-- Card modal --}}
    <div id="modalCard"
         class="relative w-full max-w-sm rounded-3xl bg-white shadow-2xl
                transform scale-75 opacity-0
                transition-all duration-300 ease-out
                overflow-hidden z-10">

        {{-- Decorative top gradient bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-[#2E471F] via-[#F5A623] to-[#2E471F]"></div>

        {{-- Confetti dots decoration --}}
        <div class="absolute top-4 left-4 w-3 h-3 rounded-full bg-[#F5A623]/40"></div>
        <div class="absolute top-8 right-6 w-2 h-2 rounded-full bg-[#2E471F]/30"></div>
        <div class="absolute top-16 left-8 w-1.5 h-1.5 rounded-full bg-[#F5A623]/50"></div>

        <div class="px-6 pt-5 pb-6 flex flex-col items-center text-center">

            {{-- Maskot image --}}
            <div class="relative mb-1">
                <div class="absolute -inset-3 bg-[#F5A623]/10 rounded-full blur-xl"></div>
                <img src="{{ asset('images/maskot-nunjuk.png') }}"
                     alt="Maskot CaloriMate"
                     class="relative w-44 h-44 object-contain drop-shadow-lg
                            animate-[bounce_2s_ease-in-out_infinite]"
                     style="animation: maskotFloat 2.5s ease-in-out infinite;">
            </div>

            {{-- Teks --}}
            <div class="mb-6">
                <h2 class="font-raleway text-2xl font-extrabold text-[#2E471F] mb-1 leading-tight">
                    Udah siap belum nih? 💪
                </h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Program <span class="font-semibold text-[#2E471F]">{{ $program->name }}</span>
                    siap dimulai!<br>
                    Pastikan tubuh kamu udah siap ya, bos!
                </p>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex flex-col gap-3 w-full">
                {{-- Redi Bos! --}}
                <a href="{{ route('client.exercise.play', $program->program_id) }}"
                   id="btnRedi"
                   class="relative w-full py-3.5 rounded-2xl bg-[#2E471F] text-white font-bold text-base
                          overflow-hidden group transition-all duration-200
                          hover:shadow-lg hover:shadow-[#2E471F]/30 hover:-translate-y-0.5 active:scale-95">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                        </svg>
                        Redi Bos! 🔥
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#2E471F] to-[#3d6628]
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                </a>

                {{-- Ntar Dulu Deh --}}
                <button onclick="closeReadyModal()"
                        class="w-full py-3 rounded-2xl border-2 border-[#2E471F]/20 text-[#2E471F]/70
                               font-semibold text-sm bg-transparent
                               hover:border-[#2E471F]/40 hover:text-[#2E471F]
                               hover:bg-[#EFE6D2]/50 transition-all duration-200 active:scale-95">
                    😅 Ntar Dulu Deh...
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk modal --}}
<script>
function closeReadyModal() {
    const card = document.getElementById('modalCard');
    const modal = document.getElementById('readyModal');
    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-75', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 280);
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReadyModal();
});
</script>

<style>
@keyframes maskotFloat {
    0%, 100% { transform: translateY(0px) rotate(-2deg); }
    50%       { transform: translateY(-10px) rotate(2deg); }
}
</style>

@endsection

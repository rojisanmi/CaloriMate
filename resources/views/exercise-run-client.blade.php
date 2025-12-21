@extends('layouts.verivied-client')

@section('title', 'Mulai ' . $program->name)

@section('content')
<section class="max-w-3xl mx-auto">

    <div class="bg-[#FFFFFF] rounded-xl p-8 shadow">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-[#2E471F]">
                    {{ $program->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    Latihan {{ $step + 1 }} dari {{ $totalSteps }}
                </p>
            </div>
        </div>

        {{-- CURRENT EXERCISE INFO --}}
        <div class="mb-6">
            <p class="text-sm font-semibold text-[#2E471F] mb-1">
                Nama Latihan
            </p>
            <p class="text-lg font-bold text-[#2E471F] mb-3">
                {{ $item->exercise_name }}
            </p>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                <span class="inline-flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#2E471F]"></span>
                    Durasi: {{ $item->duration_minutes }} menit
                </span>
                <span class="inline-flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#F4A938]"></span>
                    Intensitas: {{ ucfirst($item->intensity_level) }}
                </span>
            </div>
        </div>

        {{-- INSTRUCTION / DESCRIPTION PLACEHOLDER --}}
        <div class="mb-6 bg-[#F7F7F7] rounded-lg p-4 border border-gray-100">
            <h3 class="font-semibold text-[#2E471F] mb-2">
                TIPS
            </h3>
            <p class="text-sm text-gray-700 leading-relaxed">
                Fokus pada teknik yang benar dan jaga pernapasan tetap teratur selama latihan.
            </p>
        </div>

        {{-- TIMER --}}
        <div class="mb-8 text-center">
            <p class="text-sm text-gray-500 mb-1">Sisa Waktu</p>
            <div id="timerDisplay"
                 class="inline-flex items-center justify-center rounded-full border border-[#2E471F] px-8 py-3
                        text-2xl font-mono font-semibold text-[#2E471F] bg-[#F2EAD3]">
                00:00
            </div>
        </div>

        {{-- CONTROLS --}}
        <div class="flex flex-wrap gap-3 justify-between">
            {{-- Batalkan --}}
            <a href="{{ route('client.exercise.show', $program->program_id) }}"
               class="px-4 py-2 rounded-lg border border-red-400 text-red-600 text-sm font-semibold hover:bg-red-50">
                Batalkan
            </a>

            <div class="flex gap-3 ml-auto">
                {{-- Kembali --}}
                @php
                    $isFirst = $step === 0;
                @endphp
                <a href="{{ $isFirst ? '#' : route('client.exercise.play', ['id' => $program->program_id, 'step' => $step - 1]) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold
                          {{ $isFirst ? 'text-gray-400 cursor-not-allowed opacity-60' : 'text-[#2E471F] hover:bg-gray-50' }}">
                    Kembali
                </a>

                {{-- Selanjutnya --}}
                @php
                    $isLast = ($step + 1) >= $totalSteps;
                @endphp
                <button
                    id="nextButton"
                    type="button"
                    data-next-url="{{ $isLast ? '' : route('client.exercise.play', ['id' => $program->program_id, 'step' => $step + 1]) }}"
                    data-finish-url="{{ route('client.exercise.start', $program->program_id) }}"
                    data-is-last="{{ $isLast ? '1' : '0' }}"
                    class="px-5 py-2 rounded-lg bg-[#2E471F] text-white text-sm font-semibold hover:opacity-90">
                    {{ $isLast ? 'Selanjutnya (Selesai Program)' : 'Selanjutnya' }}
                </button>
            </div>
        </div>
    </div>

</section>

<form id="finishForm" method="POST" action="{{ route('client.exercise.start', $program->program_id) }}" class="hidden">
    @csrf
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Timer
    let remaining = {{ $durationSeconds }};
    const display = document.getElementById('timerDisplay');

    function formatTime(sec) {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function tick() {
        if (!display) return;
        display.textContent = formatTime(remaining);
        if (remaining > 0) {
            remaining--;
            setTimeout(tick, 1000);
        }
    }

    tick();
    // Next Button
    const nextBtn = document.getElementById('nextButton');
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            const isLast = this.getAttribute('data-is-last') === '1';
            if (isLast) {
                const form = document.getElementById('finishForm');
                if (form) {
                    form.submit();
                }
            } else {
                const url = this.getAttribute('data-next-url');
                if (url) {
                    window.location.href = url;
                }
            }
        });
    }
});
</script>
@endsection



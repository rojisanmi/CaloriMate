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

            {{-- Tombol buka/tutup mini player --}}
            <button id="musicToggleBtn"
                    onclick="toggleMusicPlayer()"
                    title="Musik Latihan"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EFE6D2] text-[#2E471F]
                           font-semibold text-sm hover:bg-[#F5A623]/20 transition-all duration-200">
                {{-- Dot indikator --}}
                <span id="musicDot" class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F5A623] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#F5A623]"></span>
                </span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <span id="musicBtnLabel">Musik Latihan</span>
            </button>
        </div>

        {{-- INFO LATIHAN --}}
        <div class="mb-6">
            <p class="text-sm font-semibold text-[#2E471F] mb-1">Nama Latihan</p>
            <p class="text-lg font-bold text-[#2E471F] mb-3">{{ $item->exercise_name }}</p>
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

        {{-- TIPS --}}
        <div class="mb-6 bg-[#F7F7F7] rounded-lg p-4 border border-gray-100">
            <h3 class="font-semibold text-[#2E471F] mb-2">TIPS</h3>
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

        {{-- KONTROL NAVIGASI --}}
        <div class="flex flex-wrap gap-3 justify-between">
            <a href="{{ route('client.exercise.show', $program->program_id) }}"
               class="px-4 py-2 rounded-lg border border-red-400 text-red-600 text-sm font-semibold hover:bg-red-50">
                Batalkan
            </a>
            <div class="flex gap-3 ml-auto">
                @php $isFirst = $step === 0; @endphp
                <a href="{{ $isFirst ? '#' : route('client.exercise.play', ['id' => $program->program_id, 'step' => $step - 1]) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold
                          {{ $isFirst ? 'text-gray-400 cursor-not-allowed opacity-60' : 'text-[#2E471F] hover:bg-gray-50' }}">
                    Kembali
                </a>
                @php $isLast = ($step + 1) >= $totalSteps; @endphp
                <button id="nextButton" type="button"
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

{{-- ======================== FLOATING MUSIC PLAYER ======================== --}}
{{--
    Letakkan 1 file MP3 di: public/audio/workout.mp3
    Ganti nama file di baris: audio.src = '/audio/workout.mp3'
--}}

{{-- Player card (hidden by default, muncul saat tombol diklik) --}}
<div id="musicPlayer"
     class="fixed bottom-6 right-6 z-50"
     style="display:none; width:300px;">

    <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10"
         style="background: linear-gradient(150deg,#162510 0%,#2E471F 100%);">

        {{-- Top accent --}}
        <div class="h-1 bg-gradient-to-r from-[#F5A623] via-[#fff] to-[#F5A623] opacity-40"></div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-2.5">
            <div class="flex items-center gap-2">
                {{-- Live dot --}}
                <span class="relative flex h-2 w-2" id="liveDot">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F5A623] opacity-60"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#F5A623]"></span>
                </span>
                <span class="text-white font-bold text-xs tracking-wide">🎵 Musik Latihan</span>
            </div>
            <div class="flex gap-0.5">
                {{-- Minimize --}}
                <button onclick="minimizePlayer()"
                        class="h-7 w-7 rounded-lg flex items-center justify-center text-white/50
                               hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/>
                    </svg>
                </button>
                {{-- Close --}}
                <button onclick="closePlayer()"
                        class="h-7 w-7 rounded-lg flex items-center justify-center text-white/50
                               hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Song info --}}
        <div class="px-4 pb-1 flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(245,166,35,.15);">
                <svg class="h-5 w-5 text-[#F5A623]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-bold text-sm truncate">Workout Music</p>
                <p class="text-white/40 text-xs">CaloriMate · Selamat Berlatih 💪</p>
            </div>
        </div>

        {{-- Waveform animasi --}}
        <div class="flex items-end justify-center gap-[3px] px-4 pt-2 pb-1" style="height:32px;">
            @php
            $heights = [6,10,16,22,14,20,8,18,24,12,20,16,10,26,14,18,22,8,16,20,12,24,10,18,14,20,8,16];
            $speeds  = [0.6,0.75,0.5,0.9,0.65,0.8,0.55,0.7,0.6,0.85,0.65,0.75,0.5,0.9,0.6,0.7,0.8,0.55,0.65,0.9,0.6,0.75,0.5,0.8,0.65,0.7,0.6,0.85];
            @endphp
            @foreach($heights as $i => $h)
            <div class="wave-bar rounded-full"
                 style="width:3px; background:#F5A623; height:{{ $h }}px; opacity:0.3;
                        animation: waveAnim {{ $speeds[$i] }}s ease-in-out infinite alternate;
                        animation-play-state: paused; transform-origin: bottom;">
            </div>
            @endforeach
        </div>

        {{-- Seek bar + waktu --}}
        <div class="px-4 pt-1 pb-0">
            <input id="seekBar" type="range" min="0" max="100" value="0"
                   oninput="seekAudio(this.value)"
                   class="w-full cursor-pointer"
                   style="height:3px; accent-color:#F5A623; background:rgba(255,255,255,.15);
                          border-radius:99px; appearance:none; -webkit-appearance:none;">
            <div class="flex justify-between mt-1" style="font-size:10px; color:rgba(255,255,255,.35);">
                <span id="curTime">0:00</span>
                <span id="totTime">0:00</span>
            </div>
        </div>

        {{-- Kontrol --}}
        <div class="flex items-center justify-between px-4 py-3">

            {{-- Volume --}}
            <div class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"
                     style="color:rgba(255,255,255,.35);">
                    <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                </svg>
                <input id="volBar" type="range" min="0" max="100" value="80"
                       oninput="setVol(this.value)"
                       class="cursor-pointer"
                       style="width:54px; height:3px; accent-color:#F5A623;
                              background:rgba(255,255,255,.15); border-radius:99px;
                              appearance:none; -webkit-appearance:none;">
            </div>

            {{-- Play / Pause --}}
            <button id="playPauseBtn" onclick="togglePlay()"
                    class="h-12 w-12 rounded-full flex items-center justify-center
                           transition-all duration-200 hover:scale-110 active:scale-95"
                    style="background:#F5A623; box-shadow:0 0 22px rgba(245,166,35,.45);">
                <svg id="iconPlay" class="h-5 w-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"
                     style="color:#2E471F;">
                    <path d="M8 5v14l11-7z"/>
                </svg>
                <svg id="iconPause" class="h-5 w-5 hidden" fill="currentColor" viewBox="0 0 24 24"
                     style="color:#2E471F;">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
            </button>

            {{-- Loop --}}
            <button id="loopBtn" onclick="toggleLoop()"
                    class="h-8 w-8 rounded-lg flex items-center justify-center transition-colors"
                    style="background:rgba(255,255,255,.07);"
                    title="Ulangi lagu">
                <svg id="loopIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2"
                     style="color:rgba(255,255,255,.35);">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Bubble saat di-minimize --}}
<div id="musicBubble"
     onclick="expandPlayer()"
     class="fixed bottom-6 right-6 z-50 cursor-pointer"
     style="display:none;">
    <div class="relative h-14 w-14 rounded-full flex items-center justify-center
                hover:scale-110 transition-transform duration-200"
         style="background:#2E471F; box-shadow:0 4px 20px rgba(46,71,31,.5);">
        <div class="absolute inset-0 rounded-full animate-ping opacity-15"
             style="background:#2E471F;"></div>
        <svg class="h-6 w-6 relative z-10" fill="currentColor" viewBox="0 0 24 24"
             style="color:#F5A623;">
            <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
        </svg>
    </div>
</div>

<script>
// ── Timer latihan ──
document.addEventListener('DOMContentLoaded', function () {
    let remaining = {{ $durationSeconds }};
    const display = document.getElementById('timerDisplay');

    function fmt(s) {
        return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0');
    }
    function tick() {
        if (display) display.textContent = fmt(remaining);
        if (remaining > 0) { remaining--; setTimeout(tick, 1000); }
    }
    tick();

    const nextBtn = document.getElementById('nextButton');
    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (this.getAttribute('data-is-last') === '1') {
                document.getElementById('finishForm')?.submit();
            } else {
                const url = this.getAttribute('data-next-url');
                if (url) window.location.href = url;
            }
        });
    }
});


// ── Audio ──
const audio  = new Audio('/audio/workout.mp3');
audio.volume = 0.8;
audio.loop   = true; // default looping
let isLooping = true;
let musicStarted = false;

// Musik hanya dimulai saat user klik tombol "Musik Latihan" → toggleMusicPlayer()
// Play/Pause dikontrol user lewat tombol play di dalam player.


// ── Player UI visibility ──
const player = document.getElementById('musicPlayer');
const bubble = document.getElementById('musicBubble');

function showPlayer() {
    player.style.display = 'block';
    player.style.opacity  = '0';
    player.style.transform = 'translateY(16px) scale(0.95)';
    requestAnimationFrame(() => {
        player.style.transition = 'opacity .3s ease, transform .3s ease';
        player.style.opacity    = '1';
        player.style.transform  = 'translateY(0) scale(1)';
    });
    document.getElementById('musicBtnLabel').textContent = 'Sembunyikan';
}

function toggleMusicPlayer() {
    if (player.style.display === 'none' || !player.style.display) {
        // Tampilkan player, tapi TIDAK auto-play — biarkan user klik play sendiri
        if (!musicStarted) {
            // Pertama kali dibuka: inisialisasi audio tapi tidak langsung play
            musicStarted = true;
            showPlayer();
            setPlayingUI(false);
        } else {
            showPlayer();
        }
    } else {
        hidePlayer();
    }
}

function hidePlayer() {
    player.style.transition = 'opacity .25s ease, transform .25s ease';
    player.style.opacity    = '0';
    player.style.transform  = 'translateY(10px) scale(0.95)';
    setTimeout(() => { player.style.display = 'none'; }, 260);
    document.getElementById('musicBtnLabel').textContent = 'Musik Latihan';
}

function minimizePlayer() {
    player.style.display = 'none';
    bubble.style.display = 'block';
    bubble.style.opacity = '0';
    bubble.style.transform = 'scale(0.5)';
    requestAnimationFrame(() => {
        bubble.style.transition = 'opacity .25s ease, transform .25s ease';
        bubble.style.opacity    = '1';
        bubble.style.transform  = 'scale(1)';
    });
    document.getElementById('musicBtnLabel').textContent = 'Musik Latihan';
}

function expandPlayer() {
    bubble.style.display = 'none';
    showPlayer();
}

function closePlayer() {
    hidePlayer();
    bubble.style.display = 'none';
}

// ── Kontrol audio ──
function togglePlay() {
    if (audio.paused) {
        audio.play().then(() => setPlayingUI(true)).catch(() => {});
    } else {
        audio.pause();
        setPlayingUI(false);
    }
}

function setPlayingUI(playing) {
    document.getElementById('iconPlay').classList.toggle('hidden', playing);
    document.getElementById('iconPause').classList.toggle('hidden', !playing);
    document.querySelectorAll('.wave-bar').forEach(b => {
        b.style.animationPlayState = playing ? 'running' : 'paused';
        b.style.opacity = playing ? '0.85' : '0.3';
    });
    // Dot indikator header
    const dot = document.getElementById('liveDot');
    if (dot) dot.style.opacity = playing ? '1' : '0.4';
}

function seekAudio(val) {
    if (!isNaN(audio.duration) && audio.duration > 0) {
        audio.currentTime = (val / 100) * audio.duration;
    }
}

function setVol(val) { audio.volume = val / 100; }

function toggleLoop() {
    isLooping  = !isLooping;
    audio.loop = isLooping;
    const icon = document.getElementById('loopIcon');
    icon.style.color   = isLooping ? '#F5A623' : 'rgba(255,255,255,.35)';
}

// Loop aktif by default — icon langsung oranye
document.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('loopIcon');
    if (icon) icon.style.color = '#F5A623';
});

// Update seek bar & waktu tiap 300ms
function fmtT(s) {
    return Math.floor(s/60) + ':' + String(Math.floor(s%60)).padStart(2,'0');
}
setInterval(() => {
    if (audio.duration && !isNaN(audio.duration)) {
        document.getElementById('seekBar').value =
            (audio.currentTime / audio.duration) * 100;
        document.getElementById('curTime').textContent = fmtT(audio.currentTime);
        document.getElementById('totTime').textContent = fmtT(audio.duration);
    }
}, 300);

audio.addEventListener('ended', () => { if (!isLooping) setPlayingUI(false); });
</script>

<style>
@keyframes waveAnim {
    from { transform: scaleY(0.3); }
    to   { transform: scaleY(1);   }
}
</style>

@endsection

@extends('layouts.verivied-client')

@section('title','Profil')

@section('content')

{{-- FLASH --}}
@if(session('ok'))
<div id="flash-msg"
     class="fixed top-20 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl
            bg-[#2E471F] text-white shadow-lg cm-fadein max-w-sm">
  <svg class="h-5 w-5 text-[#F5A623] flex-shrink-0" fill="none" viewBox="0 0 24 24"
       stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
  </svg>
  <span class="text-sm font-medium flex-1">{{ session('ok') }}</span>
  <button onclick="document.getElementById('flash-msg').remove()"
          class="text-white/50 hover:text-white transition-colors">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
  </button>
</div>
<script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
@endif

{{-- HEADER --}}
<div class="mb-6 cm-fadein">
  <h1 class="font-raleway text-2xl sm:text-3xl font-bold text-[#2E471F]">My Profile</h1>
  <p class="text-sm text-gray-500 mt-1">Kelola data pribadi dan pengaturan target nutrisi kamu</p>
</div>

<form method="POST" action="{{ route('profile.client.update') }}" enctype="multipart/form-data" class="space-y-6">
  @csrf

  {{-- AVATAR CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-1">
    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
      {{-- Avatar: klik untuk buka lightbox jika ada foto --}}
      @if($user->client && $user->client->photo_url)
        <button type="button" id="avatarBtn" onclick="openLightbox()"
                title="Lihat foto lebih besar"
                class="relative h-24 w-24 rounded-full flex-shrink-0 ring-4 ring-[#F5A623]/20
                       overflow-hidden group cursor-zoom-in focus:outline-none">
          <div id="avatarPreview" class="h-full w-full">
            <img src="{{ $user->client->photo_url }}" alt="{{ $user->username }}"
                 class="h-full w-full object-cover">
          </div>
          {{-- overlay hint --}}
          <div class="absolute inset-0 bg-black/40 flex items-center justify-center
                      opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6"/>
            </svg>
          </div>
        </button>
      @else
        <div id="avatarPreview"
             class="h-24 w-24 rounded-full bg-[#EFE6D2] flex items-center justify-center flex-shrink-0
                    ring-4 ring-[#F5A623]/20 overflow-hidden">
          <span class="text-3xl font-bold text-[#2E471F] font-raleway">
            {{ strtoupper(substr($user->username, 0, 2)) }}
          </span>
        </div>
      @endif

      <div class="flex-1">
        <p class="font-bold text-[#2E471F] text-lg">{{ $user->username }}</p>
        <p class="text-sm text-gray-400">Client &middot; {{ $user->email }}</p>

        <div class="flex items-center gap-2 mt-3">
          <label class="cursor-pointer">
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" capture="environment" class="hidden" id="photoInput">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2E471F] text-white text-xs font-semibold
                         hover:bg-[#3d6628] transition-colors shadow-sm">
              <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
              </svg>
              Ganti Foto
            </span>
          </label>
        </div>
        @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  {{-- PERSONAL INFO CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-2">
    <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-1">Informasi Pribadi</h2>
    <p class="text-xs text-gray-400 mb-5">Username tidak dapat diubah karena merupakan identitas akun.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Username</label>
        <input type="text" value="{{ $user->username }}" readonly
               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed">
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Berat Badan (kg)</label>
        <input type="number" name="bb" min="40" max="500" step="0.1"
               value="{{ old('bb', $user->client->bb ?? '') }}" required
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('bb') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('bb') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Tinggi Badan (cm)</label>
        <input type="number" name="tb" min="100" max="300" step="0.1"
               value="{{ old('tb', $user->client->tb ?? '') }}" required
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('tb') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('tb') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Umur</label>
        <input type="number" name="umur" min="17" max="120"
               value="{{ old('umur', $user->client->umur ?? '') }}" required
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('umur') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('umur') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

    </div>
  </div>

  {{-- PENGINGAT CARD --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-2">
    <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-1">Pengaturan Pengingat</h2>
    <p class="text-xs text-gray-400 mb-5">Atur jam kapan kamu ingin diingatkan untuk mencatat makanan atau berolahraga.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Pengingat Input Makanan</label>
        <input type="time" name="food_reminder_time"
               value="{{ old('food_reminder_time', $user->client->food_reminder_time ? \Carbon\Carbon::parse($user->client->food_reminder_time)->format('H:i') : '') }}"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('food_reminder_time') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('food_reminder_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Pengingat Jadwal Olahraga</label>
        <input type="time" name="exercise_reminder_time"
               value="{{ old('exercise_reminder_time', $user->client->exercise_reminder_time ? \Carbon\Carbon::parse($user->client->exercise_reminder_time)->format('H:i') : '') }}"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('exercise_reminder_time') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                      focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
        @error('exercise_reminder_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>
    </div>
  </div>

  {{-- SETTING — TARGET KALORI & MAKRO --}}
  <div class="bg-white rounded-2xl shadow-sm p-6 cm-fadein cm-delay-3">
    <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-1">Setting Target Nutrisi</h2>
    <p class="text-xs text-gray-400 mb-5">Atur target kalori harian dan distribusi makro sesuai kebutuhan kamu.</p>

    @error('macro')
      <div class="mb-4 flex items-start gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200">
        <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
        </svg>
        <p class="text-sm text-red-600">{{ $message }}</p>
      </div>
    @enderror

    {{-- Preset --}}
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Preset Cepat</p>
    <div class="flex flex-wrap gap-2 mb-5">
      <button type="button" onclick="applyPreset(30,40,30)"
              class="px-3 py-1.5 text-xs font-semibold rounded-full border border-[#2E471F]/30 text-[#2E471F] hover:bg-[#2E471F] hover:text-white transition">
        Seimbang 30/40/30
      </button>
      <button type="button" onclick="applyPreset(40,35,25)"
              class="px-3 py-1.5 text-xs font-semibold rounded-full border border-blue-300 text-blue-600 hover:bg-blue-600 hover:text-white transition">
        Tinggi Protein 40/35/25
      </button>
      <button type="button" onclick="applyPreset(30,20,50)"
              class="px-3 py-1.5 text-xs font-semibold rounded-full border border-orange-300 text-orange-500 hover:bg-orange-500 hover:text-white transition">
        Rendah Karbo 30/20/50
      </button>
    </div>

    {{-- Calorie Target --}}
    <div class="mb-5">
      <label class="block text-sm font-semibold text-[#2E471F] mb-1.5">Target Kalori Harian (kkal)</label>
      <p class="text-xs text-gray-400 mb-2">Kosongkan untuk pakai kalkulasi otomatis dari data tubuh.</p>
      <input type="number" name="calorie_target" id="calorie_target"
             min="500" max="10000" placeholder="Otomatis"
             value="{{ old('calorie_target', $user->client->calorie_target ?? '') }}"
             class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('calorie_target') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-gray-700 text-sm
                    focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
      @error('calorie_target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Makro --}}
    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="block text-xs font-semibold mb-1.5 text-blue-600">Protein (%)</label>
        <input type="number" name="protein_ratio" id="protein_ratio"
               value="{{ old('protein_ratio', $user->client->protein_ratio ?? 30) }}"
               min="10" max="70" oninput="updateTotal()"
               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-center font-bold text-blue-600
                      focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-150">
      </div>
      <div>
        <label class="block text-xs font-semibold mb-1.5 text-green-600">Karbo (%)</label>
        <input type="number" name="carbo_ratio" id="carbo_ratio"
               value="{{ old('carbo_ratio', $user->client->carbo_ratio ?? 40) }}"
               min="10" max="70" oninput="updateTotal()"
               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-center font-bold text-green-600
                      focus:border-green-400 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-150">
      </div>
      <div>
        <label class="block text-xs font-semibold mb-1.5 text-orange-500">Lemak (%)</label>
        <input type="number" name="fat_ratio" id="fat_ratio"
               value="{{ old('fat_ratio', $user->client->fat_ratio ?? 30) }}"
               min="10" max="70" oninput="updateTotal()"
               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-center font-bold text-orange-500
                      focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none transition-all duration-150">
      </div>
    </div>

    {{-- Total Indicator --}}
    <div class="mt-3 flex items-center gap-2 text-sm">
      <span class="text-gray-500">Total:</span>
      <span id="macro_total" class="font-bold">100%</span>
      <span id="macro_status" class="text-green-600 text-xs"></span>
    </div>

    {{-- Preview gram --}}
    <div class="mt-4 grid grid-cols-3 gap-2 text-center bg-[#EFE6D2]/40 rounded-xl p-3">
      <div>
        <span class="block font-bold text-blue-600 text-lg" id="prev_protein">-</span>
        <span class="text-xs text-gray-400">g Protein</span>
      </div>
      <div>
        <span class="block font-bold text-green-600 text-lg" id="prev_carbo">-</span>
        <span class="text-xs text-gray-400">g Karbo</span>
      </div>
      <div>
        <span class="block font-bold text-orange-500 text-lg" id="prev_fat">-</span>
        <span class="text-xs text-gray-400">g Lemak</span>
      </div>
    </div>
  </div>

  {{-- ACTIONS --}}
  <div class="flex justify-end gap-3 cm-fadein cm-delay-4">
    <a href="{{ route('client.home') }}"
       class="px-6 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
              hover:bg-gray-50 transition-colors duration-150">
      Cancel
    </a>
    <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                   shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
      Save Changes
    </button>
  </div>

</form>

{{-- LIGHTBOX MODAL --}}
@if($user->client && $user->client->photo_url)
<div id="photoLightbox"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4
            bg-black/80 backdrop-blur-sm
            opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="if(event.target===this) closeLightbox()">

  <div id="lightboxInner"
       class="relative max-w-lg w-full max-h-[90vh]
              scale-90 transition-transform duration-300">

    {{-- Tombol tutup --}}
    <button onclick="closeLightbox()"
            class="absolute -top-3 -right-3 z-10 h-8 w-8 rounded-full
                   bg-white shadow-lg flex items-center justify-center
                   hover:bg-gray-100 transition-colors"
            title="Tutup">
      <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
      </svg>
    </button>

    {{-- Foto besar --}}
    <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
      <img id="lightboxImg"
           src="{{ $user->client->photo_url }}"
           alt="Foto {{ $user->username }}"
           class="w-full h-auto max-h-[85vh] object-contain bg-black">
    </div>

    {{-- Caption --}}
    <div class="mt-3 text-center">
      <p class="text-white font-semibold text-sm">{{ $user->username }}</p>
      <p class="text-white/60 text-xs">Foto Profil</p>
    </div>
  </div>
</div>
@endif

<script>
function applyPreset(p, c, f) {
  document.getElementById('protein_ratio').value = p;
  document.getElementById('carbo_ratio').value   = c;
  document.getElementById('fat_ratio').value     = f;
  updateTotal();
}

function updateTotal() {
  const p = parseInt(document.getElementById('protein_ratio').value) || 0;
  const c = parseInt(document.getElementById('carbo_ratio').value)   || 0;
  const f = parseInt(document.getElementById('fat_ratio').value)     || 0;
  const total = p + c + f;

  const totalEl  = document.getElementById('macro_total');
  const statusEl = document.getElementById('macro_status');
  totalEl.textContent = total + '%';

  if (total === 100) {
    totalEl.className   = 'font-bold text-green-600';
    statusEl.textContent = '✓ Valid';
    statusEl.className   = 'text-green-600 text-xs';
  } else {
    totalEl.className   = 'font-bold text-red-600';
    statusEl.textContent = total < 100 ? `Kurang ${100 - total}%` : `Lebih ${total - 100}%`;
    statusEl.className   = 'text-red-600 text-xs';
  }

  const calInput = parseFloat(document.getElementById('calorie_target').value);
  const cal = calInput > 0 ? calInput : {{ $user->client && $user->client->tb && $user->client->bb ? round($user->client->getEffectiveCalorieTarget()) : 2000 }};
  document.getElementById('prev_protein').textContent = (cal * (p/100) / 4).toFixed(1);
  document.getElementById('prev_carbo').textContent   = (cal * (c/100) / 4).toFixed(1);
  document.getElementById('prev_fat').textContent     = (cal * (f/100) / 9).toFixed(1);
}

document.getElementById('calorie_target')?.addEventListener('input', updateTotal);
document.addEventListener('DOMContentLoaded', updateTotal);

// Live preview foto
document.getElementById('photoInput')?.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (evt) => {
    const src = evt.target.result;
    document.getElementById('avatarPreview').innerHTML =
      `<img src="${src}" alt="Preview" class="h-full w-full object-cover">`;
    // Sync ke lightbox jika ada
    const lbImg = document.getElementById('lightboxImg');
    if (lbImg) lbImg.src = src;
  };
  reader.readAsDataURL(file);
});

// Lightbox
function openLightbox() {
  const lb = document.getElementById('photoLightbox');
  const inner = document.getElementById('lightboxInner');
  if (!lb) return;
  lb.classList.remove('opacity-0', 'pointer-events-none');
  lb.classList.add('opacity-100');
  inner.classList.remove('scale-90');
  inner.classList.add('scale-100');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  const lb = document.getElementById('photoLightbox');
  const inner = document.getElementById('lightboxInner');
  if (!lb) return;
  lb.classList.remove('opacity-100');
  lb.classList.add('opacity-0');
  inner.classList.remove('scale-100');
  inner.classList.add('scale-90');
  document.body.style.overflow = '';
  setTimeout(() => lb.classList.add('pointer-events-none'), 300);
}

// Tutup lightbox dengan tombol Escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeLightbox();
});
</script>

@endsection

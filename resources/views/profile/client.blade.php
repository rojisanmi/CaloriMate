@extends('layouts.verivied-client')

@section('title','Profil Client')

@section('content')

@if (session('ok'))
  <div id="success-notification" class="mb-6 rounded-lg bg-green-500 text-white px-4 py-3 animate-slide-down">
    {{ session('ok') }}
  </div>
@endif

<form method="POST"
      action="{{ route('profile.client.update') }}"
      enctype="multipart/form-data">

@csrf

<div class="max-w-4xl mx-auto p-6 lg:p-8 mt-8">
  <div class="bg-white p-6 rounded-xl shadow-lg animate-scale-in">
    <div class="flex flex-col lg:flex-row lg:items-start gap-8">

      {{-- LEFT: Avatar --}}
      <div class="lg:w-1/3 flex flex-col items-center animate-fade-in-left">
        @php
          $avatar = $user->client->avatar ?? null;
        @endphp

        @if($avatar)
          <img src="{{ asset('storage/'.$avatar) }}"
               class="w-48 h-48 rounded-lg object-cover border shadow-sm hover:scale-105 transition-transform duration-300">
        @else
          <div class="w-48 h-60 flex items-center justify-center rounded-lg border bg-gray-50 text-gray-400 hover:bg-gray-100 transition-colors duration-300">
            No Avatar
          </div>
        @endif
      </div>

      {{-- RIGHT: FORM --}}
      <div class="lg:w-2/3 w-full space-y-4 animate-fade-in-right">

        {{-- Username --}}
        <div class="animate-slide-up" style="animation-delay: 0.1s">
          <label class="font-semibold text-[#2E471F]">Username</label>
          <input type="text"
                 value="{{ $user->username }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/20">
        </div>

        {{-- Email --}}
        <div class="animate-slide-up" style="animation-delay: 0.2s">
          <label class="font-semibold text-[#2E471F]">Email</label>
          <input type="email"
                 value="{{ $user->email }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/20">
        </div>

        {{-- Role --}}
        <div class="animate-slide-up" style="animation-delay: 0.3s">
          <label class="font-semibold text-[#2E471F]">Role</label>
          <input type="text"
                 value="Client"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/20">
        </div>

        {{-- Gender --}}
        <div class="animate-slide-up" style="animation-delay: 0.4s">
          <label class="font-semibold text-[#2E471F]">Gender</label>
          <input type="text"
                 value="{{ $user->client->gender ?? '-' }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/20">
        </div>

        {{-- Umur --}}
        <div class="animate-slide-up" style="animation-delay: 0.5s">
          <label class="font-semibold text-[#2E471F]">Umur</label>
          <input type="number" name="umur" min="1"
                 value="{{ old('umur', $user->client->umur ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/50 focus:border-[#2E471F]">
        </div>

        {{-- Berat & Tinggi Badan --}}
        <div class="grid grid-cols-2 gap-4 animate-slide-up" style="animation-delay: 0.6s">
          <div>
            <label class="font-semibold text-[#2E471F]">Berat Badan (kg)</label>
            <input type="number" name="bb"
                   value="{{ old('bb', $user->client->bb ?? '') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-md transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/50 focus:border-[#2E471F]">
          </div>

          <div>
            <label class="font-semibold text-[#2E471F]">Tinggi Badan (cm)</label>
            <input type="number" name="tb"
                   value="{{ old('tb', $user->client->tb ?? '') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-md transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/50 focus:border-[#2E471F]">
          </div>
        </div>

        {{-- Save --}}
        <div class="pt-6 flex justify-end animate-slide-up" style="animation-delay: 0.7s">
          <button type="submit"
                  class="px-6 py-2 bg-[#2E7D32] text-white font-semibold rounded-md hover:opacity-90 hover:scale-105 transition-all duration-200 active:scale-95">
            Simpan Perubahan
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.querySelector('img')?.setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

// Auto hide success notification
document.addEventListener('DOMContentLoaded', function() {
  const notification = document.getElementById('success-notification');
  if (notification) {
    setTimeout(() => {
      notification.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      notification.style.opacity = '0';
      notification.style.transform = 'translateY(-20px)';
      setTimeout(() => notification.remove(), 500);
    }, 3000);
  }
});
</script>

<style>
/* Animation Keyframes */
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

@keyframes fadeInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Animation Classes */
.animate-scale-in {
  animation: scaleIn 0.5s ease-out;
}

.animate-fade-in-left {
  animation: fadeInLeft 0.6s ease-out;
}

.animate-fade-in-right {
  animation: fadeInRight 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.5s ease-out both;
}

.animate-slide-down {
  animation: slideDown 0.5s ease-out;
}
</style>

</form>
@endsection
@extends('layouts.verivied')

@section('title', 'Profil Trainer')

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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
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

    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out;
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

    .animate-delay-4 {
        animation-delay: 0.4s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
</style>

@if (session('ok'))
  <div id="successAlert"
       class="mb-6 rounded-lg bg-green-500 text-white px-4 py-3 animate-fade-in-down">
    {{ session('ok') }}
  </div>
@endif

<form method="POST"
      action="{{ route('profile.trainer.update') }}"
      enctype="multipart/form-data">

@csrf

<div class="max-w-4xl mx-auto p-6 lg:p-8 mt-8">
  <div class="bg-white p-6 rounded-xl shadow-lg animate-scale-in">
    <div class="flex flex-col lg:flex-row lg:items-start gap-8">

      {{-- LEFT: Avatar --}}
      <div class="lg:w-1/3 flex flex-col items-center animate-slide-in-left animate-delay-1">
        @php
          $avatar = $user->trainer->avatar ?? null;
          $avatarUrl = $avatar ? asset($avatar) : null;
        @endphp

        @if($avatarUrl)
          <img src="{{ $avatarUrl }}"
               class="w-48 h-48 rounded-lg object-cover border shadow-sm">
        @else
          <div class="w-48 h-48 flex items-center justify-center rounded-lg border bg-gray-50 text-gray-400">
            No Avatar
          </div>
        @endif
      </div>

      {{-- RIGHT: FORM --}}
      <div class="lg:w-2/3 w-full space-y-4 animate-slide-in-right animate-delay-2">

        {{-- Nama --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Nama</label>
          <input type="text"
                 name="nama"
                 value="{{ old('nama', $user->trainer->nama ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md transition-all duration-200 focus:ring-2 focus:ring-[#2E4F2A] focus:border-transparent">
        </div>

        {{-- Username --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Username</label>
          <input type="text"
                 value="{{ $user->username }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Email --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Email</label>
          <input type="email"
                 value="{{ $user->email }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Role --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Role</label>
          <input type="text"
                 value="{{ $user->role_label ?? $user->role }}"
                 readonly
                 class="w-full mt-1 px-3 py-2 border rounded-md bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Keahlian --}}
        <div>
          <label class="font-semibold text-[#2E4F2A]">Keahlian</label>
          <input type="text"
                 name="keahlian"
                 value="{{ old('keahlian', $user->trainer->keahlian ?? '') }}"
                 class="w-full mt-1 px-3 py-2 border rounded-md transition-all duration-200 focus:ring-2 focus:ring-[#2E4F2A] focus:border-transparent">
        </div>

        {{-- Sertifikasi --}}
        <div class="mt-4">
            <label class="font-semibold text-[#2E4F2A] block mb-2">Sertifikasi</label>

            {{-- CARD PREVIEW --}}
            <div class="w-full h-56 flex justify-center items-center 
            border border-gray-300 rounded-md bg-gray-50 mb-3 overflow-hidden">
                @if(!empty($user->trainer->sertifikasi))
                    <img id="previewImage"
                      src="{{ asset('storage/' . $user->trainer->sertifikasi) }}"
                      class="h-full w-full object-contain object-center rounded">
                @else
                    <img id="previewImage"
                        class="max-h-48 mx-auto object-contain rounded shadow mb-3 hidden">
                @endif
            </div>

            {{-- CUSTOM FILE INPUT --}}
            <label class="flex flex-col items-center px-4 py-3 bg-white rounded-md 
               shadow cursor-pointer border border-gray-300 hover:bg-gray-100 transition-all duration-200 hover:shadow-md">
                <span class="text-sm text-gray-600">Klik untuk memilih file sertifikasi</span>
                <input type="file"
                      name="sertifikasi"
                      class="hidden"
                      id="sertifikasiInput">
            </label>
        </div>

        {{-- Save --}}
        <div class="pt-6 flex justify-end animate-fade-in-up animate-delay-3">
          <button type="submit"
                  class="px-6 py-2 bg-[#2E7D32] text-white font-semibold rounded-md 
                  transition-all duration-200 hover:bg-[#1B5E20] hover:shadow-lg hover:-translate-y-1 
                  active:scale-95">
            Simpan Perubahan
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Success alert
    const alert = document.getElementById('successAlert');

    if (alert) {
        setTimeout(() => {
            alert.classList.add(
                'transition-all',
                'duration-500',
                'opacity-0',
                '-translate-y-2'
            );

            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 3000); // 3 detik
    }

   // Preview Gambar Sertifikasi
    const input = document.getElementById('sertifikasiInput');
    const preview = document.getElementById('previewImage');

    if (input) {
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

});
</script>
</form>
@endsection
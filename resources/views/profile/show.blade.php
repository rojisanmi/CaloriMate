@php
    if ($user->isClient()) {
        $layout = 'layouts.verivied-client';
    } else {
        $layout = 'layouts.verivied';
    }
@endphp
@extends($layout)
@section('title', 'Profil Trainer')

@section('content')
  <div class="max-w-4xl mx-auto p-6 lg:p-8 mt-8">
    <div class="bg-white p-6 rounded-xl shadow-lg">
      <div class="flex flex-col lg:flex-row lg:items-start gap-8">
        {{-- LEFT: Avatar Section --}}
        <div class="lg:w-1/3 flex justify-center">
          @php
            $avatar = $user->trainer->avatar ?? null;
            $avatarUrl = null;
            if (!empty($avatar)) {
              if (str_starts_with($avatar, 'data:') || str_starts_with($avatar, 'http')) {
                $avatarUrl = $avatar;
              } else {
                $avatarUrl = asset($avatar);
              }
            }
          @endphp

          @if($avatarUrl)
            <img 
              src="{{ $avatarUrl }}" 
              alt="Avatar {{ $user->username }}" 
              class="w-48 h-48 rounded-lg object-cover border border-gray-200 shadow-sm"
            >
          @else
            <div class="w-48 h-48 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-400">
              <span class="text-center">No<br>Avatar</span>
            </div>
          @endif

          <div class="mt-4 text-center lg:hidden">
            <p class="font-bold text-lg text-[#2E4F2A]">
              {{ $user->trainer->nama ?? $user->name ?? $user->username }}
            </p>
            <p class="text-sm text-gray-500">@ {{ $user->username ?? 'username' }}</p>
          </div>
        </div>

        {{-- RIGHT: Info Profil --}}
        <div class="lg:w-2/3 w-full">
          <div class="flex justify-between items-start mb-6">
            <div>
              <h1 class="text-2xl font-bold text-[#2E4F2A]">Profil</h1>
              <p class="text-sm text-gray-600 mt-1">Informasi akun dan sertifikasi trainer</p>
            </div>
          </div>

          <div class="space-y-4">
            <div class="grid grid-cols-3 gap-4">
              <label class="font-semibold text-[#2E4F2A]">Nama</label>
              <div class="col-span-2">
                <input type="text" value="{{ $user->trainer->nama ?? '' }}" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-[#2E4F2A] cursor-not-allowed">
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <label class="font-semibold text-[#2E4F2A]">Username</label>
              <div class="col-span-2">
                <input type="text" value="{{ $user->username ?? '' }}" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-[#2E4F2A] cursor-not-allowed">
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <label class="font-semibold text-[#2E4F2A]">Email</label>
              <div class="col-span-2">
                <input type="email" value="{{ $user->email ?? '' }}" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-[#2E4F2A] cursor-not-allowed">
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <label class="font-semibold text-[#2E4F2A]">Role</label>
              <div class="col-span-2">
                <input type="text" value="{{ $user->role_label ?? ($user->role ?? '') }}" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-[#2E4F2A] cursor-not-allowed">
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <label class="font-semibold text-[#2E4F2A]">Keahlian</label>
              <div class="col-span-2">
                <input type="text" value="{{ $user->trainer->keahlian ?? '-' }}" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-[#2E4F2A] cursor-not-allowed">
              </div>
            </div>

            {{-- Sertifikasi --}}
            {{-- Sertifikasi --}}
                <div class="mt-6">
                <label class="font-semibold text-[#2E4F2A] block mb-2">Sertifikasi</label>

                @php
                    $sert = $user->trainer->sertifikasi ?? null;
                    $sertUrl = null;

                    if ($sert) {
                        if (is_string($sert) && (str_starts_with($sert, 'http') || str_starts_with($sert, 'data:'))) {
                            // Jika sudah berupa URL atau base64, langsung pakai
                            $sertUrl = $sert;
                        } else {
                            // Jika berupa binary blob → convert jadi data:image/... base64
                            try {
                                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                                $mime = $finfo->buffer($sert) ?: 'image/jpeg';
                            } catch (\Throwable $e) {
                                $mime = 'image/jpeg';
                            }
                            $sertUrl = 'data:' . $mime . ';base64,' . base64_encode($sert);
                        }
                    }
                @endphp

                @if($sertUrl)
                    <div class="inline-block">
                        <img
                            src="{{ $sertUrl }}"
                            alt="Sertifikasi"
                            class="block max-w-full h-auto object-contain"
                        >
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Tidak ada sertifikasi yang diunggah.</div>
                @endif

                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
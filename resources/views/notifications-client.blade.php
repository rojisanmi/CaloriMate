@extends('layouts.verivied-client')

@section('title', 'Notifikasi')

@section('content')

<div class="mb-6 cm-fadein flex justify-between items-end">
  <div>
    <h1 class="font-raleway text-2xl sm:text-3xl font-bold text-[#2E471F]">Notifikasi Anda</h1>
    <p class="text-sm text-gray-500 mt-1">Pengingat, peringatan, dan ringkasan aktivitas harian Anda</p>
  </div>
  @if(count($notifications) > 0 && collect($notifications)->where('is_read', false)->count() > 0)
    <form action="{{ route('client.notifications.read') }}" method="POST">
      @csrf
      <button type="submit" class="text-xs font-semibold text-[#F5A623] hover:underline px-3 py-1.5 bg-orange-50 rounded-lg">Tandai semua dibaca</button>
    </form>
  @endif
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden cm-fadein cm-delay-1">
  @if(count($notifications) > 0)
    <ul class="divide-y divide-gray-100">
      @foreach($notifications as $notif)
        <li class="p-5 flex gap-4 hover:bg-gray-50 transition-colors {{ $notif['is_read'] ? 'opacity-70' : '' }}">
          <div class="flex-shrink-0 mt-1">
            @if($notif['icon'] === 'food')
              <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m-6-6h12" />
                </svg>
              </div>
            @elseif($notif['icon'] === 'warning')
              <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
            @elseif($notif['icon'] === 'exercise')
              <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            @else
              <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            @endif
          </div>
          
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2 mb-1">
              <h3 class="text-sm font-bold text-[#2E471F] {{ !$notif['is_read'] ? 'font-extrabold' : '' }}">{{ $notif['title'] }}</h3>
              <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notif['time'] }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $notif['message'] }}</p>
          </div>
          
          @if(!$notif['is_read'])
            <div class="flex-shrink-0 flex items-center gap-2">
              <div class="h-2.5 w-2.5 rounded-full bg-[#F5A623]"></div>
              <form action="{{ route('client.notifications.read') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $notif['id'] }}">
                <button type="submit" class="text-[10px] text-gray-400 hover:text-[#F5A623] hover:underline">Tandai dibaca</button>
              </form>
            </div>
          @endif
        </li>
      @endforeach
    </ul>
  @else
    <div class="flex flex-col items-center justify-center py-20 text-center">
      <div class="h-16 w-16 rounded-2xl bg-[#EFE6D2] flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-[#2E471F]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
      </div>
      <p class="text-gray-400 text-sm">Belum ada notifikasi baru.</p>
    </div>
  @endif
</div>

@endsection

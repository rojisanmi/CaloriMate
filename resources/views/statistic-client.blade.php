@extends('layouts.verified-client')

@section('title','Statistic')

@section('content')
<section class="max-w-5xl mx-auto">

    <div class="bg-[#EFE6D2] rounded-xl p-8 shadow">

        {{-- DETAIL STATISTIK --}}
        <h2 class="text-center font-bold text-[#2E471F] mb-4">
            Detail Statistik
        </h2>

        <div class="bg-white rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

                <div>
                    <p class="text-sm text-gray-500">Kalori Masuk</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['kalori_masuk'] }} kkal
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kalori Keluar</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['kalori_keluar'] }} kkal
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Selisih</p>
                    <p class="text-xl font-bold text-[#2E471F]">
                        {{ $statistik['selisih'] }} kkal
                    </p>
                </div>

            </div>
        </div>

        {{-- RIWAYAT AKTIVITAS --}}
        <h3 class="font-semibold text-[#2E471F] mb-2">
            Riwayat Aktivitas
        </h3>

        <div class="bg-white rounded-lg overflow-hidden border">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-[#2E471F]">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Aktivitas</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-right">Kalori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item['tanggal'] }}</td>
                        <td class="px-4 py-2">{{ $item['nama'] }}</td>
                        <td class="px-4 py-2">{{ $item['waktu'] }}</td>
                        <td class="px-4 py-2 text-right">
                            {{ $item['kalori'] }} kkal
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Tidak ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</section>
@endsection

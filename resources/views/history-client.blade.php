@extends('layouts.verivied-client')

@section('title','History')

@section('content')
<section class="max-w-4xl mx-auto">

    {{-- FILTER --}}
    <form method="GET" action="{{ route('client.history') }}">
        <div class="bg-[#EFE6D2] rounded-xl p-6 shadow">

            <select name="period"
                    onchange="this.form.submit()"
                    class="w-full px-4 py-3 rounded-lg bg-white border
                           text-[#2E471F] font-semibold focus:outline-none">

                <option value="1_day"   {{ $period=='1_day'?'selected':'' }}>
                    1 Hari Terakhir
                </option>
                <option value="7_days"  {{ $period=='7_days'?'selected':'' }}>
                    7 Hari Terakhir
                </option>
                <option value="1_month" {{ $period=='1_month'?'selected':'' }}>
                    1 Bulan Terakhir
                </option>
            </select>

            {{-- RESULT --}}
            <div class="mt-6 space-y-4">

                @forelse($histories as $history)
                <div class="bg-white rounded-lg px-5 py-4 shadow-sm flex justify-between">
                    <div>
                        <p class="font-semibold text-[#2E471F]">
                            {{ $history['name'] }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($history['date'])->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="text-[#2E471F] font-bold">
                        {{ $history['calories'] }} kkal
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-6">
                    Tidak ada data pada periode ini
                </p>
                @endforelse

            </div>
        </div>
    </form>

</section>
@endsection

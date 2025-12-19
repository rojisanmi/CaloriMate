@extends('layouts.verivied-client')

@section('title','Tambah Makanan')

@section('content')
<section class="max-w-lg mx-auto">

    {{-- FORM TAMBAH MAKANAN --}}
    <div class="bg-[#FFFFFF] rounded-xl p-6 shadow">
        <h2 class="text-xl font-bold text-[#2E471F] mb-4">
            Tambah Makanan -
            @switch($category)
                @case('breakfast') Makan Pagi @break
                @case('lunch') Makan Siang @break
                @case('dinner') Makan Malam @break
                @case('snack') Camilan @break
            @endswitch
        </h2>

        <form method="POST" action="{{ route('client.diary.store') }}">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">

            <div class="mb-4">
                <label class="block text-[#2E471F] font-semibold mb-2">
                    Pilih Makanan
                </label>
                <select name="food_id" required
                    class="w-full px-4 py-3 rounded-lg bg-white border text-[#2E471F]">
                    <option value="">-- Pilih Makanan --</option>
                    @foreach($foods as $food)
                        <option value="{{ $food->food_id }}">
                            {{ $food->name }} ({{ $food->calories_per_portion }} kkal/porsi)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-[#2E471F] font-semibold mb-2">
                    Jumlah Porsi
                </label>
                <input type="number" name="portions" value="1" min="1" required
                    class="w-full px-4 py-3 rounded-lg bg-white border text-[#2E471F]">
            </div>

            <div class="flex gap-3">
                <a href="{{ route('client.diary') }}"
                   class="flex-1 text-center py-3 rounded-lg border border-[#2E471F]
                          text-[#2E471F] font-semibold">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-lg bg-[#2E471F] text-white font-semibold">
                    Tambah
                </button>
            </div>
        </form>
    </div>

    {{-- RIWAYAT MAKANAN --}}
    @if(count($consumptions) > 0)
        <div class="bg-white rounded-xl p-6 shadow mt-6">
            <h3 class="text-lg font-bold text-[#2E471F] mb-4">
                Riwayat Makanan Hari Ini
            </h3>

            <ul class="space-y-3">
                @foreach($consumptions as $item)
                    <li class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold text-[#2E471F]">
                                {{ $item->food->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $item->portions }} porsi ·
                                {{ $item->food->calories_per_portion * $item->portions }} kkal
                            </p>
                        </div>

                        <form method="POST"
                              action="{{ route('client.diary.remove') }}"
                              onsubmit="return confirm('Yakin ingin menghapus makanan ini?')">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="food_id" value="{{ $item->food_id }}">
                            <input type="hidden" name="category" value="{{ $category }}">

                            <button type="submit"
                                class="text-red-600 font-semibold text-sm">
                                Hapus
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</section>
@endsection

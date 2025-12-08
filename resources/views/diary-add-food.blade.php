@extends('layouts.verivied-client')

@section('title','Tambah Makanan')

@section('content')
<section class="max-w-lg mx-auto">
    <div class="bg-[#EFE6D2] rounded-xl p-6 shadow">
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
                <label class="block text-[#2E471F] font-semibold mb-2">Pilih Makanan</label>
                <select name="food_id" required
                    class="w-full px-4 py-3 rounded-lg bg-white border text-[#2E471F] focus:outline-none">
                    <option value="">-- Pilih Makanan --</option>
                    @foreach($foods as $food)
                        <option value="{{ $food->food_id }}">
                            {{ $food->name }} ({{ $food->calories_per_portion }} kkal/porsi)
                        </option>
                    @endforeach
                </select>
                @error('food_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-[#2E471F] font-semibold mb-2">Jumlah Porsi</label>
                <input type="number" name="portions" value="1" min="1" required
                    class="w-full px-4 py-3 rounded-lg bg-white border text-[#2E471F] focus:outline-none">
                @error('portions')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('client.diary') }}"
                   class="flex-1 text-center py-3 rounded-lg border border-[#2E471F] text-[#2E471F] font-semibold hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-lg bg-[#2E471F] text-white font-semibold hover:opacity-90">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

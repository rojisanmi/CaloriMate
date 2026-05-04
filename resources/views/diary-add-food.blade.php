@extends('layouts.verivied-client')

@section('title','Tambah Makanan')

@section('content')
<section class="max-w-lg mx-auto">

    {{-- FLASH MESSAGES --}}
    @if (session('ok'))
      <div id="flash-ok"
           class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm mb-4">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('ok') }}
      </div>
      <script>setTimeout(() => { const el = document.getElementById('flash-ok'); if(el){el.style.transition='opacity .4s';el.style.opacity='0';setTimeout(()=>el.remove(),400);} }, 3000);</script>
    @endif

    @if ($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
        <p class="font-semibold mb-1">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

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
                    class="w-full px-4 py-3 rounded-lg bg-white border {{ $errors->has('food_id') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-[#2E471F]">
                    <option value="">-- Pilih Makanan --</option>
                    @foreach($foods as $food)
                        <option value="{{ $food->food_id }}">
                            {{ $food->name }} ({{ $food->calories_per_portion }} kkal/porsi)
                        </option>
                    @endforeach
                </select>
                @error('food_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-[#2E471F] font-semibold mb-2">
                    Jumlah Porsi
                </label>
                <input type="number" name="portions" value="{{ old('portions', 1) }}" min="1" max="50" required
                    class="w-full px-4 py-3 rounded-lg bg-white border {{ $errors->has('portions') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-[#2E471F]">
                @error('portions') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
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
                              id="del-food-{{ $item->food_id }}-{{ $category }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="food_id" value="{{ $item->food_id }}">
                            <input type="hidden" name="category" value="{{ $category }}">
                            <button type="button"
                                    onclick="showDeleteModal(document.getElementById('del-food-{{ $item->food_id }}-{{ $category }}'), 'Hapus {{ addslashes($item->food->name ?? 'makanan ini') }} dari diary?')"
                                    class="text-red-600 font-semibold text-sm hover:text-red-800 transition">
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

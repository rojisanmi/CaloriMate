@extends('layouts.verivied')

@section('title', 'Buat Program Latihan')

@section('content')

<div class="max-w-3xl mx-auto cm-fadein">

  {{-- HEADER --}}
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ route('trainer.programs.index') }}"
       class="h-9 w-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center
              text-gray-500 hover:text-[#2E471F] hover:border-[#2E471F] transition-all duration-150">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </a>
    <div>
      <h1 class="font-raleway text-xl font-bold text-[#2E471F]">Buat Program Latihan</h1>
      <p class="text-xs text-gray-400">Buat program baru beserta item latihannya</p>
    </div>
  </div>

  {{-- ERROR SUMMARY --}}
  @if($errors->any())
    <div class="mb-4 flex items-start gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200">
      <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
      </svg>
      <ul class="text-red-600 text-sm list-disc list-inside">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm p-6">
    <form id="program-form" method="POST" action="{{ route('trainer.programs.store') }}" class="space-y-6">
      @csrf

      {{-- PROGRAM INFO --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Program</label>
          <input name="name" value="{{ old('name') }}" required placeholder="mis. Full Body Workout"
                 class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 text-sm
                        focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
          @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe</label>
          <input name="type" value="{{ old('type') }}" placeholder="mis. HIIT, Strength, Cardio"
                 class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 text-sm
                        focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
          @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tingkat Kesulitan</label>
          <select name="difficulty"
                  class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 text-sm bg-white
                         focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
            <option value="">Pilih tingkat</option>
            @foreach(['Beginner','Intermediate','Advanced'] as $opt)
              <option value="{{ $opt }}" @selected(old('difficulty') === $opt)>{{ $opt }}</option>
            @endforeach
          </select>
          @error('difficulty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- ITEM LATIHAN --}}
      <div class="border-t border-gray-100 pt-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-[#2E471F]">Item Latihan</h2>
          <button type="button" id="add-row"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#EFE6D2] text-[#2E471F]
                         text-sm font-semibold hover:bg-[#F5A623]/20 transition-colors duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah Item
          </button>
        </div>

        <div id="items" class="space-y-3"></div>

        <template id="item-template">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Latihan</label>
              <input name="__NAME__[exercise_name]" required placeholder="mis. Push Up"
                     class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800
                            focus:border-[#2E471F] focus:outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-600 mb-1">Durasi (menit)</label>
              <input type="number" min="1" name="__NAME__[duration_minutes]" placeholder="mis. 10"
                     class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800
                            focus:border-[#2E471F] focus:outline-none">
            </div>
            <div class="flex gap-2">
              <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Intensitas</label>
                <select name="__NAME__[intensity_level]"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 bg-white
                               focus:border-[#2E471F] focus:outline-none">
                  <option value="">Pilih</option>
                  <option value="Rendah">Rendah</option>
                  <option value="Sedang">Sedang</option>
                  <option value="Tinggi">Tinggi</option>
                </select>
              </div>
              <button type="button"
                      class="remove-row self-end px-3 py-2 rounded-lg border border-red-200 text-red-500
                             bg-white hover:bg-red-50 text-sm transition-colors duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
        </template>
      </div>

      {{-- ACTIONS --}}
      <div class="flex gap-3 pt-2">
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                       shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
          Simpan Program
        </button>
        <a href="{{ route('trainer.programs.index') }}"
           class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
                  hover:bg-gray-50 transition-colors duration-150">
          Batal
        </a>
      </div>

    </form>
  </div>

</div>

<script>
(function () {
  let idx = 0;
  const items = document.getElementById('items');
  const tpl   = document.getElementById('item-template').innerHTML;

  function addRow() {
    const html    = tpl.replaceAll('__NAME__', `items[${idx++}]`);
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html.trim();
    const node = wrapper.firstElementChild;
    items.appendChild(node);
    node.querySelector('.remove-row').addEventListener('click', () => node.remove());
  }

  document.getElementById('add-row').addEventListener('click', addRow);
  addRow();
})();
</script>

@endsection

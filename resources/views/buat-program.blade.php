@extends('layouts.verivied')

@section('title', 'Buat Program Latihan')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">Buat Program</h1>
    <a href="{{ route('trainer.programs.index') }}"
       class="inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-gray-700 font-semibold hover:bg-gray-300">
      Kembali
    </a>
  </div>

  {{-- Error summary --}}
  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white shadow-md rounded-lg p-6 space-y-8">
    {{-- Program form --}}
    <form id="program-form" method="POST" action="{{ route('trainer.programs.store') }}" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Program</label>
          <input name="name" value="{{ old('name') }}" required
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
          <input name="type" value="{{ old('type') }}"
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none"
                 placeholder="mis. HIIT, Strength, Cardio">
          @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tingkat</label>
          <select name="difficulty"
                  class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
            <option value="" {{ old('difficulty')===''?'selected':'' }}>Pilih</option>
            @foreach (['Beginner','Intermediate','Advanced'] as $opt)
              <option value="{{ $opt }}" @selected(old('difficulty')===$opt)>{{ $opt }}</option>
            @endforeach
          </select>
          @error('difficulty') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Durasi Total (menit)</label>
          <input type="number" min="1" name="duration_minutes" value="{{ old('duration_minutes') }}"
                 class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
          @error('duration_minutes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Program Items --}}
      <div class="border-t pt-6">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-xl font-bold text-[#2E471F]">Item Latihan</h2>
          <button type="button" id="add-row"
                  class="inline-flex items-center rounded-lg bg-[#2E7D32] px-3 py-1.5 text-white text-sm font-semibold shadow hover:opacity-90">
            + Tambah Item
          </button>
        </div>

        <div id="items" class="space-y-3">
          {{-- Template row gets cloned by JS --}}
        </div>

        {{-- Hidden template --}}
        <template id="item-template">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 border border-gray-200 rounded-lg p-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Latihan</label>
              <input name="__NAME__[exercise_name]" required
                     class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Durasi (menit)</label>
              <input type="number" min="1" name="__NAME__[duration_minutes]"
                     class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
            </div>
            <div class="flex gap-2">
              <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Intensitas</label>
                <select name="__NAME__[intensity_level]"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-800 focus:border-[#2E471F] focus:outline-none">
                  <option value="">Pilih</option>
                  <option value="Low">Low</option>
                  <option value="Medium">Medium</option>
                  <option value="High">High</option>
                </select>
              </div>
              <button type="button"
                      class="remove-row self-end inline-flex items-center rounded-lg border border-red-300 px-3 py-2 text-red-700 bg-white hover:bg-red-50">
                Hapus
              </button>
            </div>
          </div>
        </template>
      </div>

      <div class="flex gap-3">
        <button type="submit"
                class="inline-flex items-center rounded-lg bg-[#2E7D32] px-4 py-2 text-white font-semibold shadow hover:opacity-90">
          Simpan Program
        </button>
        <a href="{{ route('trainer.programs.index') }}"
           class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-gray-700 font-semibold bg-white hover:bg-gray-50">
          Batal
        </a>
      </div>
    </form>
  </div>

  {{-- JS for dynamic rows --}}
  <script>
    (function() {
      let idx = 0;
      const items = document.getElementById('items');
      const tpl = document.getElementById('item-template').innerHTML;

      function addRow() {
        const html = tpl.replaceAll('__NAME__', `items[${idx++}]`);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const node = wrapper.firstElementChild;
        items.appendChild(node);
        node.querySelector('.remove-row').addEventListener('click', () => node.remove());
      }

      document.getElementById('add-row').addEventListener('click', addRow);

      // Start with one empty row
      addRow();
    })();
  </script>
@endsection

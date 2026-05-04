@extends('layouts.verivied')

@section('title', 'Edit Program: ' . $program->name)

@section('content')

<div class="max-w-3xl mx-auto cm-fadein">

  {{-- HEADER --}}
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ route('trainer.programs.show', $program) }}"
       class="h-9 w-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center
              text-gray-500 hover:text-[#2E471F] hover:border-[#2E471F] transition-all duration-150">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </a>
    <div>
      <h1 class="font-raleway text-xl font-bold text-[#2E471F]">Edit Program</h1>
      <p class="text-xs text-gray-400">{{ $program->name }}</p>
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

  <form action="{{ route('trainer.programs.update', $program) }}" method="POST" class="space-y-6">
    @csrf @method('PUT')

    {{-- PROGRAM INFO CARD --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
      <h2 class="font-raleway font-bold text-[#2E471F] text-lg mb-4">Informasi Program</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Program</label>
          <input type="text" name="name" value="{{ old('name', $program->name) }}" required
                 class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 text-sm
                        focus:border-[#2E471F] focus:ring-2 focus:ring-[#2E471F]/20 focus:outline-none transition-all duration-150">
          @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe</label>
          <input type="text" name="type" value="{{ old('type', $program->type) }}"
                 placeholder="mis. HIIT, Strength, Cardio"
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
              <option value="{{ $opt }}" @selected(old('difficulty', $program->difficulty) === $opt)>{{ $opt }}</option>
            @endforeach
          </select>
          @error('difficulty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>
    </div>

    {{-- ITEMS LATIHAN CARD --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="font-raleway font-bold text-[#2E471F] text-lg">Item Latihan</h2>
          <p class="text-xs text-gray-400">Tambah, edit, atau hapus gerakan dalam program ini.</p>
        </div>
        <button type="button" id="add-row"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#EFE6D2] text-[#2E471F]
                       text-sm font-semibold hover:bg-[#F5A623]/20 transition-colors duration-150">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
          </svg>
          Tambah
        </button>
      </div>

      <div id="items" class="space-y-3">
        @foreach(old('items', $program->items->toArray()) as $i => $item)
          <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item['program_item_id'] ?? $item['id'] ?? '' }}">

            <div class="sm:col-span-5">
              <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Latihan</label>
              <input type="text" name="items[{{ $i }}][exercise_name]"
                     value="{{ $item['exercise_name'] ?? '' }}" required
                     placeholder="mis. Push Up"
                     class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800
                            focus:border-[#2E471F] focus:outline-none">
            </div>
            <div class="sm:col-span-3">
              <label class="block text-xs font-semibold text-gray-600 mb-1">Durasi (mnt)</label>
              <input type="number" name="items[{{ $i }}][duration_minutes]" min="0"
                     value="{{ $item['duration_minutes'] ?? '' }}" placeholder="10"
                     class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800
                            focus:border-[#2E471F] focus:outline-none">
            </div>
            <div class="sm:col-span-3">
              <label class="block text-xs font-semibold text-gray-600 mb-1">Intensitas</label>
              <select name="items[{{ $i }}][intensity_level]"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 bg-white
                             focus:border-[#2E471F] focus:outline-none">
                <option value="">Pilih</option>
                @foreach(['Rendah','Sedang','Tinggi'] as $lvl)
                  <option value="{{ $lvl }}" @selected(($item['intensity_level'] ?? '') === $lvl)>{{ $lvl }}</option>
                @endforeach
              </select>
            </div>
            <div class="sm:col-span-1 flex items-end">
              <button type="button" onclick="removeRow(this)"
                      class="w-full sm:w-auto h-10 px-3 rounded-lg border border-red-200 text-red-500 bg-white
                             hover:bg-red-50 transition-colors duration-150 flex items-center justify-center"
                      title="Hapus item">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
              </button>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Empty state --}}
      <div id="empty-items" class="hidden mt-4 text-center py-8 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
        Belum ada item latihan. Klik "Tambah" untuk menambahkan gerakan.
      </div>

      {{-- Template --}}
      <template id="item-template">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
          <input type="hidden" name="items[__INDEX__][id]" value="">
          <div class="sm:col-span-5">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Latihan</label>
            <input type="text" name="items[__INDEX__][exercise_name]" required placeholder="mis. Push Up"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 focus:border-[#2E471F] focus:outline-none">
          </div>
          <div class="sm:col-span-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Durasi (mnt)</label>
            <input type="number" name="items[__INDEX__][duration_minutes]" min="0" placeholder="10"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 focus:border-[#2E471F] focus:outline-none">
          </div>
          <div class="sm:col-span-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Intensitas</label>
            <select name="items[__INDEX__][intensity_level]"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800 bg-white focus:border-[#2E471F] focus:outline-none">
              <option value="">Pilih</option>
              <option value="Rendah">Rendah</option>
              <option value="Sedang">Sedang</option>
              <option value="Tinggi">Tinggi</option>
            </select>
          </div>
          <div class="sm:col-span-1 flex items-end">
            <button type="button" onclick="removeRow(this)"
                    class="w-full sm:w-auto h-10 px-3 rounded-lg border border-red-200 text-red-500 bg-white hover:bg-red-50 transition-colors duration-150 flex items-center justify-center">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
              </svg>
            </button>
          </div>
        </div>
      </template>
    </div>

    {{-- ACTIONS --}}
    <div class="flex justify-between items-center">
      {{-- DELETE PROGRAM --}}
      <form action="{{ route('trainer.programs.destroy', $program) }}" method="POST"
            id="del-program-{{ $program->program_id }}" class="inline">
        @csrf @method('DELETE')
        <button type="button"
                onclick="showDeleteModal(document.getElementById('del-program-{{ $program->program_id }}'), 'Hapus program \'{{ addslashes($program->name) }}\'? Semua item latihan akan ikut terhapus.')"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 border border-red-200
                       text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors duration-150">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
          </svg>
          Hapus Program
        </button>
      </form>

      <div class="flex gap-3">
        <a href="{{ route('trainer.programs.index') }}"
           class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-semibold
                  hover:bg-gray-50 transition-colors duration-150">
          Batal
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#2E471F] text-white text-sm font-semibold
                       shadow hover:bg-[#3d6628] hover:-translate-y-0.5 transition-all duration-200">
          Simpan Perubahan
        </button>
      </div>
    </div>
  </form>

</div>

<script>
let itemIndex = {{ count($program->items) }};
const itemsContainer = document.getElementById('items');
const emptyState = document.getElementById('empty-items');
const tpl = document.getElementById('item-template').innerHTML;

function refreshEmptyState() {
  const visible = itemsContainer.querySelectorAll(':scope > div').length;
  emptyState.classList.toggle('hidden', visible > 0);
}

function addRow() {
  const html = tpl.replaceAll('__INDEX__', itemIndex++);
  const wrapper = document.createElement('div');
  wrapper.innerHTML = html.trim();
  itemsContainer.appendChild(wrapper.firstElementChild);
  refreshEmptyState();
}

function removeRow(btn) {
  const row = btn.closest('div.grid');
  row.remove();
  refreshEmptyState();
}

document.getElementById('add-row').addEventListener('click', addRow);
refreshEmptyState();
</script>

@endsection

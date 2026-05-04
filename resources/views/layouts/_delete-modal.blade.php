{{-- Delete Confirmation Modal — included once per layout --}}
<div id="deleteModal"
     class="hidden fixed inset-0 z-[999] items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">

  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

  {{-- Panel --}}
  <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 cm-modal-pop">

    {{-- Icon --}}
    <div class="flex justify-center mb-4">
      <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
      </div>
    </div>

    {{-- Text --}}
    <h3 id="deleteModalTitle" class="text-lg font-bold text-center text-gray-800 mb-2">
      Konfirmasi Hapus
    </h3>
    <p id="deleteModalMessage" class="text-sm text-center text-gray-500 mb-6 leading-relaxed">
      Apakah kamu yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
    </p>

    {{-- Buttons --}}
    <div class="flex gap-3">
      <button type="button" onclick="closeDeleteModal()"
              class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600
                     hover:bg-gray-50 transition">
        Batal
      </button>
      <button type="button" id="deleteConfirmBtn" onclick="confirmDelete()"
              class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 text-sm font-semibold text-white
                     hover:bg-red-600 transition">
        Ya, Hapus
      </button>
    </div>
  </div>
</div>

<style>
  @keyframes cm-modal-pop {
    from { opacity: 0; transform: scale(0.94); }
    to   { opacity: 1; transform: scale(1); }
  }
  .cm-modal-pop { animation: cm-modal-pop 0.2s ease-out; }
</style>

<script>
  let _pendingDeleteForm = null;

  function showDeleteModal(formEl, message) {
    _pendingDeleteForm = formEl;
    if (message) {
      document.getElementById('deleteModalMessage').textContent = message;
    }
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    _pendingDeleteForm = null;
  }

  function confirmDelete() {
    if (_pendingDeleteForm) {
      _pendingDeleteForm.submit();
    }
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
  });
</script>

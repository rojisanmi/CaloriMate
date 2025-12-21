@extends('layouts.verivied')

@section('title', 'Kelola Menu Makanan')

@section('content')
<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.5s ease-out;
    }

    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out;
    }

    .animate-delay-1 {
        animation-delay: 0.1s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-2 {
        animation-delay: 0.2s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-3 {
        animation-delay: 0.3s;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .animate-delay-4 {
        animation-delay: 0.4s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
</style>

  {{-- ALERT SUCCESS --}}
  @if (session('ok'))
    <div id="alert-success"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999]
                rounded-lg bg-emerald-600 text-white px-6 py-3 shadow-lg text-center
                animate-fade-in-down">
      {{ session('ok') }}
    </div>

    <script>
      setTimeout(() => {
        const el = document.getElementById('alert-success');
        if (el) {
          el.style.transition = 'opacity 0.4s ease';
          el.style.opacity = '0';
          setTimeout(() => el.remove(), 400);
        }
      }, 3000);
    </script>
  @endif

  {{-- TITLE + ACTION --}}
  <div class="flex items-center justify-between mb-6 animate-fade-in-down">
    <h1 class="text-3xl font-extrabold text-[#2E471F]">
      Kelola Menu Makanan
    </h1>
  </div>

  {{-- FILTER BAR --}}
  <div class="mb-4 flex items-center justify-between gap-4 animate-slide-in-left animate-delay-1">

    {{-- KIRI: SEARCH --}}
    <form method="GET"
          action="{{ route('trainer.foods.index') }}"
          class="w-full max-w-md">
      <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Search"
            class="w-full rounded-lg border border-gray-300 bg-white
                    px-4 py-2 text-gray-700 placeholder-gray-400
                    focus:border-[#2E471F] focus:outline-none
                    transition-all duration-200 focus:ring-2 focus:ring-[#2E471F]/30">
    </form>

    {{-- KANAN: PER PAGE + TAMBAH ITEM --}}
    <div class="flex items-center gap-3">

      {{-- ITEMS PER PAGE --}}
      <form method="GET"
            action="{{ route('trainer.foods.index') }}"
            class="flex items-center gap-2">
        <input type="hidden" name="q" value="{{ request('q') }}">

        <label for="per_page"
              class="text-sm text-gray-600 whitespace-nowrap">
          Items per page
        </label>

        <select id="per_page" name="per_page"
                onchange="this.form.submit()"
                class="rounded-md border border-gray-300 bg-white
                      px-3 py-2 text-sm text-gray-700
                      focus:border-[#2E471F] focus:outline-none
                      transition-all duration-200">
          @foreach ([10,25,50,100] as $n)
            <option value="{{ $n }}"
              @selected((int)request('per_page', 10) === $n)>
              {{ $n }}
            </option>
          @endforeach
        </select>
      </form>

      {{-- TAMBAH ITEM --}}
      <a href="{{ route('trainer.foods.create') }}"
        class="inline-flex items-center rounded-lg
                bg-[#2E7D32] px-4 py-2
                text-white font-semibold shadow
                hover:bg-[#1B5E20] hover:shadow-lg hover:-translate-y-0.5
                transition-all duration-200 whitespace-nowrap
                active:scale-95">
        Tambah Item
      </a>

    </div>
  </div>

  {{-- TABLE --}}
  <div class="relative bg-white shadow-md sm:rounded-lg
              max-h-[65vh] overflow-y-auto overflow-x-auto
              animate-scale-in animate-delay-2">

    <table class="w-full text-sm text-left text-gray-600">

      {{-- STICKY HEADER --}}
      <thead class="text-xs uppercase bg-gray-50 sticky top-0 z-10">
        <tr>
          <th class="px-6 py-3">Nama</th>
          <th class="px-6 py-3">Gramasi</th>
          <th class="px-6 py-3">Kalori</th>
          <th class="px-6 py-3">Lemak</th>
          <th class="px-6 py-3">Karbo</th>
          <th class="px-6 py-3">Protein</th>
          <th class="px-6 py-3 text-right">Action</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($foods as $food)
          <tr class="border-b hover:bg-gray-50 transition-colors duration-150">
            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
              {{ $food->name }}
            </th>
            <td class="px-6 py-4">{{ $food->grammage }}</td>
            <td class="px-6 py-4">{{ $food->calories_per_portion }}</td>
            <td class="px-6 py-4">{{ $food->total_fat }}</td>
            <td class="px-6 py-4">{{ $food->total_carbo }}</td>
            <td class="px-6 py-4">{{ $food->total_protein }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('trainer.foods.edit', $food) }}"
                   class="inline-flex items-center rounded-md px-3 py-1.5
                          text-xs font-semibold text-white bg-[#2E7D32]
                          hover:bg-[#1B5E20] hover:shadow-md
                          transition-all duration-200 active:scale-95">
                  Edit
                </a>

                <form method="POST"
                      action="{{ route('trainer.foods.destroy', $food) }}"
                      onsubmit="return confirm('Hapus item ini?')">
                  @csrf
                  @method('DELETE')

                  <button type="submit"
                          class="inline-flex items-center rounded-md px-3 py-1.5
                                 text-xs font-semibold text-white bg-red-600
                                 hover:bg-red-700 hover:shadow-md
                                 transition-all duration-200 active:scale-95">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7"
                class="px-6 py-10 text-center text-gray-500">
              Data tidak ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- FOOTER (PAGINATION) --}}
    <div class="flex items-center justify-between px-4 py-3
                bg-white border-t">

      <div class="text-sm text-gray-600">
        @if(method_exists($foods, 'total'))
          Menampilkan {{ $foods->firstItem() ?? 0 }}
          sampai {{ $foods->lastItem() ?? 0 }}
          dari {{ $foods->total() }} hasil
        @endif
      </div>

      <div>
        {{ $foods->appends([
            'q' => request('q'),
            'per_page' => request('per_page')
          ])->links() }}
      </div>

    </div>
  </div>

@endsection
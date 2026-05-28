{{-- 
    Food Card Component
    Usage: <x-food-card :food="$food" />
    
    Props:
    - food (required): MasterMakanan model instance
--}}

<div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
    {{-- Image Container --}}
    <div class="relative bg-gray-100 overflow-hidden" style="aspect-ratio: 4/3;">
        @if($food->foto)
            <img src="{{ asset('storage/' . $food->foto) }}"
                 alt="{{ $food->nama_makanan }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'">
        @else
            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80"
                 alt="{{ $food->nama_makanan }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @endif
    </div>

    {{-- Card Content --}}
    <div class="p-5">
        {{-- Category Badge --}}
        <div class="inline-block bg-amber-100 text-amber-700 text-xs font-extrabold tracking-wider uppercase px-3 py-1 rounded-full mb-3">
            {{ $food->kategori ?? 'Umum' }}
        </div>

        {{-- Food Name --}}
        <h3 class="text-base font-bold text-gray-900 leading-tight mb-3 line-clamp-2">
            {{ $food->nama_makanan }}
        </h3>

        {{-- Weight Info --}}
        <div class="flex items-center gap-2 text-gray-600 text-sm mb-5">
            <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            <span class="font-medium">
                {{ $food->berat ? number_format($food->berat, 2) . ' kg' : '— kg' }}
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3">
            {{-- Edit Button --}}
            <a href="{{ route('foods.edit', $food->id) }}"
               class="flex-1 flex items-center justify-center gap-2 border-2 border-gray-200 text-gray-700 hover:border-green-500 hover:text-green-600 hover:bg-green-50 font-semibold text-xs px-4 py-2.5 rounded-lg transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Master</span>
            </a>

            {{-- Delete Button --}}
            <form action="{{ route('foods.destroy', $food->id) }}"
                  method="POST"
                  class="delete-form"
                  data-name="{{ $food->nama_makanan }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ addslashes($food->nama_makanan) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-11 h-11 flex items-center justify-center border-2 border-red-200 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

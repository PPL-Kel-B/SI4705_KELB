@extends('layouts.user')

@section('title', 'Riwayat')

@section('content')
<div class="p-2 min-h-screen font-jakarta antialiased text-gray-800">
    
    <div class="mb-8">
        <h1 class="text-4xl font-black text-[#1cb764] tracking-tight">Riwayat Donasi</h1>
        <p class="max-w-3xl text-gray-500 font-medium mt-2 text-lg">
        Kelola dan pantau seluruh kontribusi makanan yang telah Anda bagikan.
        Setiap donasi Anda membantu mengurangi kelaparan di komunitas.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
        <form action="{{ url()->current() }}" method="GET" class="relative w-full lg:w-[450px]">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            @if(request('start_date') && request('end_date'))
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            @endif

            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                </svg>
            </span>
            
            <input type="text" 
                   name="search" 
                   list="suggestion-list"
                   value="{{ request('search') }}"
                   placeholder="Cari donasi atau unit bisnis..." 
                   autocomplete="off"
                   class="w-full pl-11 pr-4 py-3 bg-white text-sm border border-gray-150 rounded-xl focus:outline-none shadow-sm placeholder-gray-400 font-semibold">

            <datalist id="suggestion-list">
                @if(isset($searchSuggestions))
                    @foreach($searchSuggestions as $opsi)
                        <option value="{{ $opsi }}"></option>
                    @endforeach
                @endif
            </datalist>
        </form>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-start lg:justify-end">
            <div class="flex items-center gap-2 p-1 rounded-2xl bg-[#E7ECE8]">
                @php $currentStatus = request('status', 'all'); @endphp

                <a href="{{ url()->current() }}?status=all{{ request('search') ? '&search='.request('search') : '' }}{{ request('start_date') ? '&start_date='.request('start_date').'&end_date='.request('end_date') : '' }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $currentStatus == 'all' ? 'bg-[#046A38] text-white shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                    Semua
                </a>

                <a href="{{ url()->current() }}?status=menunggu_pembayaran{{ request('search') ? '&search='.request('search') : '' }}{{ request('start_date') ? '&start_date='.request('start_date').'&end_date='.request('end_date') : '' }}" 
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $currentStatus == 'menunggu_pembayaran' ? 'bg-[#046A38] text-white shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                    Menunggu Pembayaran
                </a>

                <a href="{{ url()->current() }}?status=selesai{{ request('search') ? '&search='.request('search') : '' }}{{ request('start_date') ? '&start_date='.request('start_date').'&end_date='.request('end_date') : '' }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $currentStatus == 'selesai' ? 'bg-[#046A38] text-white shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                    Selesai
                </a>

                <a href="{{ url()->current() }}?status=proses{{ request('search') ? '&search='.request('search') : '' }}{{ request('start_date') ? '&start_date='.request('start_date').'&end_date='.request('end_date') : '' }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $currentStatus == 'proses' ? 'bg-[#046A38] text-white shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                    Proses
                </a>

                <a href="{{ url()->current() }}?status=batal{{ request('search') ? '&search='.request('search') : '' }}{{ request('start_date') ? '&start_date='.request('start_date').'&end_date='.request('end_date') : '' }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $currentStatus == 'batal' ? 'bg-[#046A38] text-white shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                    Batal
                </a>
            </div>

            <div class="relative" id="date-range-popover">
                <button onclick="toggleDatePopover()" type="button" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-150 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4" style="color: #046A38;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ request('start_date') && request('end_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y').' - '.\Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Rentang' }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400 ml-1"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </button>

                <div id="date-content" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl border border-gray-150 p-4 shadow-xl z-50">
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full p-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#046A38] font-semibold text-gray-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full p-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#046A38] font-semibold text-gray-700">
                        </div>

                        <div class="flex gap-2 mt-1">
                            <a href="{{ url()->current() }}?status={{ request('status', 'all') }}{{ request('search') ? '&search='.request('search') : '' }}" class="w-1/2 text-center py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-200 transition">
                                Reset
                            </a>
                            <button type="submit" class="w-1/2 py-2 bg-[#046A38] text-white rounded-xl text-xs font-bold hover:bg-[#03532B] transition shadow-sm">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-150 p-8 overflow-x-auto h-auto flex flex-col justify-between mt-4">
        <table class="w-full text-left border-collapse min-w-[850px]">
            <thead>
                <tr class="text-gray-400 uppercase text-[11px] font-bold tracking-widest border-b border-gray-100">
                    <th class="pb-5 font-bold w-40">Tanggal</th>
                    <th class="pb-5 font-bold">Nama Makanan</th>
                    <th class="pb-5 font-bold">Unit Bisnis</th>
                    <th class="pb-5 font-bold">Lokasi</th>
                    <th class="pb-5 font-bold text-center w-36">Status</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold text-gray-800">
                @forelse($pesanans as $pesanan)
                @php
                    $statusPesanan = $pesanan->status;

                    if ($statusPesanan == 'menunggu_pembayaran') {
                        $urlTujuan = route('user.pembayaran', $pesanan->id);
                    } elseif (in_array($statusPesanan, ['proses', 'siap_diambil', 'dibayar'])) {
                        $urlTujuan = route('user.pembayaran.berhasil', $pesanan->id);
                    } else {
                        $urlTujuan = route('user.riwayat.show', $pesanan->id);
                    }
                @endphp

                <tr class="border-b border-gray-100/70 transition cursor-pointer hover:bg-gray-50/80" 
                    onclick="window.location='{{ $urlTujuan }}'">
                    <td class="py-7 text-gray-500 font-semibold vertical-align-middle">
                        <div class="flex flex-col justify-center">
                            <span class="text-gray-700 text-xs font-bold">{{ \Carbon\Carbon::parse($pesanan->waktu_pesan)->translatedFormat('d M Y') }}</span>
                            <span class="text-[11px] text-gray-400 font-medium mt-1">{{ \Carbon\Carbon::parse($pesanan->waktu_pesan)->format('H:i') }} WIB</span>
                        </div>
                    </td>
                    
                    <td class="py-7 flex items-center gap-3">
                        @php
                            $kategori = strtolower($pesanan->kategori ?? '');
                            $status = $pesanan->status;

                            if ($status == 'selesai') {
                                $statusStyle = 'background-color: #E6F4EA; color: #137333;';
                            } elseif ($status == 'menunggu_pembayaran') {
                                $statusStyle = 'background-color: #FFF3E0; color: #B85C00;';
                            } elseif ($status == 'proses' || $status == 'siap_diambil' || $status == 'dibayar') {
                                $statusStyle = 'background-color: #FDF4E7; color: #B06000;';
                            } else {
                                $statusStyle = 'background-color: #FCE8E6; color: #D93025;';
                            }
                        @endphp

                        @if(strpos($kategori, 'berat') !== false)
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $statusStyle }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <rect x="3" y="7" width="18" height="13" rx="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11h18M10 3h4v4h-4zM7 15h3m4 0h3" />
                                </svg>
                            </div>
                        @elseif(strpos($kategori, 'ringan') !== false || strpos($kategori, 'cemilan') !== false)
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $statusStyle }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M4 12a8 8 0 0 1 16 0M3 15h18M5 15a4 4 0 0 0 14 0M4 18h16a1 1 0 0 1 1 1v1H3v-1a1 1 0 0 1 1-1Z" />
                                </svg>
                            </div>
                        @elseif(strpos($kategori, 'dessert') !== false)
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $statusStyle }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a1 1 0 0 0-.6-.9L12 3 3.6 7.1A1 1 0 0 0 3 8v8a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3ZM3 12h18M12 3v16" />
                                    <circle cx="12" cy="7" r="1" fill="currentColor" />
                                </svg>
                            </div>
                        @elseif(strpos($kategori, 'minuman') !== false)
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $statusStyle }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12l-1.5 15a2 2 0 0 1-2 1.8h-5A2 2 0 0 1 6 18L4.5 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M15 3l1-2" />
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="{{ $statusStyle }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5v6a3 3 0 0 1-3 3h-4a3 3 0 0 1-3-3V5M7 2v3M17 2v3" />
                                </svg>
                            </div>
                        @endif

                        @php
                            if (strpos($kategori, 'berat') !== false) { $label = 'Makanan Berat'; }
                            elseif (strpos($kategori, 'ringan') !== false || strpos($kategori, 'cemilan') !== false) { $label = 'Cemilan'; }
                            elseif (strpos($kategori, 'dessert') !== false) { $label = 'Dessert'; }
                            elseif (strpos($kategori, 'minuman') !== false) { $label = 'Minuman'; }
                            else { $label = 'Lainnya'; }
                        @endphp

                        <div class="flex flex-col">
                            <span class="text-gray-900 font-black text-[13px]">{{ $pesanan->nama_makanan }}</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wide mt-0.5">{{ $label }}</span>
                        </div>
                    </td>
                    
                    <td class="py-7 text-gray-500 font-medium">{{ $pesanan->nama_usaha }}</td>
                    
                    <td class="py-7 text-gray-400 font-medium">
                        <div class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-gray-300"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            <span class="text-gray-500">Bandung, Jawa Barat</span>
                        </div>
                    </td>
                    
                    <td class="py-7 text-center">
                        @if($pesanan->status == 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-[#137333] bg-[#E6F4EA]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#137333]"></div>
                                SELESAI
                            </span>
                        @elseif($pesanan->status == 'menunggu_pembayaran')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-[#B85C00] bg-[#FFF3E0]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 text-[#B85C00]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                MENUNGGU PEMBAYARAN
                            </span>
                        @elseif($pesanan->status == 'proses' || $pesanan->status == 'siap_diambil' || $pesanan->status == 'dibayar')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-[#B06000] bg-[#FDF4E7]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#F9AB00]"></div>
                                PROSES
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-[#C5221F] bg-[#FCE8E6]">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#D93025]"></div>
                                BATAL
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-gray-400 font-medium">
                        Belum ada riwayat transaksi.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 pt-4 border-t border-gray-100 text-xs font-semibold text-gray-400">
            <p>
                Menampilkan 
                <span class="text-gray-800 font-bold">{{ $pesanans->firstItem() ?? 0 }}</span> 
                sampai 
                <span class="text-gray-800 font-bold">{{ $pesanans->lastItem() ?? 0 }}</span> 
            </p>
            
            <div class="flex items-center gap-1.5">
                @if ($pesanans->onFirstPage())
                    <button class="p-2 text-gray-200 cursor-not-allowed" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                @else
                    <a href="{{ $pesanans->previousPageUrl() }}" class="p-2 text-gray-400 hover:bg-gray-50 rounded-lg block">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </a>
                @endif

                @php
                    $start = max($pesanans->currentPage() - 2, 1);
                    $end = min($start + 4, $pesanans->lastPage());
                    if ($end - $start < 4) { $start = max($end - 4, 1); }
                @endphp

                @if ($start > 1)
                    <a href="{{ $pesanans->url(1) }}" class="w-7 h-7 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 font-bold text-xs transition flex items-center justify-center">1</a>
                    @if ($start > 2) <span class="px-0.5 text-gray-400">...</span> @endif
                @endif

                @foreach (range($start, $end) as $page)
                    @if ($page == $pesanans->currentPage())
                        <button class="w-7 h-7 rounded-lg text-white font-bold text-xs" style="background-color: #046A38;">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $pesanans->url($page) }}" class="w-7 h-7 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 font-bold text-xs transition flex items-center justify-center">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($end < $pesanans->lastPage())
                    @if ($end < $pesanans->lastPage() - 1) <span class="px-0.5 text-gray-400">...</span> @endif
                    <a href="{{ $pesanans->url($pesanans->lastPage()) }}" class="w-7 h-7 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 font-bold text-xs transition flex items-center justify-center">{{ $pesanans->lastPage() }}</a>
                @endif

                @if ($pesanans->hasMorePages())
                    <a href="{{ $pesanans->nextPageUrl() }}" class="p-2.5 text-gray-400 hover:bg-gray-50 rounded-lg block">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                @else
                    <button class="p-2.5 text-gray-200 cursor-not-allowed" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleDatePopover() {
    const content = document.getElementById('date-content');
    content.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const popover = document.getElementById('date-range-popover');
    const content = document.getElementById('date-content');
    
    if (popover && !popover.contains(event.target)) {
        content.classList.add('hidden');
    }
});
</script>
@endsection
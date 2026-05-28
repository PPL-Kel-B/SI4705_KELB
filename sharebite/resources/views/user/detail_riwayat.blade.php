@extends('layouts.user')

@section('title', 'Detail Riwayat Donasi')

{{-- REVISI: Menyisipkan CDN SweetAlert2 agar pop-up modern bisa berjalan --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<div class="p-2 min-h-screen font-sans antialiased text-gray-800">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('user.riwayat') }}" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 transition flex items-center justify-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Detail Riwayat Donasi</h1>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-bold">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-150 flex flex-col lg:flex-row gap-8 mb-6 overflow-hidden items-stretch min-h-[35rem]">
        <div class="relative w-full md:w-[45%] min-h-[22rem] md:min-h-full rounded-l-[2rem] overflow-hidden shrink-0 self-stretch">
            <img src="{{ $pesanan->foto ? asset('storage/' . $pesanan->foto) : 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&q=80&w=600' }}" 
                 alt="Foto Makanan" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute top-6 left-6 z-10">
                @if($pesanan->status == 'selesai')
                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-white" style="background-color: #137333;">SELESAI</span>
                @elseif($pesanan->status == 'menunggu_pembayaran')
                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-white" style="background-color: #E65100;">MENUNGGU PEMBAYARAN</span>
                @elseif($pesanan->status == 'proses' || $pesanan->status == 'siap_diambil' || $pesanan->status == 'dibayar')
                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-white" style="background-color: #F9AB00;">PROSES</span>
                @else
                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider text-white" style="background-color: #D93025;">BATAL</span>
                @endif

            </div>
        </div>

        {{-- KONTEN SEBELAH KANAN: Ditambahkan h-full, pb-6 agar teks bawah terdorong naik dan aman --}}
        <div class="flex flex-col justify-between w-full lg:w-[55%] p-8 md:p-12 lg:p-14 lg:pb-16 min-h-full">
            <div>
                <div class="flex justify-between items-start gap-4 mb-2">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">{{ $pesanan->nama_makanan }}</h2>
                    <span class="text-xl font-black text-[#1cb764] shrink-0">Rp {{ number_format($pesanan->total_harga ?? 15000, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center gap-1.5 text-gray-500 font-bold text-sm mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" /></svg>
                    <span>{{ $pesanan->nama_usaha }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-[#E7ECE8]/40 border border-gray-100 rounded-xl p-4">
                        <span class="block text-[10px] font-black uppercase text-gray-400 tracking-wider mb-1">Tanggal Donasi</span>
                        <span class="text-sm font-black text-gray-800">{{ \Carbon\Carbon::parse($pesanan->waktu_pesan)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="bg-[#E7ECE8]/40 border border-gray-100 rounded-xl p-4">
                        <span class="block text-[10px] font-black uppercase text-gray-400 tracking-wider mb-1">Porsi</span>
                        <span class="text-sm font-black text-gray-800">{{ $pesanan->jumlah ?? 2 }} Porsi</span>
                    </div>
                </div>
            </div>

            
            <div class="flex justify-between items-center border-t border-gray-100 pt-5 mt-auto mb-4">
                <div class="flex items-center gap-2 text-[#137333] font-bold text-sm">
                    <div class="w-5 h-5 rounded-full bg-[#E6F4EA] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.14-.083l3.75-5.25Z" clip-rule="evenodd" /></svg>
                    </div>
                    <span>Verifikasi Berhasil</span>
                </div>
                <div class="text-right">
                    <span class="block text-[9px] font-bold uppercase text-gray-400 tracking-widest">ID:</span>
                    <span class="text-xs font-bold text-gray-600">SB-{{ $pesanan->id ?? '882910' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if((isset($ratingTerisi) && $ratingTerisi) || $pesanan->status == 'batal' || $pesanan->status == 'dibatalkan')
        @if($pesanan->status == 'batal' || $pesanan->status == 'dibatalkan')
            {{-- Tampilan Kotak Informasi khusus untuk Pesanan yang Dibatalkan --}}
            <div class="bg-red-50 border border-red-200 rounded-[2rem] p-8 text-center text-red-700 font-bold text-sm">
                Donasi ini telah dibatalkan. Anda tidak dapat mengirimkan bukti berbagi maupun penilaian rating.
            </div>
        @else
            {{-- Tampilan Lama jika Rating Sudah Terisi --}}
            <div class="bg-gray-50 border border-gray-200 rounded-[2rem] p-8 text-center text-gray-500 font-bold text-sm">
                Anda telah mengirimkan bukti berbagi dan penilaian rating untuk donasi ini.
            </div>
        @endif
    @else
        <form id="rating-form" action="{{ route('user.riwayat.storeRating', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-150 p-8 flex flex-col min-h-[34rem]">
                    <h3 class="text-md font-black text-gray-900 tracking-tight flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor" class="w-5 h-5 text-[#046A38]"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75Z" /></svg>
                        Unggah Bukti Berbagi
                    </h3>
                    
                    <div id="dropzone" onclick="document.getElementById('file-input').click()" class="border-2 border-dashed border-[#E7ECE8] hover:border-[#1cb764] rounded-2xl bg-gray-50/50 p-8 flex flex-col items-center justify-center text-center group cursor-pointer transition flex-grow">
                        <input type="file" id="file-input" name="bukti_berbagi" accept=".png, .jpg, .jpeg" class="hidden" onchange="handleFileSelect(this.files)">

                        <div class="w-12 h-12 rounded-full bg-[#E7ECE8]/60 flex items-center justify-center text-[#046A38] group-hover:bg-[#E6F4EA] transition mb-3">
                            <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.375A1.5 1.5 0 0 0 1.875 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        </div>
                        
                        <h4 id="upload-headline" class="text-sm font-black text-gray-800 mb-1">Pilih File atau seret ke sini</h4>
                        <p id="upload-subline" class="text-[11px] text-gray-400 font-bold max-w-xs mb-4">Tarik dan lepas foto bukti distribusi di sini (PNG, JPG, JPEG)</p>
                        <button type="button" class="px-4 py-2 bg-[#046A38] hover:bg-[#03532B] text-white text-xs font-black rounded-xl shadow-sm transition">Pilih File</button>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-150 p-8 flex flex-col min-h-[34rem]">
                    <h3 class="text-md font-black text-gray-900 tracking-tight flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor" class="w-5 h-5 text-[#046A38]"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.173-.443.81-.443.983 0l2.224 5.631 5.922.46c.49.038.687.643.326.974l-4.417 4.053 1.406 5.824c.117.487-.412.871-.832.618l-5.15-3.136-5.151 3.136c-.42.253-.949-.131-.832-.618l1.406-5.824-4.417-4.053c-.362-.331-.165-.936.326-.974l5.922-.46 2.224-5.632Z" /></svg>
                        Komentar Unit Bisnis
                    </h3>

                    <div class="mb-4">
                        <span class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1.5">Beri Rating Pengalaman <span class="text-red-500">*</span></span>
                        
                        <input type="hidden" name="skor_rating" id="skor_rating_input" value="">

                        <div class="flex items-center gap-1 text-xl">
                            @for($i = 1; $i <= 5; $i++)
                                <svg onclick="setRating({{ $i }})" onmouseover="hoverRating({{ $i }})" onmouseleave="resetRating()" 
                                     data-star="{{ $i }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                     class="w-6 h-6 text-gray-300 cursor-pointer transition-colors duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.173-.443.81-.443.983 0l2.224 5.631 5.922.46c.49.038.687.643.326.974l-4.417 4.053 1.406 5.824c.117.487-.412.871-.832.618l-5.15-3.136-5.151 3.136c-.42.253-.949-.131-.832-.618l1.406-5.824-4.417-4.053c-.362-.331-.165-.936.326-.974l5.922-.46 2.224-5.632Z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    <div class="flex-grow flex flex-col">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1.5">Ceritakan Pengalaman Anda</label>
                        <textarea name="catatan_pengalaman" placeholder="Ceritakan pengalaman Anda mengkurasi paket ini..." 
                                  class="w-full border border-gray-150 rounded-2xl p-4 text-xs font-semibold text-gray-700 bg-gray-50/30 focus:outline-none focus:border-[#1cb764] resize-none flex-grow min-h-[120px] placeholder-gray-300"></textarea>
                    </div>
                </div>
            </div>

            <div class="w-full flex justify-center mt-4">
                <button type="submit" class="w-full max-w-md py-3.5 bg-[#1cb764] hover:bg-[#179e56] text-white font-black text-sm rounded-2xl shadow-md transition flex items-center justify-center gap-2 tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/xl" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
                    Kirim
                </button>
            </div>
        </form>
    @endif

</div>

<script>
    // --- JS LOGIC UPLOAD BUKTI BERBAGI ---
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const headline = document.getElementById('upload-headline');
    const subline = document.getElementById('upload-subline');

    function handleFileSelect(files) {
        if (files.length > 0) { validateAndDisplayFile(files[0]); }
    }

    function validateAndDisplayFile(file) {
        const allowedExtensions = ['image/png', 'image/jpeg', 'image/jpg'];
        if (!allowedExtensions.includes(file.type)) {
            // REVISI ALERT 1: Mengubah alert bawaan menjadi SweetAlert2 kustom
            Swal.fire({
                icon: 'error',
                title: 'Format File Salah!',
                text: 'Hanya diperbolehkan mengunggah file gambar dengan format PNG, JPG, atau JPEG.',
                confirmButtonColor: '#046A38',
                customClass: { popup: 'rounded-[2rem]' }
            });
            fileInput.value = '';
            return;
        }
        headline.innerText = "File Siap Diunggah!";
        headline.classList.remove('text-gray-800');
        headline.classList.add('text-[#046A38]');
        subline.innerText = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
    }

    if (dropzone) {
        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('bg-[#E6F4EA]/20'); });
        dropzone.addEventListener('dragleave', () => { dropzone.classList.remove('bg-[#E6F4EA]/20'); });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('bg-[#E6F4EA]/20');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                validateAndDisplayFile(e.dataTransfer.files[0]);
            }
        });
    }

    // --- JS LOGIC KLIK BINTANG INTERAKTIF ---
    let currentSelectedRating = 0;
    const ratingInput = document.getElementById('skor_rating_input');
    const starElements = document.querySelectorAll('[data-star]');

    // Didaftarkan sebagai fungsi window agar tetap terbaca interaktif oleh event onclick di SVG Blade kamu
    window.setRating = function(val) {
        currentSelectedRating = val;
        ratingInput.value = val;
        highlightStars(val);
    }

    window.hoverRating = function(val) {
        highlightStars(val);
    }

    window.resetRating = function() {
        highlightStars(currentSelectedRating);
    }

    function highlightStars(count) {
        starElements.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-star'));
            if (starValue <= count) {
                star.setAttribute('fill', 'currentColor');
                star.classList.remove('text-gray-300');
                star.classList.add('text-amber-500');
            } else {
                star.setAttribute('fill', 'none');
                star.classList.remove('text-amber-500');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Validasi Sisi Klien Sebelum Form Di-submit
    const form = document.getElementById('rating-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!ratingInput.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Rating Belum Diisi!',
                    text: 'Silakan pilih bintang terlebih dahulu untuk memberikan penilaian.',
                    confirmButtonColor: '#1cb764',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } else if (!fileInput.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Bukti Berbagi Kosong!',
                    text: 'Silakan sertakan foto bukti berbagi terlebih dahulu.',
                    confirmButtonColor: '#1cb764',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        });
    }
</script>
@endsection
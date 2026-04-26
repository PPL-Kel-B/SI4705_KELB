<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu Aktif - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="min-h-screen flex">
    
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between py-8 px-6 hidden md:flex">
        <div>
            <div class="flex items-center gap-2 mb-10">
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 7.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <span class="text-xl font-bold text-green-600">ShareBite</span>
            </div>

            <nav class="space-y-4">
                <a href="#" class="flex items-center gap-3 text-gray-400 hover:text-green-600 font-medium p-2">
                    <span>📊</span> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 bg-green-50 text-green-600 font-semibold p-2 rounded-lg">
                    <span>🍴</span> Kelola Makanan
                </a>
                <a href="#" class="flex items-center gap-3 text-gray-400 hover:text-green-600 font-medium p-2">
                    <span>🛒</span> Pesanan
                </a>
                <a href="#" class="flex items-center gap-3 text-gray-400 hover:text-green-600 font-medium p-2">
                    <span>🕒</span> Riwayat
                </a>
            </nav>
        </div>

        <button class="flex items-center gap-3 text-red-500 font-medium p-2 hover:bg-red-50 rounded-lg transition">
            <span>⬅️</span> Logout
        </button>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        
        <header class="flex justify-between items-center mb-8">
            <div class="text-sm text-gray-400">
                Kelola Makanan > <span class="text-green-600 font-medium">Tambah Menu Baru</span>
            </div>
            <div class="flex items-center gap-4">
                <button class="relative p-2 text-gray-400 hover:bg-gray-100 rounded-full">
                    <span>🔔</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center gap-3 border-l pl-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase">Unit Bisnis</p>
                        <p class="text-sm font-bold">Arcamanik Hotel</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Arcamanik+Hotel&background=0D9488&color=fff" class="w-10 h-10 rounded-full shadow-sm">
                </div>
            </div>
        </header>

        <section x-data="{ isFree: false, stock: 15 }">
    <h1 class="text-3xl font-extrabold mb-2">Tambah Menu Aktif</h1>
    <p class="text-gray-500 mb-8">Tambahkan menu untuk dijual berdasarkan makanan master data.</p>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center gap-3">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('makanan.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 space-y-6">
                
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400">🔍</span>
                    <input type="text" placeholder="Cari nama menu atau kategori..." 
                           class="w-full pl-12 pr-4 py-3 bg-white rounded-xl border border-gray-100 shadow-sm focus:ring-2 focus:ring-green-500 outline-none transition">
                </div>

                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 space-y-3 h-80 overflow-y-auto">
                    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-xl cursor-pointer">
                        <div class="flex items-center gap-4">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100" class="w-12 h-12 rounded-lg object-cover">
                            <div>
                                <h4 class="font-bold text-sm">Nasi Campur Bali Organik</h4>
                                <p class="text-[10px] text-green-600 font-bold uppercase tracking-wider">Nasi Kotak</p>
                            </div>
                        </div>
                        <span class="bg-green-600 text-white text-[10px] px-3 py-1 rounded-full font-bold">Terpilih</span>
                    </div>
                    </div>

                <div class="bg-green-50 p-6 rounded-2xl flex justify-between items-center border border-green-100">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-white rounded-lg shadow-sm">🎁</div>
                        <div>
                            <h4 class="font-bold text-sm">Gratiskan makanan ini</h4>
                            <p class="text-xs text-gray-500">Menu ini akan dibagikan secara cuma-cuma</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_free" x-model="isFree" class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Stok</label>
                        <div class="flex items-center bg-white rounded-xl border border-gray-100 shadow-sm p-1">
                            <button type="button" @click="stock--" class="w-10 h-10 bg-gray-50 rounded-lg hover:bg-gray-100 text-xl font-bold text-green-600">－</button>
                            <input type="number" name="jumlah_porsi" x-model="stock" class="w-full text-center font-bold outline-none bg-transparent">
                            <button type="button" @click="stock++" class="w-10 h-10 bg-gray-50 rounded-lg hover:bg-gray-100 text-xl font-bold text-green-600">＋</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Batas Pengambilan</label>
                        <div class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
                            <span class="text-green-600">🕒</span>
                            <input type="text" name="batas_waktu" value="21:00 WIB" class="w-full font-bold outline-none bg-transparent">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-green-200 hover:bg-green-800 transition transform active:scale-[0.98]">
                    Publikasikan Menu Aktif
                </button>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                    <span class="w-2 h-2 bg-orange-400 rounded-full"></span> Pratinjau
                </div>
                
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200 overflow-hidden border border-gray-50">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800" class="w-full h-64 object-cover">
                        <span class="absolute top-4 left-4 bg-green-900/80 backdrop-blur-md text-white text-[10px] px-3 py-1 rounded-full font-bold uppercase">Tersedia</span>
                    </div>
                    <div class="p-8">
                        <h2 class="text-2xl font-extrabold mb-3">Nasi Campur Bali Organik</h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6">
                            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
                        </p>
                        
                        <div class="flex items-center gap-2 text-gray-600 mb-2">
                            <span class="text-green-600 font-bold">🍱</span>
                            <span class="font-bold text-sm" x-text="stock + ' Porsi'"></span>
                        </div>
                        
                        <div class="text-2xl font-extrabold text-orange-500" x-text="isFree ? 'GRATIS' : 'Rp 12.000'"></div>
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-2xl border-l-4 border-green-500 flex gap-4">
                    <div class="text-2xl">💡</div>
                    <div>
                        <h5 class="font-bold text-sm text-green-800 mb-1">Tips Kurasi</h5>
                        <p class="text-xs text-green-700 leading-relaxed">Pastikan stok yang diinput akurat dengan sistem inventori dapur Anda untuk menghindari pembatalan pesanan.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</section>
    </main>
</div>

</body>
</html>
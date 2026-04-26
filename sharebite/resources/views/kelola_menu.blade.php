<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Kelola Menu Makanan</title>
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  body { font-family: 'Inter', sans-serif; }
</style>

</head>
<body class="bg-[#F5F7F6]">

<div class="flex min-h-screen">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-white border-r px-6 py-6 flex flex-col justify-between">
    <div>
      <h1 class="text-xl font-semibold text-green-600 mb-8">🍜 ShareBite</h1>

      <nav class="space-y-2 text-sm">
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">
          📊 Dashboard
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-green-100 text-green-700 font-medium">
          🍽 Kelola Makanan
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">🛒 Pesanan</a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">📝 Riwayat</a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">👤 Profil</a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">⚙️ Pengaturan</a>
      </nav>
    </div>

    <button class="text-red-500 text-sm font-medium">🚪 Logout</button>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 px-8 py-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-green-700">Kelola Menu Makanan</h2>

      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">Arcamanik Hotel</span>
        <div class="w-9 h-9 rounded-full bg-gray-400"></div>
      </div>
    </div>

    <!-- TABS -->
    <div class="flex gap-6 border-b mb-6 text-sm">
      <button class="pb-3 border-b-2 border-green-600 text-green-600 font-medium">
        Menu Aktif
      </button>
      <button class="pb-3 text-gray-400 hover:text-gray-600">Master Data</button>
    </div>

    <!-- SEARCH + BUTTON -->
    <div class="flex justify-between items-center mb-6">
      <input type="text"
        placeholder="Cari pesanan atau menu..."
        class="w-[320px] bg-[#EEF2F1] px-4 py-2 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

      <button class="bg-green-600 hover:bg-green-700 text-white text-sm px-5 py-2 rounded-full flex items-center gap-2 font-medium">
        <span class="text-lg">+</span> Buka Menu
      </button>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-3 gap-4 mb-6">

      <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-lg">✔</div>
        <div>
          <p class="text-xs text-gray-500">Total Menu Aktif</p>
          <p class="font-semibold text-lg">12 <span class="text-sm text-gray-500">Kategori</span></p>
        </div>
      </div>

      <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-lg">✘</div>
        <div>
          <p class="text-xs text-gray-500">Total Menu Habis</p>
          <p class="font-semibold text-lg">4 <span class="text-sm text-gray-500">Menu</span></p>
        </div>
      </div>

      <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-lg">💳</div>
        <div>
          <p class="text-xs text-gray-500">Porsi Terjual Hari Ini</p>
          <p class="font-semibold text-lg">86 <span class="text-sm text-gray-500">Porsi</span></p>
        </div>
      </div>

    </div>

    <!-- FILTER -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex gap-2 text-sm">
        <button class="px-4 py-2 rounded-full bg-green-600 text-white font-medium">Semua</button>
        <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300">Tersedia</button>
        <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300">Segera Habis</button>
        <button class="px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300">Segera Habis</button>
      </div>

      <div class="flex items-center gap-2 text-sm text-gray-500">
        <span>Urutkan: Terbaru</span>
        <span>▼</span>
      </div>
    </div>

    <!-- GRID - 8 CARDS -->
    <div class="grid grid-cols-4 gap-5">

      <!-- CARD 1 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 2 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 3 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 4 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 5 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 6 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 7 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

      <!-- CARD 8 -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
               class="w-full h-40 object-cover">
          <span class="absolute top-3 left-3 bg-green-700 text-white text-[10px] px-3 py-1 rounded-full font-semibold">
            TERSEDIA
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-sm mb-1">Paket Nasi Kotak Nusantara</h3>
          <p class="text-xs text-gray-500 mb-2">
            Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            🍽 15 Porsi
          </div>
          <div class="text-orange-500 font-semibold mb-3 text-sm">
            Rp 12.000
          </div>
          <div class="flex gap-2 text-xs">
            <button class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">✎ Edit</button>
            <button class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">🗑 Tutup</button>
          </div>
        </div>
      </div>

    </div>

  </main>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Profile Alya</title>
</head>
<body class="bg-[#eef5ef] font-sans">

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-lg p-6">
    <h1 class="text-2xl font-bold text-green-600 mb-8">ShareBite</h1>

    <nav class="space-y-3">
      <a class="block text-gray-600 hover:text-green-600 transition">Dashboard</a>
      <a class="block text-gray-600 hover:text-green-600 transition">Riwayat</a>
      <a class="block bg-green-100 text-green-700 px-4 py-2 rounded-xl font-medium">Profile</a>
      <a class="block text-gray-600 hover:text-green-600 transition">Pengaturan</a>
    </nav>

    <div class="absolute bottom-6 text-red-500 cursor-pointer">Logout</div>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-10">

    <h2 class="text-3xl font-bold text-green-700 mb-8">Profile Saya</h2>

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between">
      <div class="flex items-center gap-5">
        <div class="w-20 h-20 bg-green-100 rounded-2xl shadow-inner"></div>
        <div>
          <h3 class="text-xl font-bold">Alya</h3>
          <p class="text-sm text-gray-500">Terdaftar Jan 2023</p>
        </div>
      </div>

      <button class="bg-green-500 hover:bg-green-600 transition text-white px-5 py-2 rounded-full">
        Edit Profile
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-6 mt-6">
      <div class="bg-green-50 p-6 rounded-2xl shadow">
        <p class="text-gray-500 mb-1">Kontribusi Berbagi</p>
        <h3 class="text-3xl font-bold text-green-600">128</h3>
        <span class="text-sm">Item</span>
      </div>

      <div class="bg-green-100 p-6 rounded-2xl shadow">
        <p class="text-gray-500 mb-1">Dampak Ekologis</p>
        <h3 class="text-3xl font-bold text-green-700">42.5</h3>
        <span class="text-sm">Kg Sampah</span>
      </div>
    </div>

    <!-- Info -->
    <div class="grid grid-cols-3 gap-6 mt-6">

      <!-- Informasi -->
      <div class="col-span-2 bg-white p-6 rounded-2xl shadow">
        <h4 class="font-semibold mb-4">Informasi Pribadi</h4>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm text-gray-500">Nama Lengkap</label>
            <input value="Alya" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-green-400 outline-none"/>
          </div>

          <div>
            <label class="text-sm text-gray-500">Alamat Email</label>
            <input value="alya@email.com" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-green-400 outline-none"/>
          </div>

          <div class="col-span-2">
            <label class="text-sm text-gray-500">Nomor Telepon</label>
            <input value="+62 812-3456-7890" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-green-400 outline-none"/>
          </div>
        </div>
      </div>

      <!-- Keamanan -->
      <div class="bg-white p-6 rounded-2xl shadow">
        <h4 class="font-semibold mb-4">Keamanan</h4>
        <ul class="space-y-3">
          <li class="flex justify-between text-gray-600 hover:text-green-600 cursor-pointer">
            Ubah Kata Sandi <span>›</span>
          </li>
          <li class="flex justify-between text-gray-600 hover:text-green-600 cursor-pointer">
            Notifikasi <span>›</span>
          </li>
        </ul>
      </div>

    </div>

    <!-- Address -->
    <div class="grid grid-cols-3 gap-6 mt-6">

      <div class="col-span-2 bg-white p-6 rounded-2xl shadow">
        <h4 class="font-semibold mb-4">Lokasi & Alamat Utama</h4>
        <p class="text-gray-600">Jl. Kebon Sirih No. 123, Jakarta</p>
      </div>

      <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-6 rounded-2xl shadow">
        <h4 class="font-bold mb-2">Butuh Bantuan?</h4>
        <p class="text-sm mb-4">Tim kami siap membantu Anda 24/7</p>
        <button class="bg-white text-green-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">
          Hubungi CS
        </button>
      </div>

    </div>

  </main>
</div>

</body>
</html>
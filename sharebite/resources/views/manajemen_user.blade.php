<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - ShareBite</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-[#F6F8F7] text-gray-800 flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-sm flex flex-col justify-between">

    <div>
        <!-- Logo -->
        <div class="h-[72px] flex items-center px-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#006838] rounded-full"></div>
                <span class="text-lg font-bold text-[#006838]">
                    Share<span class="text-[#D4813E]">Bite</span>
                </span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="px-3 mt-2 space-y-1">

            <a class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 bg-[#EAF3EE] text-[#006838] rounded-xl font-semibold">
                <span class="text-sm">Manajemen Pengguna</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                <span class="text-sm font-medium">Chat</span>
            </a>

        </nav>
    </div>

    <!-- Logout -->
    <div class="p-4">
        <a class="flex items-center gap-2 px-4 py-3 text-red-600 rounded-xl hover:bg-red-50">
            <span class="font-semibold text-sm">Logout</span>
        </a>
    </div>

</aside>


<!-- MAIN -->
<main class="flex-1 overflow-y-auto">
<div class="max-w-7xl mx-auto px-10 py-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-[#006838]">Manajemen Pengguna</h1>

        <div class="flex gap-3">
            <div class="w-10 h-10 bg-white rounded-full shadow-sm"></div>
            <div class="w-10 h-10 bg-white rounded-full shadow-sm"></div>
        </div>
    </div>

    <!-- TABS -->
    <div class="inline-flex bg-[#EAF3EE] rounded-full p-1 mb-8">
        <button class="px-6 py-2 bg-white text-[#006838] rounded-full font-semibold text-sm shadow-sm">
            Unit Bisnis
        </button>
        <button class="px-6 py-2 text-gray-500 text-sm">Komunitas / Individu</button>
        <button class="px-6 py-2 text-gray-500 text-sm">Verifikasi NIB</button>
    </div>

    <!-- STAT -->
    <div class="grid grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 rounded-3xl flex gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)]">
            <div class="w-12 h-12 bg-[#EAF3EE] rounded-xl"></div>
            <div>
                <p class="text-sm text-gray-500">Total Unit Bisnis</p>
                <h3 class="text-2xl font-bold">1,248</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl flex gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)]">
            <div class="w-12 h-12 bg-[#FFF4E5] rounded-xl"></div>
            <div>
                <p class="text-sm text-gray-500">Pending Verifikasi</p>
                <h3 class="text-2xl font-bold">42</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl flex gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.04)]">
            <div class="w-12 h-12 bg-[#F0F2FB] rounded-xl"></div>
            <div>
                <p class="text-sm text-gray-500">Aktif Komunitas</p>
                <h3 class="text-2xl font-bold">315</h3>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-sm mb-8">

        <div class="p-8 pb-5">
            <h2 class="text-xl font-bold">Daftar Unit Bisnis</h2>
            <p class="text-sm text-gray-500">Kelola restoran, hotel, dan cafe.</p>
        </div>

        <table class="w-full text-left">
            <thead class="bg-[#F6F8F7] text-xs text-gray-500">
                <tr>
                    <th class="px-8 py-4">Nama</th>
                    <th>Tipe</th>
                    <th>Email</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-right px-8">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                <tr class="hover:bg-gray-50">
                    <td class="px-8 py-6 flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#EAF3EE] rounded-xl flex items-center justify-center text-[#006838] font-bold">
                            BS
                        </div>
                        <span class="font-semibold">Bakery Seroja</span>
                    </td>

                    <td><span class="bg-gray-100 px-3 py-1 rounded-full text-xs">Restoran</span></td>
                    <td>contact@seroja.id</td>
                    <td>12 Okt 2023</td>

                    <td>
                        <span class="bg-[#EAF7F0] text-[#0A8F4E] px-3 py-1 rounded-full text-xs font-semibold">
                            Verified
                        </span>
                    </td>

                    <td class="text-right px-8">✏️ 🗑️</td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-8 py-6 flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#EAF3EE] rounded-xl flex items-center justify-center text-[#006838] font-bold">
                            GH
                        </div>
                        <span class="font-semibold">Grand Hyatt JKT</span>
                    </td>

                    <td><span class="bg-gray-100 px-3 py-1 rounded-full text-xs">Hotel</span></td>
                    <td>fnb.admin@hyatt.com</td>
                    <td>15 Nov 2023</td>

                    <td>
                        <span class="bg-[#FFF4E5] text-[#D07F20] px-3 py-1 rounded-full text-xs font-semibold">
                            Pending
                        </span>
                    </td>

                    <td class="text-right px-8">✏️ 🗑️</td>
                </tr>

            </tbody>
        </table>

    </div>

</div>
</main>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div 
    x-data="{ tab: 'Komunitas', showPassword: false }"
    class="min-h-screen flex flex-col md:flex-row"
>

    <!-- LEFT -->
    <div class="w-full md:w-1/3 bg-green-700 text-white p-8 md:p-16 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-12">
                <div class="bg-white p-2 rounded-full">
                    <span class="text-green-700 font-bold">SB</span>
                </div>
                <span class="text-xl font-semibold">ShareBite</span>
            </div>

            <h1 class="text-4xl font-bold leading-tight mb-6">
                Bergabunglah sebagai <span x-text="tab"></span>
            </h1>

            <p class="text-green-100 text-lg">
                Mari bersama kurangi limbah pangan dan bantu sesama.
                Langkah kecil Anda hari ini adalah dampak besar bagi kelestarian pangan esok hari.
            </p>
        </div>

        <div class="bg-green-800 p-4 rounded-xl mt-12 flex items-center gap-4">
            <span>👥</span>
            <div>
                <p class="text-sm text-green-200">Dampak Kolektif</p>
                <p class="font-bold text-lg">500+ Komunitas Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-full md:w-2/3 bg-white p-8 md:p-16 overflow-y-auto">

        <!-- TAB -->
        <div class="flex bg-gray-100 p-1 rounded-lg mb-8 max-w-lg">
            <template x-for="item in ['Unit Bisnis', 'Komunitas', 'Individu']" :key="item">
                <button 
                    @click="tab = item"
                    :class="tab === item 
                        ? 'bg-green-500 text-white shadow-sm' 
                        : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2 text-sm font-medium rounded-md transition-all"
                    x-text="item"
                ></button>
            </template>
        </div>

        <!-- HEADER -->
        <div class="flex items-center gap-3 mb-6">
            <span class="text-green-600">👤</span>
            <h2 class="text-xl font-bold text-gray-800">
                Identitas <span x-text="tab"></span>
            </h2>
        </div>

        <!-- FORM -->
        <form method="POST" action="#" class="space-y-5 max-w-2xl">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama
                </label>
                <input type="text"
                    :placeholder="
                        tab === 'Komunitas' ? 'Komunitas Hijau Lestari' :
                        tab === 'Unit Bisnis' ? 'PT Maju Jaya' :
                        'Nama Anda'
                    "
                    class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
            </div>

            <!-- CONDITIONAL -->
            <div x-show="tab === 'Komunitas'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <input type="text" placeholder="Penanggung Jawab" class="p-3 rounded-lg border bg-gray-50">
                <input type="number" placeholder="Jumlah Anggota" class="p-3 rounded-lg border bg-gray-50">
            </div>

            <div x-show="tab === 'Individu'">
                <input type="text" placeholder="Nama Lengkap" class="w-full p-3 rounded-lg border bg-gray-50">
            </div>

            <div x-show="tab === 'Unit Bisnis'">
                <input type="text" placeholder="Nama Perusahaan" class="w-full p-3 rounded-lg border bg-gray-50">
            </div>

            <!-- Kontak -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <input type="text" placeholder="Nomor HP" class="p-3 rounded-lg border bg-gray-50">
                <input type="email" placeholder="Email" class="p-3 rounded-lg border bg-gray-50">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full p-3 rounded-lg border bg-gray-50"
                    >
                    <span 
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-3 cursor-pointer"
                    >
                        👁
                    </span>
                </div>
            </div>

            <!-- Checkbox -->
            <div class="flex items-start gap-3">
                <input type="checkbox" class="mt-1">
                <p class="text-sm text-gray-600">
                    Saya setuju dengan syarat dan ketentuan ShareBite
                </p>
            </div>

            <!-- BUTTON -->
            <button class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                Daftar sebagai <span x-text="tab"></span>
            </button>

        </form>
    </div>

</div>

</body>
</html>
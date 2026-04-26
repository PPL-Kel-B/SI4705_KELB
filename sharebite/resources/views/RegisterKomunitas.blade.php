<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Komunitas</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans">

<div x-data="{ showPassword: false }" class="min-h-screen flex flex-col md:flex-row">

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
                Bergabunglah sebagai <span class="text-orange-500">Komunitas</span>
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
            <button disabled class="flex-1 py-2 text-sm text-gray-400 opacity-50">
                Unit Bisnis
            </button>
            <button class="flex-1 py-2 text-sm bg-green-500 text-white rounded-md">
                Komunitas
            </button>
            <button disabled class="flex-1 py-2 text-sm text-gray-400 opacity-50">
                Individu
            </button>
        </div>

        <!-- HEADER -->
        <div class="flex items-center gap-3 mb-6">
            <span class="text-green-600">👤</span>
            <h2 class="text-xl font-bold text-gray-800">
                Identitas Komunitas
            </h2>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-5 max-w-2xl">
        @csrf

            <!-- Nama Komunitas -->
            <div>
                <label class="block text-sm font-medium mb-2">
                    Nama Komunitas
                </label>
                <input type="text" name="nama_komunitas" required
                    placeholder="Nama Komunitas"
                    class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Penanggung Jawab + Jumlah -->
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-2">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" required
                        placeholder="Nama Penanggung Jawab"
                        class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Jumlah Anggota</label>
                    <input type="number" name="jumlah_anggota" required min="1"
                        placeholder="Jumlah Anggota"
                        class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <!-- HP + Email -->
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-2">Nomor HP</label>
                    <input type="tel" name="no_hp" required
                        placeholder="081234567890"
                        class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" required
                        placeholder="komunitas@gmail.com"
                        class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium mb-2">Kata Sandi</label>
                <div class="relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        required
                        minlength="6"
                        placeholder="Minimal 6 karakter"
                        class="w-full p-3 rounded-lg border bg-gray-50 focus:ring-2 focus:ring-green-500"
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
                <input type="checkbox" required class="mt-1">
                <p class="text-sm text-gray-600">
                    Saya setuju dengan 
                    <span class="font-medium text-gray-800">
                        syarat dan ketentuan
                    </span>
                </p>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
                Daftar Komunitas
            </button>

            <!-- LOGIN -->
            <div class="text-center pt-4">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="/login" class="text-green-600 font-medium">
                        Login
                    </a>
                </p>
            </div>

        </form>

    </div>

</div>

</body>
</html>
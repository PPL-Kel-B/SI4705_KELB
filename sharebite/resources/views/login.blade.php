<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShareBite</title>

    <!-- Font (biar mirip design modern) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="h-screen bg-[#f5f7f6]">

<div class="flex h-full">

    <!-- LEFT -->
    <div class="hidden lg:flex w-1/2 relative overflow-hidden">
        <!-- Gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#d9efe3] via-[#9fe3b9] to-[#e5e7eb]"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center px-20">
            <h1 class="text-[56px] font-bold leading-tight text-gray-900">
                Selamatkan <br>
                makanan,
                <span class="text-green-500">sebarkan <br> kebaikan.</span>
            </h1>

            <p class="mt-6 text-gray-600 text-lg max-w-md leading-relaxed">
                Bergabunglah dengan ribuan penyelamat makanan untuk
                mengurangi food waste dan membantu sesama, satu porsi
                dalam satu waktu.
            </p>

            <!-- Logo -->
            <div class="absolute bottom-10 left-20 flex items-center gap-2 text-green-500 font-semibold text-lg">
                <span class="text-2xl">🍽️</span> ShareBite
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#f8faf9] px-8">
        <div class="w-full max-w-md">

            <h2 class="text-4xl font-semibold text-gray-800">
                Selamat Datang
            </h2>
            <p class="text-gray-500 mt-2">
                Silakan masuk ke akun Anda
            </p>

            <form class="mt-10 space-y-8">

                <!-- EMAIL -->
                <div>
                    <label class="text-xs tracking-widest text-gray-400 uppercase">
                        Email
                    </label>
                    <input type="email" placeholder="nama@email.com"
                        class="w-full border-b border-gray-300 bg-transparent focus:outline-none focus:border-gray-600 py-3 mt-2 text-gray-700 placeholder-gray-300">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-xs tracking-widest text-gray-400 uppercase">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password"
                            class="w-full border-b border-gray-300 bg-transparent focus:outline-none focus:border-gray-600 py-3 mt-2">
                        <span class="absolute right-0 top-4 text-gray-400 cursor-pointer text-lg">
                            👁
                        </span>
                    </div>
                </div>

                <!-- OPTIONS -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-400">
                        <input type="checkbox" class="accent-gray-400">
                        Ingat Saya
                    </label>

                    <a href="#" class="text-green-500 font-medium hover:underline">
                        Lupa Sandi?
                    </a>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-[#34c36b] hover:bg-[#2fb862] text-white py-4 rounded-full font-semibold text-lg shadow-lg shadow-green-200 transition">
                    Masuk Sekarang
                </button>

                <!-- REGISTER -->
                <p class="text-center text-gray-500 text-sm">
                    Baru di ShareBite?
                    <a href="#" class="text-green-500 font-semibold hover:underline">
                        Buat Akun Baru
                    </a>
                </p>

            </form>

        </div>
    </div>

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShareBite</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen bg-[#f5f7f6]">

<div class="flex min-h-screen">

    <!-- LEFT -->
    <div class="hidden lg:flex w-1/2 relative overflow-hidden">

        <!-- Gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#ecfdf5] via-[#bbf7d0] to-[#fef9c3]"></div>

        <!-- Decorative blobs -->
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-green-300 opacity-40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-yellow-200 opacity-30 rounded-full blur-3xl"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-between h-full px-20 py-20">

            <!-- Text -->
            <div class="flex-1 flex items-center">
                <div class="max-w-md">
                    <h1 class="text-[56px] xl:text-[64px] font-extrabold leading-tight text-gray-900">
                        Selamatkan <br>
                        makanan,
                        <span class="text-green-500">sebarkan <br> kebaikan.</span>
                    </h1>

                    <p class="mt-6 text-gray-600 text-lg leading-relaxed">
                        Bergabunglah dengan ribuan penyelamat makanan untuk
                        mengurangi food waste dan membantu sesama,
                        satu porsi dalam satu waktu.
                    </p>
                </div>
            </div>

            <!-- Logo -->
            <div class="flex items-center gap-2 text-lg font-semibold">
                <span class="text-2xl">🍽️</span>
                <span class="text-gray-900">
                    Share<span class="text-green-500">Bite</span>
                </span>
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#fafafa] px-8 relative">

        <!-- subtle blob -->
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-yellow-200 opacity-30 rounded-full blur-3xl"></div>

        <div class="w-full max-w-md relative z-10">

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
                        class="w-full border-b-2 border-gray-300 bg-transparent focus:outline-none focus:border-green-500 py-3 mt-2 text-gray-700 placeholder-gray-300 transition">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-xs tracking-widest text-gray-400 uppercase">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input id="password" type="password"
                            class="w-full border-b-2 border-gray-300 bg-transparent focus:outline-none focus:border-green-500 py-3 mt-2 pr-10 transition">

                        <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-500 text-lg">
                            <span id="eye-open">👁</span>
                            <span id="eye-close" class="hidden">🙈</span>
                        </button>
                    </div>
                </div>

                <!-- OPTIONS -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-500">
                        <input type="checkbox" class="accent-green-500">
                        Ingat Saya
                    </label>

                    <a href="#" class="text-green-500 font-medium hover:underline">
                        Lupa Sandi?
                    </a>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 hover:shadow-xl active:scale-[0.98] text-white py-4 rounded-full font-semibold text-lg shadow-lg shadow-green-200 transition-all duration-200">
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

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const open = document.getElementById('eye-open');
    const close = document.getElementById('eye-close');

    if (input.type === 'password') {
        input.type = 'text';
        open.classList.add('hidden');
        close.classList.remove('hidden');
    } else {
        input.type = 'password';
        open.classList.remove('hidden');
        close.classList.add('hidden');
    }
}
</script>

</body>
</html>
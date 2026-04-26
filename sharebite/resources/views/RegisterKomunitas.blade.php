<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Komunitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- LEFT -->
    <div class="w-[35%] bg-gradient-to-b from-green-700 to-green-400 text-white p-16 flex flex-col justify-between">
        
        <div>
            <div class="bg-white px-4 py-2 rounded-full w-fit flex items-center gap-2 shadow-sm border border-gray-100">
                <img src="{{ asset('images/ShareBite.png') }}" alt="Logo ShareBite" class="h-6 w-auto">
            </div>

            <h1 class="text-5xl font-bold mt-10 leading-tight">
            Bergabunglah <br> sebagai <br> <span class="text-[#FFDDB9]">Komunitas</span>
            </h1>

            <p class="mt-6 text-lg text-green-100">
                Mari bersama kurangi limbah pangan dan bantu sesama.
                Langkah kecil Anda hari ini adalah dampak besar bagi
                kelestarian pangan esok hari.
            </p>
        </div>

        <div class="flex items-center gap-4 bg-white/20 backdrop-blur-md border border-white/20 p-4 rounded-[1.5rem] w-fit shadow-sm">
    
    <div class="bg-white/20 p-3 rounded-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-white">
            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
            <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
        </svg>
    </div>

        <div class="flex flex-col justify-center">
            <p class="text-white/80 text-xs md:text-sm font-medium leading-tight">
            Dampak Kolektif
            </p>
            <p class="text-white font-bold text-lg md:text-xl leading-tight">
            500+ Komunitas Terdaftar
            </p>
        </div>
    </div>

    </div>

    <!-- RIGHT (70%) -->
<div class="w-[70%] flex items-center justify-center bg-white rounded-xl shadow-lg p-12">

    <div class="w-[520px]">

        <!-- TAB -->
        <div class="bg-[#e5ebe8] p-1 rounded-lg flex mb-8">
            <button class="flex-1 py-2 text-black-500 text-sm">Unit Bisnis</button>
            <button class="flex-1 py-2 bg-green-500 text-black rounded-lg text-sm font-semibold">
                Komunitas
            </button>
            <button class="flex-1 py-2 text-black-500 text-sm">Individu</button>
        </div>

        <!-- TITLE -->
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-green-100 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-800">
                    <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                    <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">
                Identitas Komunitas
            </h2>
        </div>

        <!-- FORM -->
        <form class="space-y-5" action="{{ route('registerkomunitas.store') }}" method="POST">
            @csrf

            @if(session('success'))
                <div class="rounded-xl bg-green-100 border border-green-200 p-4 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Nama Komunitas -->
            <div>
                <label class="text-sm text-gray-700">Nama Komunitas/Akun</label>
                <input name="nama_komunitas" type="text"
                    value="{{ old('nama_komunitas') }}"
                    placeholder="Contoh: Komunitas Hijau Lestari"
                    class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('nama_komunitas') ? 'border border-red-500 bg-red-50' : '' }}">
                @error('nama_komunitas')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2 COL -->
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="text-sm text-gray-700">Nama Penanggung Jawab</label>
                    <input name="penanggung_jawab" type="text"
                        value="{{ old('penanggung_jawab') }}"
                        placeholder="Nama lengkap sesuai KTP"
                        class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('penanggung_jawab') ? 'border border-red-500 bg-red-50' : '' }}">
                    @error('penanggung_jawab')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2">
                    <label class="text-sm text-gray-700">Jumlah Anggota</label>
                    <input name="jumlah_anggota" type="text"
                        value="{{ old('jumlah_anggota') }}"
                        placeholder="Contoh: 50"
                        class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('jumlah_anggota') ? 'border border-red-500 bg-red-50' : '' }}">
                    @error('jumlah_anggota')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 2 COL -->
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="text-sm text-gray-700">Nomor HP</label>
                    <input name="no_hp" type="text"
                        value="{{ old('no_hp') }}"
                        placeholder="0812-XXXX-XXXX"
                        class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('no_hp') ? 'border border-red-500 bg-red-50' : '' }}">
                    @error('no_hp')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2">
                    <label class="text-sm text-gray-700">Email</label>
                    <input name="email" type="email"
                        value="{{ old('email') }}"
                        placeholder="komunitas@email.com"
                        class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('email') ? 'border border-red-500 bg-red-50' : '' }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm text-gray-700">Kata Sandi</label>
                <div class="relative mt-2">
                    <input id="password" name="password" type="password"
                        placeholder="••••••••"
                        class="w-full p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('password') ? 'border border-red-500 bg-red-50' : '' }}">

                    <button id="togglePassword" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 focus:outline-none">
                        <svg id="passwordIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- CHECKBOX -->
            <div class="bg-[#e9eeeb] p-4 rounded-xl flex gap-3 text-sm text-gray-600 items-start">
                <input id="agreementCheckbox" name="agreement" type="checkbox" class="mt-1" required>
                <p>
                    Saya bersedia mengikuti regulasi dan peraturan yang ada serta berkomitmen pada standar keamanan pangan 
                    <span class="text-green-600 font-semibold">ShareBite</span> secara konsisten demi keselamatan penerima manfaat.
                </p>
            </div>

            <!-- BUTTON -->
            <button id="submitButton" type="submit" disabled class="w-full bg-gradient-to-r from-green-400 to-green-500 text-white py-4 rounded-xl font-semibold shadow-md hover:scale-[1.01] transition disabled:cursor-not-allowed disabled:opacity-60">
                Daftar Sebagai Komunitas
            </button>

            <!-- LOGIN -->
            <p class="text-center text-sm text-gray-500">
                Sudah memiliki akun mitra?
                <a href="#" class="text-green-600 font-medium">Masuk ke Dashboard</a>
            </p>

        </form>
    </div>

    </div>

</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const passwordIcon = document.getElementById('passwordIcon');
    const agreementCheckbox = document.getElementById('agreementCheckbox');
    const submitButton = document.getElementById('submitButton');

    if (passwordInput && togglePassword && passwordIcon) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            passwordIcon.innerHTML = isPassword
                ? '<path d="M1 1l22 22" />\n<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8 1.73-3.03 4.51-5.35 8.06-6.39" />\n<path d="M9.53 9.53a3.5 3.5 0 0 0 4.94 4.94" />\n<path d="M14.47 14.47A3.5 3.5 0 0 1 9.53 9.53" />'
                : '<path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z" />\n<circle cx="12" cy="12" r="3" />';
        });
    }

    if (agreementCheckbox && submitButton) {
        function updateSubmitState() {
            submitButton.disabled = !agreementCheckbox.checked;
        }

        agreementCheckbox.addEventListener('change', updateSubmitState);
        updateSubmitState();
    }
</script>
</html>

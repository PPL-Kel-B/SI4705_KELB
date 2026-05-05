<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Pendaftaran Komunitas - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/firebase-otp.js'])

    <style>
        /* Menghilangkan icon mata bawaan browser (Edge/Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>

<body class="bg-white">
    <!-- RECAPTCHA CONTAINER -->
    <div id="recaptcha-container"></div>

    <!-- OTP SECTION (Hidden by default) -->
    <div id="otp-section" class="hidden fixed inset-0 bg-[#f7fbf8] z-50 flex flex-col">
        <!-- Header -->
        <div class="p-6 flex items-center justify-between">
            <div class="bg-white rounded-full w-36 h-16 flex items-center justify-center shadow-sm overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="ShareBite Logo" class="h-24 w-auto object-contain"
                    onerror="this.outerHTML='<span class=\'text-[#1cb764] font-bold text-lg\'>ShareBite</span>'">
            </div>
            <button type="button" id="btn-back-to-number"
                class="flex items-center text-gray-600 hover:text-gray-900 font-medium text-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke pengaturan nomor
            </button>
        </div>

        <!-- OTP Card -->
        <div class="flex-1 flex flex-col items-center justify-center px-4 pb-20">
            <div class="bg-white p-10 rounded-[2rem] shadow-sm w-full max-w-md text-center">
                <!-- Icon -->
                <div class="bg-[#e9eeeb] w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                </div>

                <h2 class="text-2xl font-extrabold text-[#1cb764] mb-3">Verifikasi Nomor HP</h2>
                <p class="text-sm text-gray-500 mb-8 px-4">
                    Masukkan kode OTP yang dikirimkan ke nomor Anda untuk melanjutkan langkah keamanan.
                </p>

                <!-- 6 OTP Inputs for Firebase -->
                <div class="flex justify-center gap-2 mb-6" id="otp-inputs">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                    <input type="text" maxlength="1"
                        class="w-12 h-14 text-center text-xl font-bold bg-[#e9eeeb] rounded-xl outline-none focus:ring-2 focus:ring-[#1cb764] transition">
                </div>

                <div class="text-sm text-gray-600 font-medium mb-8 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kirim Ulang Kode dalam <span id="otp-timer" class="text-[#1cb764] font-bold">01:59</span>
                </div>

                <button type="button" id="btn-confirm-otp"
                    class="w-full bg-[#22c55e] hover:bg-[#1cb764] text-white py-4 rounded-xl font-bold shadow-md hover:scale-[1.01] transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Konfirmasi
                </button>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-2">Punya Masalah?</p>
                    <a href="#"
                        class="text-sm font-bold text-[#b47a32] hover:underline flex items-center justify-center gap-1">
                        Butuh bantuan? Hubungi CS
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-8 text-sm text-gray-500 font-medium">
                Tidak menerima kode? <button type="button" id="btn-resend-otp"
                    class="text-[#1cb764] font-bold hover:underline disabled:opacity-50" disabled>Kirim Ulang</button>
            </div>
        </div>
    </div>

    <!-- REGISTRATION SECTION -->
    <div id="registration-section" class="flex min-h-screen bg-white">
        <div
            class="hidden lg:flex lg:w-4/12 bg-[#1cb764] flex-col justify-between px-10 py-10 text-white relative overflow-hidden">

            <div class="z-10">
                <div class="bg-white rounded-full w-36 h-16 flex items-center justify-center shadow-sm overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="ShareBite Logo" class="h-24 w-auto object-contain"
                        onerror="this.outerHTML='<span class=\'text-[#1cb764] font-bold text-lg\'>ShareBite</span>'">
                </div>
            </div>

            <div class="mt-0 z-5">
                <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                    Bergabunglah <br> sebagai <br> <span class="text-[#f7b055]">Komunitas</span>
                </h1>
                <p class="text-base text-white/90 font-medium mb-2">
                    Mari bersama kurangi limbah pangan dan bantu sesama. Langkah kecil Anda hari ini adalah dampak besar
                    bagi kelestarian pangan esok hari.
                </p>
            </div>

            <div class="mt-8 space-y-4 z-10 max-w-sm">
                <div class="bg-white/10 rounded-2xl p-4 flex items-center space-x-5 shadow-sm">
                    <div class="bg-white/20 p-3.5 rounded-2xl flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-7 h-7 text-white">
                            <path fill-rule="evenodd"
                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                clip-rule="evenodd" />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] font-semibold text-white">Dampak Kolektif</div>
                        <div class="text-[15px] font-bold tracking-widest text-white">{{ $totalKomunitas }} Komunitas
                            Terdaftar</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="w-full lg:w-8/12 flex justify-center py-8 px-6 sm:px-12 overflow-y-auto">
            <div class="w-full max-w-2xl">

                <div
                    class="flex space-x-2 bg-[#EBF0EE] p-1.5 rounded-xl mb-8 text-sm font-medium text-center shadow-inner">
                    <a href="{{ route('unit-bisnis.create') }}"
                        class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Unit
                        Bisnis</a>
                    <a href="{{ route('registerkomunitas') }}"
                        class="flex-1 bg-[#1cb764] text-white py-2.5 rounded-lg shadow-sm cursor-default block">Komunitas</a>
                    <a href="{{ route('individu.create') }}"
                        class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Individu</a>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 text-green-800">
                            <path fill-rule="evenodd"
                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                clip-rule="evenodd" />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Identitas Komunitas
                    </h2>
                </div>

                <form class="space-y-5" action="{{ route('registerkomunitas.store') }}" method="POST" id="registForm">
                    @csrf

                    @if(session('success'))
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registrasi Komunitas Berhasil',
                                    confirmButtonColor: '#1cb764',
                                    confirmButtonText: 'Lanjutkan'
                                });
                            });
                        </script>
                    @endif

                    <div>
                        <label class="text-sm text-gray-700">Nama Komunitas/Akun</label>
                        <input name="nama_komunitas" type="text" value="{{ old('nama_komunitas') }}"
                            placeholder="Contoh: Komunitas Hijau Lestari" required
                            class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('nama_komunitas') ? 'border border-red-500 bg-red-50' : '' }}">
                        @error('nama_komunitas')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="text-sm text-gray-700">Nama Penanggung Jawab</label>
                            <input name="penanggung_jawab" type="text" value="{{ old('penanggung_jawab') }}"
                                placeholder="Nama lengkap sesuai KTP" required
                                class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('penanggung_jawab') ? 'border border-red-500 bg-red-50' : '' }}">
                        </div>

                        <div class="w-1/2">
                            <label class="text-sm text-gray-700">Jumlah Anggota</label>
                            <input name="jumlah_anggota" type="text" value="{{ old('jumlah_anggota') }}"
                                placeholder="Contoh: 50" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('jumlah_anggota') ? 'border border-red-500 bg-red-50' : '' }}">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="text-sm text-gray-700">Nomor HP</label>
                            <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp') }}"
                                placeholder="0812-XXXX-XXXX" inputmode="numeric" pattern="^(\+62|0)[0-9]{9,13}$"
                                maxlength="15" required
                                class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('no_hp') ? 'border border-red-500 bg-red-50' : '' }}">
                            <p id="err-hp" class="mt-1 text-xs text-red-500 hidden">Nomor HP hanya boleh angka (10–14
                                digit), contoh: 08123456789.</p>
                        </div>

                        <div class="w-1/2">
                            <label class="text-sm text-gray-700">Email</label>
                            <input name="email" type="email" value="{{ old('email') }}"
                                placeholder="komunitas@email.com" required
                                class="w-full mt-2 p-4 rounded-xl bg-[#e9eeeb] outline-none text-sm {{ $errors->has('email') ? 'border border-red-500 bg-red-50' : '' }}">
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="Password" name="password" placeholder="••••••••" required
                                class="w-full p-4 pr-12 rounded-xl bg-[#e9eeeb] outline-none text-sm focus:ring-2 focus:ring-[#1cb764] transition {{ $errors->has('password') ? 'border border-red-500 bg-red-50' : '' }}">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1cb764] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <ul class="text-xs space-y-1">
                                <li id="req-cap" class="flex items-center text-red-500 transition-colors">
                                    <svg class="w-3 h-3 mr-1.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Minimal 1 huruf kapital
                                </li>
                                <li id="req-num" class="flex items-center text-red-500 transition-colors">
                                    <svg class="w-3 h-3 mr-1.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Karakter unik atau nomor
                                </li>
                                <li id="req-len" class="flex items-center text-red-500 transition-colors">
                                    <svg class="w-3 h-3 mr-1.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Minimal 8 karakter
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-[#e9eeeb] p-4 rounded-xl flex gap-3 text-sm text-gray-600 items-start">
                        <input id="agreementCheckbox" name="agreement" type="checkbox" class="mt-1" required>
                        <p>
                            Saya bersedia mengikuti regulasi dan peraturan yang ada serta berkomitmen pada standar
                            keamanan pangan
                            <span class="text-green-600 font-semibold">ShareBite</span> secara konsisten demi
                            keselamatan penerima manfaat.
                        </p>
                    </div>

                    <button id="submitButton" type="submit" disabled
                        class="w-full bg-gradient-to-r from-green-400 to-green-500 text-white py-4 rounded-xl font-extrabold shadow-lg transition duration-300 opacity-50 cursor-not-allowed">
                        Daftar Sebagai Komunitas
                    </button>

                    <p class="text-center text-sm text-gray-500">
                        Sudah memiliki akun ?
                        <a href="{{ route('login') }}" class="text-[#1cb764] font-bold hover:underline">Masuk Ke
                            Dashboard</a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById('Password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const agreementCheckbox = document.getElementById('agreementCheckbox');
            const submitButton = document.getElementById('submitButton');
            const registForm = document.getElementById('registForm');
            const allRequiredInputs = registForm.querySelectorAll('input[required]');

            // Deklarasikan status validasi password di awal
            let isPasswordValid = false;

            // Toggle Password Visibility
            if (togglePassword && eyeIcon && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.972 9.972 0 013.29-1.56m0 0L12 5.5l1.42 1.42m0 0A3 3 0 0115 12"></path>`;
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
                    }
                });
            }

            // Real-time validation DOM elements
            const reqCap = document.getElementById('req-cap');
            const reqNum = document.getElementById('req-num');
            const reqLen = document.getElementById('req-len');

            const validIcon = `<svg class="w-3 h-3 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            const invalidIcon = `<svg class="w-3 h-3 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;

            function updateRequirement(el, isValid, text) {
                if (isValid) {
                    el.classList.remove('text-red-500');
                    el.classList.add('text-green-500');
                    el.innerHTML = validIcon + text;
                } else {
                    el.classList.remove('text-green-500');
                    el.classList.add('text-red-500');
                    el.innerHTML = invalidIcon + text;
                }
            }

            // Phone validation
            const phoneInput = document.getElementById('no_hp');
            const errHp = document.getElementById('err-hp');
            let isPhoneValid = false;
            const phoneRegex = /^(\+62|0)[0-9]{9,13}$/;

            phoneInput.addEventListener('keypress', function (e) {
                if (!/[0-9+]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
                    e.preventDefault();
                }
            });

            phoneInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9+]/g, '');
                isPhoneValid = phoneRegex.test(this.value);
                if (this.value.length > 0 && !isPhoneValid) {
                    errHp.classList.remove('hidden');
                } else {
                    errHp.classList.add('hidden');
                }
                checkFormValidity();
            });

            // Fungsi utama pengecekan keseluruhan form
            function checkFormValidity() {
                let allInputsFilled = true;

                // Cek semua input yang required
                allRequiredInputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        if (!input.checked) allInputsFilled = false;
                    } else {
                        if (input.value.trim() === '') allInputsFilled = false;
                    }
                });

                // Aktifkan button HANYA jika password valid, HP valid, dan semua field terisi/tercentang
                if (allInputsFilled && isPasswordValid && isPhoneValid) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:scale-[1.01]', 'active:scale-95');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:scale-[1.01]', 'active:scale-95');
                }
            }

            // Pengecekan Password
            if (passwordInput) {
                passwordInput.addEventListener('input', function (e) {
                    let val = e.target.value;

                    const hasCapital = /[A-Z]/.test(val);
                    const hasNumOrUnique = /[\d\W_]/.test(val);
                    const isLengthValid = val.length >= 8;

                    updateRequirement(reqCap, hasCapital, 'Minimal 1 huruf kapital');
                    updateRequirement(reqNum, hasNumOrUnique, 'Karakter unik atau nomor');
                    updateRequirement(reqLen, isLengthValid, 'Minimal 8 karakter');

                    isPasswordValid = hasCapital && hasNumOrUnique && isLengthValid;
                    checkFormValidity();
                });
            }

            // Pasang event listener ke semua input required (kecuali no_hp, sudah ditangani)
            allRequiredInputs.forEach(input => {
                if (input.id === 'no_hp') return;
                const eventType = input.type === 'checkbox' ? 'change' : 'input';
                input.addEventListener(eventType, checkFormValidity);
            });

            // Jalankan pengecekan pertama kali
            checkFormValidity();
        });
    </script>
</body>

</html>
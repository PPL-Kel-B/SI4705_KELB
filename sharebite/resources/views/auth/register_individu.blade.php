<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Relawan (Individu) - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Menghilangkan icon mata bawaan browser (Edge/Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>

<body class="bg-white">

    <div class="flex min-h-screen bg-white">
        <!-- Left Column: Branding -->
        <div class="hidden lg:flex lg:w-4/12 bg-[#1cb764] flex-col justify-between px-10 py-10 text-white relative overflow-hidden">

            <!-- Logo -->
            <div class="z-10">
                <div class="bg-white rounded-full px-4 py-1.5 inline-flex items-center shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="ShareBite Logo" class="h-14 object-contain"
                        onerror="this.outerHTML='<span class=\'text-[#1cb764] font-bold text-lg\'>ShareBite</span>'">
                </div>
            </div>

            <!-- Main Copy -->
            <div class="mt-8 z-10">
                <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                    Bergabunglah <br> sebagai <br> <span class="text-[#f7b055]">Relawan</span>
                </h1>
                <p class="text-base text-white/90 font-medium mb-3">
                    Mari bersama kurangi limbah pangan dan bantu sesama. Langkah kecil Anda hari ini adalah dampak besar
                    bagi kelestarian pangan esok hari.
                </p>
            </div>

            <!-- Stat Cards -->
            <div class="mt-8 space-y-4 z-10 max-w-sm">
                <div class="bg-white/10 rounded-2xl p-4 flex items-center space-x-5 shadow-sm">
                    <div class="bg-white/20 p-3.5 rounded-2xl flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold tracking-widest text-white mt-0.5">Sudah Bergabung</div>
                        <div class="text-2xl font-extrabold text-white">1,200+ Relawan Aktif</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-10 flex items-start space-x-2 text-xs text-white/80 z-10 font-medium opacity-0">
                <!-- Spacer for layout balance -->
            </div>
        </div>

        <!-- Right Column: Form -->
        <div class="w-full lg:w-8/12 flex justify-center py-8 px-6 sm:px-12 overflow-y-auto">
            <div class="w-full max-w-2xl">

                <!-- Tabs -->
                <div class="flex space-x-2 bg-[#EBF0EE] p-1.5 rounded-xl mb-8 text-sm font-medium text-center shadow-inner">
                    <a href="{{ route('unit-bisnis.create') }}" class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Unit Bisnis</a>
                    <a href="{{ route('registerkomunitas') }}" class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Komunitas</a>
                    <a href="{{ route('individu.create') }}" class="flex-1 bg-[#1cb764] text-white py-2.5 rounded-lg shadow-sm cursor-default block">Individu</a>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Identitas Relawan
                    </h2>
                </div>

                <form class="space-y-5" action="{{ route('individu.store') }}" method="POST" id="registForm">
                    @csrf

                    @if(session('success'))
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registrasi Berhasil',
                                    text: '{{ session("success") }}',
                                    confirmButtonColor: '#1cb764',
                                    confirmButtonText: 'Login Sekarang'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('login') }}";
                                    }
                                });
                            });
                        </script>
                    @endif

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <input name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}"
                            placeholder="Nama Lengkap sesuai KTP" required
                            class="w-full mt-2 p-4 rounded-xl bg-[#EBF0EE] border-none outline-none text-sm focus:ring-2 focus:ring-[#1cb764] transition {{ $errors->has('nama_lengkap') ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                        @error('nama_lengkap')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Nomor HP -->
                        <div class="w-full md:w-1/2">
                            <label class="text-sm font-semibold text-gray-700">Nomor HP</label>
                            <input name="no_hp" type="text" value="{{ old('no_hp') }}" placeholder="0812-XXXX-XXXX"
                                required
                                class="w-full mt-2 p-4 rounded-xl bg-[#EBF0EE] border-none outline-none text-sm focus:ring-2 focus:ring-[#1cb764] transition {{ $errors->has('no_hp') ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                            @error('no_hp')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="w-full md:w-1/2">
                            <label class="text-sm font-semibold text-gray-700">Email</label>
                            <input name="email" type="email" value="{{ old('email') }}"
                                placeholder="email@contoh.com" required
                                class="w-full mt-2 p-4 rounded-xl bg-[#EBF0EE] border-none outline-none text-sm focus:ring-2 focus:ring-[#1cb764] transition {{ $errors->has('email') ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kata Sandi -->
                    <div class="mt-5">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="Password" name="password" placeholder="••••••••" required
                                class="w-full mt-2 p-4 pr-12 rounded-xl bg-[#EBF0EE] border-none outline-none text-sm focus:ring-2 focus:ring-[#1cb764] transition {{ $errors->has('password') ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-4 mt-2 flex items-center text-gray-500 hover:text-[#1cb764] transition">
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

                        <!-- Password Requirements Block -->
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

                    <!-- Agreement Checkbox -->
                    <div class="bg-[#EBF0EE] p-4 rounded-xl mt-8 flex gap-3 text-sm text-gray-600 items-start">
                        <input id="agreementCheckbox" name="agreement" type="checkbox" class="mt-1 w-5 h-5 text-[#1cb764] rounded border-gray-300 focus:ring-[#1cb764]" required>
                        <p class="leading-relaxed">
                            Saya bersedia mengikuti regulasi dan peraturan yang ada serta berkomitmen pada standar keamanan pangan
                            <span class="text-[#1cb764] font-semibold">ShareBite</span> secara konsisten.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button id="submitButton" type="submit" disabled
                        class="w-full bg-[#1cb764] text-black py-4 mt-6 rounded-xl font-bold">
                        Daftar Sebagai Relawan
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-[#1cb764] font-bold hover:underline">Masuk di sini</a>
                        </p>
                    </div>

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

            function checkFormValidity() {
                let allInputsFilled = true;

                allRequiredInputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        if (!input.checked) allInputsFilled = false;
                    } else {
                        if (input.value.trim() === '') allInputsFilled = false;
                    }
                });

                if (allInputsFilled && isPasswordValid) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

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

            allRequiredInputs.forEach(input => {
                const eventType = input.type === 'checkbox' ? 'change' : 'input';
                input.addEventListener(eventType, checkFormValidity);
            });

            checkFormValidity();
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Login – ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;

            /* Menghilangkan icon mata bawaan browser (Edge/Chrome) */
            input[type="password"]::-ms-reveal,
            input[type="password"]::-ms-clear {
                display: none;
            }

        }

        html,
        body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ======== LAYOUT ======== */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ======== LEFT PANEL ======== */
        .left-panel {
            position: relative;
            width: 58%;
            min-height: 100vh;
            background-color: #f2fdf4;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 64px;
        }

        /* Blob 1 — Lebih besar, hijau cerah */
        .left-panel::before {
            content: '';
            position: absolute;
            top: -120px;
            left: -120px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(74, 222, 128, 0.8) 0%, rgba(134, 239, 172, 0.4) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: 0;
            animation: organicDrift1 4s ease-in-out infinite;
        }

        /* Blob 2 — Sedang, hijau mint */
        .left-panel::after {
            content: '';
            position: absolute;
            top: 15%;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(187, 247, 208, 0.7) 0%, rgba(220, 252, 231, 0.3) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(50px);
            z-index: 0;
            animation: organicDrift2 5s ease-in-out infinite;
        }

        /* Blob 3 — Bottom Left */
        .blob-extra {
            position: absolute;
            bottom: -100px;
            left: 10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.5) 0%, rgba(134, 239, 172, 0.2) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            animation: organicDrift3 4.5s ease-in-out infinite;
        }

        /* Blob 4 — Bottom Right */
        .blob-extra-2 {
            position: absolute;
            bottom: 50px;
            right: -120px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(134, 239, 172, 0.6) 0%, rgba(187, 247, 208, 0.3) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(45px);
            z-index: 0;
            animation: organicDrift2 6s ease-in-out infinite reverse;
        }

        /* Improved organic floating animations with multi-step translation */
        @keyframes organicDrift1 {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            25% {
                transform: translate(40px, 60px) rotate(5deg) scale(1.05);
            }

            50% {
                transform: translate(-20px, 100px) rotate(-5deg) scale(0.95);
            }

            75% {
                transform: translate(-60px, 30px) rotate(3deg) scale(1.02);
            }
        }

        @keyframes organicDrift2 {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            33% {
                transform: translate(-50px, 40px) rotate(-8deg) scale(1.1);
            }

            66% {
                transform: translate(30px, -60px) rotate(4deg) scale(0.9);
            }
        }

        @keyframes organicDrift3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            20% {
                transform: translate(60px, -40px) scale(1.08);
            }

            40% {
                transform: translate(-30px, -80px) scale(0.92);
            }

            60% {
                transform: translate(-70px, 20px) scale(1.05);
            }

            80% {
                transform: translate(40px, 50px) scale(0.96);
            }
        }

        /* ======== LEFT TEXT CONTENT ======== */
        .left-content {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-heading {
            font-size: 3.6rem;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: #111827;
        }

        .left-heading .green-text {
            color: #22c55e;
        }

        .left-desc {
            margin-top: 28px;
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.7;
            max-width: 380px;
            font-weight: 400;
        }

        /* ======== LOGO ======== */
        .logo-wrap {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ======== RIGHT PANEL ======== */
        .right-panel {
            position: relative;
            flex: 1;
            min-height: 100vh;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 64px;
            overflow: hidden;
        }

        /* Blob peach — pojok kanan bawah panel kanan */
        .right-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, #fed7aa 0%, #fef3c7 45%, transparent 72%);
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.7;
        }

        /* ======== FORM CARD ======== */
        .form-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            margin-top: 6px;
            font-size: 0.9rem;
            color: #9ca3af;
            font-weight: 400;
        }

        /* ======== INPUT ======== */
        .input-group {
            margin-top: 36px;
        }

        .input-group+.input-group {
            margin-top: 28px;
        }

        .input-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .input-field {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #e5e7eb;
            border-radius: 0;
            outline: none;
            padding: 8px 0;
            font-size: 0.95rem;
            color: #111827;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color 0.2s;
        }

        .input-field::placeholder {
            color: #d1d5db;
        }

        .input-field:focus {
            border-bottom-color: #22c55e;
        }

        .input-field.error {
            border-bottom-color: #ef4444;
        }

        /* Password wrapper */
        .password-wrap {
            position: relative;
        }

        .password-wrap .input-field {
            padding-right: 36px;
        }

        .toggle-pass {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .toggle-pass:hover {
            color: #22c55e;
        }

        /* ======== REMEMBER + LUPA ======== */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        /* Custom radio-style checkbox */
        .remember-check {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 1.5px solid #d1d5db;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .remember-check:checked {
            background-color: #22c55e;
            border-color: #22c55e;
            box-shadow: inset 0 0 0 4px #ffffff;
        }

        .remember-text {
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 700;
            color: #22c55e;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #16a34a;
        }

        /* ======== BUTTON ======== */
        .btn-login {
            display: block;
            width: 100%;
            margin-top: 32px;
            padding: 16px;
            background: #22c55e;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-login:hover {
            background: #16a34a;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(34, 197, 94, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ======== REGISTER LINK ======== */
        .register-text {
            margin-top: 24px;
            text-align: center;
            font-size: 0.875rem;
            color: #9ca3af;
        }

        .register-link {
            font-weight: 700;
            color: #22c55e;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link:hover {
            color: #16a34a;
        }

        /* ======== ERROR ALERT ======== */
        .error-alert {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #dc2626;
        }

        .error-msg {
            margin-top: 6px;
            font-size: 0.78rem;
            color: #ef4444;
        }

        /* ======== RESPONSIVE ======== */
        @media (max-width: 1024px) {
            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 48px 32px;
            }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">

        {{-- ===================== LEFT PANEL ===================== --}}
        <div class="left-panel">
            <div class="blob-extra"></div>
            <div class="blob-extra-2"></div>
            <div class="left-content">
                <h1 class="left-heading">
                    Selamatkan<br>
                    makanan, <span class="green-text">sebarkan<br>kebaikan.</span>
                </h1>
                <p class="left-desc">
                    Bergabunglah dengan ribuan penyelamat makanan untuk
                    mengurangi food waste dan membantu sesama, satu porsi
                    dalam satu waktu.
                </p>
            </div>

            {{-- Logo — ganti src sesuai path asset logo kamu --}}
            <div class="logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="ShareBite" style="height: 110px; width: auto;">
            </div>
        </div>

        {{-- ===================== RIGHT PANEL ===================== --}}
        <div class="right-panel">
            <div class="form-card">

                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-subtitle">Silakan masuk ke akun Anda</p>

                {{-- Error alert global --}}
                @if ($errors->any())
                    <div class="error-alert">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0">
                            <path fill-rule="evenodd"
                                d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    {{-- EMAIL --}}
                    <div class="input-group">
                        <label class="input-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                            autocomplete="email" class="input-field {{ $errors->has('email') ? 'error' : '' }}">
                        @error('email')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KATA SANDI --}}
                    <div class="input-group">
                        <label class="input-label">Kata Sandi</label>
                        <div class="password-wrap">
                            <input type="password" id="passwordInput" name="password" placeholder="••••••••"
                                autocomplete="current-password"
                                class="input-field {{ $errors->has('password') ? 'error' : '' }}">
                            <button type="button" class="toggle-pass" id="togglePassword" tabindex="-1">
                                {{-- Eye slash (default) --}}
                                <svg id="iconHide" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                {{-- Eye (saat tampil) --}}
                                <svg id="iconShow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.972 9.972 0 013.29-1.56m0 0L12 5.5l1.42 1.42m0 0A3 3 0 0115 12">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- INGAT SAYA + LUPA SANDI --}}
                    <div class="remember-row">
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa Sandi?</a>
                    </div>

                    {{-- TOMBOL LOGIN --}}
                    <button type="submit" class="btn-login" id="loginBtn">Masuk Sekarang</button>

                    {{-- DAFTAR --}}
                    <p class="register-text">
                        Baru di ShareBite?&nbsp;
                        <a href="{{ route('registerkomunitas') }}" class="register-link">Buat Akun Baru</a>
                    </p>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('rejection_message'))
                Swal.fire({
                    title: 'Verifikasi Ditolak',
                    text: "{{ session('rejection_message') }} \n\n Catatan: {{ session('rejection_notes') }}",
                    icon: 'error',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'Saya Mengerti',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-full px-8 py-3 font-bold'
                    }
                });
            @endif

        const toggleBtn = document.getElementById('togglePassword');
            const input = document.getElementById('passwordInput');
            const iconHide = document.getElementById('iconHide');
            const iconShow = document.getElementById('iconShow');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    iconHide.style.display = isHidden ? 'none' : '';
                    iconShow.style.display = isHidden ? '' : 'none';
                });
            }

            const emailInput = document.querySelector('input[name="email"]');
            const loginBtn = document.getElementById('loginBtn');

            function checkLoginValidity() {
                if (emailInput.value.trim() !== '' && input.value.trim() !== '') {
                    loginBtn.disabled = false;
                    loginBtn.style.opacity = '1';
                    loginBtn.style.cursor = 'pointer';
                } else {
                    loginBtn.disabled = true;
                    loginBtn.style.opacity = '0.5';
                    loginBtn.style.cursor = 'not-allowed';
                }
            }

            if (emailInput && input) {
                emailInput.addEventListener('input', checkLoginValidity);
                input.addEventListener('input', checkLoginValidity);

                // Trigger check on load for autofill
                checkLoginValidity();
            }
        });
    </script>

</body>

</html>
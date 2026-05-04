<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Pendaftaran Unit Bisnis - ShareBite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- SweetAlert2 -->
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
        <!-- Changed width to lg:w-4/12 to make it narrower -->
        <div
            class="hidden lg:flex lg:w-4/12 bg-[#1cb764] flex-col justify-between px-10 py-10 text-white relative overflow-hidden">

            <!-- Logo -->
            <div class="z-10">
                <div class="bg-white rounded-full w-36 h-16 flex items-center justify-center shadow-sm overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="ShareBite Logo" class="h-24 w-auto object-contain"
                        onerror="this.outerHTML='<span class=\'text-[#1cb764] font-bold text-lg\'>ShareBite</span>'">
                </div>
            </div>

            <!-- Main Copy -->
            <div class="mt-8 z-10">
                <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                    Transformasi <br>
                    <span class="text-[#f7b055]">Surplus</span> <br>
                    Menjadi <br>
                    Solusi.
                </h1>
                <p class="text-base text-white/90 font-medium mb-3">
                    Jadilah pionir dalam ekosistem pangan berkelanjutan Indonesia.
                </p>
                <p class="text-sm text-white/80 leading-relaxed max-w-sm">
                    Daftarkan unit bisnis Anda hari ini dan mulai optimalkan inventaris pangan berlebih Anda untuk
                    membantu komunitas yang membutuhkan sekaligus mengurangi dampak lingkungan.
                </p>
            </div>

            <!-- Stat Cards -->
            <div class="mt-8 space-y-4 z-10 max-w-sm">
                <div class="bg-white/10 rounded-2xl p-4 flex items-center space-x-5 shadow-sm">
                    <div class="bg-white/20 p-3.5 rounded-2xl flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-white">{{ $totalUnitBisnis }}+</div>
                        <div class="text-[11px] font-bold tracking-widest text-white uppercase mt-0.5">UNIT BISNIS
                            TERDAFTAR</div>
                    </div>
                </div>

                <div class="bg-white/10 rounded-2xl p-4 flex items-center space-x-5 shadow-sm">
                    <div class="bg-white/20 p-3.5 rounded-2xl flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z">
                            </path>
                            <path d="M2 22l7-7"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-white">{{ $totalMakananTerselamatkan }} Porsi</div>
                        <div class="text-[11px] font-bold tracking-widest text-white uppercase mt-0.5">MAKANAN
                            TERSELAMATKAN</div>
                    </div>
                </div>

                <div class="bg-white/10 rounded-2xl p-4 flex items-center space-x-5 shadow-sm">
                    <div class="bg-white/20 p-3.5 rounded-2xl flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-white">{{ $totalPenerimaManfaat }}+</div>
                        <div class="text-[11px] font-bold tracking-widest text-white uppercase mt-0.5">PENERIMA MANFAAT
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-10 flex items-start space-x-2 text-xs text-white/80 z-10 font-medium">
                <svg class="w-4 h-4 shrink-0 text-[#f7b055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Bersertifikasi Keamanan Pangan & Standar Operasional Internasional</span>
            </div>
        </div>

        <!-- Right Column: Form -->
        <!-- Changed width to lg:w-8/12 to make it wider -->
        <div class="w-full lg:w-8/12 flex justify-center py-8 px-6 sm:px-12 overflow-y-auto">
            <div class="w-full max-w-2xl">

                <!-- Tabs -->
                <div
                    class="flex space-x-2 bg-[#EBF0EE] p-1.5 rounded-xl mb-8 text-sm font-medium text-center shadow-inner">
                    <a href="{{ route('unit-bisnis.create') }}"
                        class="flex-1 bg-[#1cb764] text-white py-2.5 rounded-lg shadow-sm cursor-default block">Unit
                        Bisnis</a>
                    <a href="{{ route('registerkomunitas') }}"
                        class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Komunitas</a>
                    <a href="{{ route('individu.create') }}"
                        class="flex-1 text-gray-500 py-2.5 hover:bg-white/50 rounded-lg transition block">Individu</a>
                </div>

                @if(session('success'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registrasi Unit Bisnis Berhasil',
                                html: '<p style="font-size: 14px;">Harap tunggu akun mu diverifikasi dalam 3x24 jam</p>',
                                confirmButtonColor: '#1cb764',
                                confirmButtonText: 'Lanjutkan'
                            });
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('unit-bisnis.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8" id="registForm">
                    @csrf

                    <!-- Section 1: Informasi Bisnis -->
                    <div>
                        <div class="flex items-center space-x-3 mb-5 text-gray-800">
                            <div class="bg-green-100 p-2 rounded-lg text-[#1cb764] shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold">Informasi Bisnis</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Usaha</label>
                                <input type="text" id="Nama_Usaha" name="Nama_Usaha" value="{{ old('Nama_Usaha') }}"
                                    placeholder="Contoh: Resto Lestari"
                                    class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-[#1cb764] outline-none transition"
                                    required>
                                <p id="err-nama" class="text-xs text-red-500 mt-1 hidden">Nama usaha tidak boleh kosong.
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Usaha</label>
                                <div class="relative">
                                    <select name="Jenis_Usaha"
                                        class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3.5 text-sm text-gray-700 focus:ring-2 focus:ring-[#1cb764] outline-none appearance-none transition"
                                        required>
                                        <option value="Restoran" {{ old('Jenis_Usaha') == 'Restoran' ? 'selected' : '' }}>
                                            Restoran</option>
                                        <option value="Kafe" {{ old('Jenis_Usaha') == 'Kafe' ? 'selected' : '' }}>Kafe
                                        </option>
                                        <option value="Hotel" {{ old('Jenis_Usaha') == 'Hotel' ? 'selected' : '' }}>Hotel
                                        </option>
                                        <option value="Supermarket" {{ old('Jenis_Usaha') == 'Supermarket' ? 'selected' : '' }}>Supermarket</option>
                                        <option value="Lainnya" {{ old('Jenis_Usaha') == 'Lainnya' ? 'selected' : '' }}>
                                            Lainnya</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                            <textarea id="Alamat" name="Alamat" rows="2"
                                placeholder="Jl. Keberlanjutan No. 88, Jakarta Selatan..."
                                class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#1cb764] outline-none transition"
                                required>{{ old('Alamat') }}</textarea>
                            <p id="err-alamat" class="text-xs text-red-500 mt-1 hidden">Alamat tidak boleh kosong.</p>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Verifikasi Dokumen</label>

                            <!-- Dropzone Area -->
                            <div id="upload-zone"
                                class="border-2 border-dashed border-[#1cb764]/40 bg-[#1cb764]/5 rounded-2xl p-6 text-center transition hover:bg-[#1cb764]/10">
                                <svg class="w-8 h-8 mx-auto text-[#1cb764] mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm font-bold text-gray-800">Upload Nomor Induk Berusaha (NIB)</p>
                                <p class="text-xs text-gray-500 mb-4">Format PDF atau JPG (Maks. 5MB)</p>

                                <label
                                    class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer shadow-sm transition">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Unggah NIB
                                    <input type="file" id="NIB_File" name="NIB_File" class="hidden"
                                        accept=".pdf,.jpg,.jpeg">
                                </label>
                            </div>
                            <p id="err-nib" class="text-xs text-red-500 mt-1 hidden">Harap unggah dokumen NIB.</p>

                            <!-- Success Uploaded State -->
                            <div id="upload-success"
                                class="hidden bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                                <div class="flex items-center">
                                    <div class="bg-white p-2 rounded-lg border border-green-100 shadow-sm mr-3">
                                        <svg class="w-6 h-6 text-[#1cb764]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p id="file-name-display"
                                            class="text-sm font-bold text-gray-800 truncate max-wxs">dokumen.pdf</p>
                                        <p class="text-xs text-[#1cb764] font-medium">Berhasil diunggah</p>
                                    </div>
                                </div>
                                <button type="button" id="remove-file"
                                    class="text-sm font-bold text-red-500 hover:text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Ganti</button>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Section 2: Kontak & Keamanan -->
                    <div>
                        <div class="flex items-center space-x-3 mb-5 text-gray-800">
                            <div class="bg-green-100 p-2 rounded-lg text-[#1cb764] shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold">Kontak & Keamanan</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor HP</label>
                                <input type="tel" id="Nomor_hp" name="Nomor_hp" value="{{ old('Nomor_hp') }}"
                                    placeholder="0812-XXXX-XXXX" inputmode="numeric" pattern="^(\+62|0)[0-9]{9,13}$"
                                    maxlength="15"
                                    class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-[#1cb764] outline-none transition"
                                    required>
                                <p id="err-hp" class="text-xs text-red-500 mt-1 hidden">Nomor HP hanya boleh angka
                                    (10–14 digit), contoh: 08123456789.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Bisnis</label>
                                <input type="email" id="Email" name="Email" value="{{ old('Email') }}"
                                    placeholder="admin@usaha.com"
                                    class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-[#1cb764] outline-none transition"
                                    required>
                                <p id="err-email" class="text-xs text-red-500 mt-1 hidden">Format email tidak valid.</p>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                            <div class="relative">
                                <input type="password" id="Password" name="Password" placeholder="••••••••"
                                    minlength="8"
                                    class="w-full bg-[#EBF0EE] border-none rounded-xl px-4 py-3.5 text-sm focus:ring-2 focus:ring-[#1cb764] outline-none transition"
                                    required>
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-[#1cb764] transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        id="eyeIcon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Realtime Password Validation -->
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
                    </div>

                    <hr class="border-gray-100">

                    <!-- Section 3: Lokasi & Alamat -->
                    <div>
                        <div class="flex items-center space-x-3 mb-5 text-gray-800">
                            <div class="bg-green-100 p-2 rounded-lg text-[#1cb764] shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold">Lokasi & Alamat</h2>
                        </div>

                        <!-- Location Search Bar -->
                        <div class="relative mb-3" id="location-search-wrapper">
                            <div class="flex items-center bg-[#EBF0EE] rounded-xl px-4 py-3 gap-2">
                                <svg class="w-4 h-4 text-[#1cb764] shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" id="location-search"
                                    placeholder="Cari lokasi, misal: Jl. Sudirman Jakarta..."
                                    class="flex-1 bg-transparent text-sm outline-none text-gray-700 placeholder-gray-400">
                                <button type="button" id="btn-search-loc"
                                    class="text-xs font-bold text-white bg-[#1cb764] px-3 py-1.5 rounded-lg hover:bg-[#19a55a] transition shrink-0">
                                    Cari
                                </button>
                            </div>
                            <!-- Autocomplete Dropdown -->
                            <ul id="search-suggestions"
                                class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl mt-1 shadow-lg text-sm hidden max-h-48 overflow-y-auto">
                            </ul>
                        </div>

                        <div
                            class="relative w-full h-56 bg-[#EBF0EE] rounded-2xl flex flex-col items-center justify-center mb-3 overflow-hidden border border-gray-200 group">

                            <!-- The Map Container -->
                            <div id="map" class="absolute inset-0 z-0"></div>



                            <!-- Hidden inputs for Lat/Lng -->
                            <input type="hidden" id="Latitude" name="Latitude" value="">
                            <input type="hidden" id="Longitude" name="Longitude" value="">
                        </div>
                        <div class="flex items-start text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1.5 shrink-0 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>Cari lokasi melalui kolom pencarian dan sesuaikan posisi pin (marker) pada peta agar tepat pada lokasi usaha Anda.</p>
                        </div>
                        <p id="err-lokasi" class="text-xs text-red-500 mt-1 hidden">Lokasi harus ditentukan dari peta.
                        </p>
                    </div>

                    <!-- Terms & Submit -->
                    <div class="mt-8 space-y-6">
                        <label
                            class="flex items-start space-x-3 bg-gray-50 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                            <input type="checkbox" id="terms" required
                                class="mt-1 w-5 h-5 text-[#1cb764] rounded border-gray-300 focus:ring-[#1cb764]">
                            <span class="text-sm text-gray-600 leading-relaxed select-none">
                                Saya bersedia mengikuti regulasi dan peraturan yang ada serta berkomitmen pada standar
                                keamanan pangan <span class="font-bold text-[#1cb764]">ShareBite</span> secara
                                konsisten.
                            </span>
                        </label>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" disabled
                            class="w-full bg-[#1cb764] text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 opacity-50 cursor-not-allowed"
                            style="background-color: #1cb764 !important;">
                            Daftar Sekarang
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-500 font-medium">
                            Sudah memiliki akun mitra? <a href="{{ route('login') }}"
                                class="text-[#1cb764] font-bold hover:underline">Login disini</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // 1. Password Visibility Toggle (Bug Fixed)
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('Password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.972 9.972 0 013.29-1.56m0 0L12 5.5l1.42 1.42m0 0A3 3 0 0115 12"></path>`;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
                }
            });

            // 2. Real-time Password Validation
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

            let isPasswordValid = false;

            passwordInput.addEventListener('input', function (e) {
                const val = e.target.value;

                const hasCapital = /[A-Z]/.test(val);
                const hasNumOrUnique = /[\d\W]/.test(val);
                const isMinLengthValid = val.length >= 8;

                updateRequirement(reqCap, hasCapital, 'Minimal 1 huruf kapital');
                updateRequirement(reqNum, hasNumOrUnique, 'Karakter unik atau nomor');
                updateRequirement(reqLen, isMinLengthValid, 'Minimal 8 karakter');

                isPasswordValid = hasCapital && hasNumOrUnique && isMinLengthValid;
                checkFormValidity();
            });

            // 3. Upload File UI State Change
            const fileInput = document.getElementById('NIB_File');
            const uploadZone = document.getElementById('upload-zone');
            const uploadSuccess = document.getElementById('upload-success');
            const fileNameDisplay = document.getElementById('file-name-display');
            const removeFileBtn = document.getElementById('remove-file');

            fileInput.addEventListener('change', function (e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const fileSize = file.size / 1024 / 1024; // size in MB

                    if (fileSize > 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar',
                            text: 'Ukuran file NIB tidak boleh melebihi 5MB.',
                            confirmButtonColor: '#1cb764'
                        });
                        fileInput.value = ''; // Reset input
                        return;
                    }

                    const fileName = file.name;
                    fileNameDisplay.textContent = fileName;

                    uploadZone.classList.add('hidden');
                    uploadSuccess.classList.remove('hidden');
                    document.getElementById('err-nib').classList.add('hidden');
                }
                checkFormValidity();
            });

            removeFileBtn.addEventListener('click', function () {
                fileInput.value = ''; // clear
                uploadZone.classList.remove('hidden');
                uploadSuccess.classList.add('hidden');
                checkFormValidity();
            });

            // 4. Map and Location Handling (Leaflet.js)
            let map;
            let marker;
            const latInput = document.getElementById('Latitude');
            const lngInput = document.getElementById('Longitude');

            function initMap(lat, lng, zoom) {
                if (map) {
                    map.setView([lat, lng], zoom);
                    marker.setLatLng([lat, lng]);
                } else {
                    map = L.map('map').setView([lat, lng], zoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    marker.on('dragend', function () {
                        latInput.value = marker.getLatLng().lat;
                        lngInput.value = marker.getLatLng().lng;
                        checkFormValidity();
                    });
                }
                latInput.value = lat;
                lngInput.value = lng;
                checkFormValidity();
                document.getElementById('err-lokasi').classList.add('hidden');
            }

            // Auto-initialize map
            window.addEventListener('load', function() {
                const defaultLat = -6.200000;
                const defaultLng = 106.816666;
                initMap(defaultLat, defaultLng, 13);
            });

            // 4b. Location Search (Nominatim / OpenStreetMap)
            const locationSearchInput = document.getElementById('location-search');
            const btnSearchLoc = document.getElementById('btn-search-loc');
            const searchSuggestions = document.getElementById('search-suggestions');
            let searchDebounce;

            function renderSuggestions(results) {
                searchSuggestions.innerHTML = '';
                if (!results || results.length === 0) {
                    searchSuggestions.innerHTML = '<li class="px-4 py-3 text-gray-400 text-xs">Lokasi tidak ditemukan.</li>';
                    searchSuggestions.classList.remove('hidden');
                    return;
                }
                results.forEach(function (item) {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2.5 cursor-pointer hover:bg-[#f0faf5] text-gray-700 flex items-start gap-2 border-b border-gray-100 last:border-0';
                    li.innerHTML = `<svg class="w-3.5 h-3.5 mt-0.5 shrink-0 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span class="leading-tight">${item.display_name}</span>`;
                    li.addEventListener('click', function () {
                        locationSearchInput.value = item.display_name;
                        searchSuggestions.classList.add('hidden');
                        initMap(parseFloat(item.lat), parseFloat(item.lon), 16);
                    });
                    searchSuggestions.appendChild(li);
                });
                searchSuggestions.classList.remove('hidden');
            }

            async function doSearch(query) {
                if (!query.trim()) return;
                try {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&accept-language=id`;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    renderSuggestions(data);
                } catch (err) {
                    searchSuggestions.innerHTML = '<li class="px-4 py-3 text-red-400 text-xs">Gagal terhubung. Periksa koneksi internet.</li>';
                    searchSuggestions.classList.remove('hidden');
                }
            }

            locationSearchInput.addEventListener('input', function () {
                clearTimeout(searchDebounce);
                const q = this.value.trim();
                if (q.length < 3) { searchSuggestions.classList.add('hidden'); return; }
                searchDebounce = setTimeout(() => doSearch(q), 400);
            });

            locationSearchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); doSearch(this.value); }
            });

            btnSearchLoc.addEventListener('click', function () { doSearch(locationSearchInput.value); });

            // Close suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!document.getElementById('location-search-wrapper').contains(e.target)) {
                    searchSuggestions.classList.add('hidden');
                }
            });

            // 5. Checkbox & Form Validity Check
            const termsCheckbox = document.getElementById('terms');
            const submitBtn = document.getElementById('submitBtn');
            const registForm = document.getElementById('registForm');

            // Phone number validation
            const phoneInput = document.getElementById('Nomor_hp');
            const errHp = document.getElementById('err-hp');
            let isPhoneValid = false;
            const phoneRegex = /^(\+62|0)[0-9]{9,13}$/;

            phoneInput.addEventListener('keypress', function (e) {
                const allowed = /[0-9+]/;
                if (!allowed.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
                    e.preventDefault();
                }
            });

            phoneInput.addEventListener('input', function () {
                // Strip anything that isn't digit or +
                this.value = this.value.replace(/[^0-9+]/g, '');
                isPhoneValid = phoneRegex.test(this.value);
                if (this.value.length > 0 && !isPhoneValid) {
                    errHp.classList.remove('hidden');
                } else {
                    errHp.classList.add('hidden');
                }
                checkFormValidity();
            });

            function checkFormValidity() {
                // 1. Cek Checkbox Syarat & Ketentuan
                const isTermsChecked = termsCheckbox.checked;

                // 2. Cek File NIB (Wajib)
                const isFileUploaded = fileInput.files.length > 0;

                // 3. Cek Lokasi (Wajib)
                const isLocationSet = latInput.value.trim() !== "" && lngInput.value.trim() !== "";

                // 4. Cek input lainnya (Nama, Email, HP, dll)
                let allInputsFilled = true;
                const requiredInputs = registForm.querySelectorAll('input[required], textarea[required], select[required]');

                requiredInputs.forEach(input => {
                    if (!input.value.trim() && input.type !== 'checkbox') {
                        allInputsFilled = false;
                    }
                });

                // 5. Validasi Akhir: Semuanya harus TRUE agar tombol menyala
                if (isTermsChecked && isPasswordValid && allInputsFilled && isFileUploaded && isLocationSet && isPhoneValid) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.add('hover:bg-[#19a55a]');

                    // Sembunyikan pesan error jika sudah valid
                    document.getElementById('err-nib').classList.add('hidden');
                    document.getElementById('err-lokasi').classList.add('hidden');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.remove('hover:bg-[#19a55a]');
                }
            }

            termsCheckbox.addEventListener('change', checkFormValidity);

            // Basic HTML5 validation error clearing on input
            const inputs = registForm.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                if (input.id !== 'Nomor_hp') { // skip, handled above
                    input.addEventListener('input', function () {
                        checkFormValidity();
                    });
                }
            });

        });
    </script>
</body>

</html>
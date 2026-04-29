<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Menggunakan yield untuk title agar bisa diisi dari tiap file fitur [cite: 35] --}}
    <title>@yield('title') – ShareBite</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Custom CSS eksternal (DILARANG nulis CSS inline di sini) [cite: 21, 22] --}}
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
</head>

<body class="bg-[#F8FAFB]">

    <div class="flex min-h-screen">
        [cite_start]{{-- SIDEBAR: Dikelola di sini, bukan di file fitur [cite: 12, 36] --}}
        <aside class="w-64 bg-white border-r ...">
            {{-- Isi Sidebar (Dashboard, Manajemen Pengguna, dll) --}}
        </aside>

        <div class="flex-1">
            [cite_start]{{-- NAVBAR: Dikelola di sini, bukan di file fitur [cite: 11, 34] --}}
            <nav class="bg-white border-b ...">
                {{-- Isi Navbar (Profile, Notifikasi, dll) --}}
            </nav>

            {{-- KONTEN FITUR: Tempat masuknya isi dari index/create/edit.blade.php [cite: 15, 16, 37] --}}
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
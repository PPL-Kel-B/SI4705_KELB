<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareBite - Manajemen Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFB]">

<div class="flex min-h-screen">
    
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between p-6 hidden md:flex sticky top-0 h-screen">
        <div>
            <div class="flex items-center gap-2 mb-10">
                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-xs">SB</div>
                <span class="text-xl font-bold text-green-800">ShareBite</span>
            </div>

            <nav class="space-y-2">
                <a href="#" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-gray-50 rounded-xl transition">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('manajemen-user.index') }}" class="flex items-center gap-3 p-3 bg-green-50 text-green-700 font-bold rounded-xl transition border border-green-100">
                    <span>👥</span> Manajemen Pengguna
                </a>
                <a href="#" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition">
                    <span>💬</span> Chat
                </a>
            </nav>
        </div>

        <a href="#" class="flex items-center gap-3 p-3 text-red-500 font-semibold hover:bg-red-50 rounded-xl transition">
            <span>🚪</span> Logout
        </a>
    </aside>

    <main class="flex-1 p-8">
        
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-green-900">Manajemen Pengguna</h1>
            <div class="flex gap-3">
                <button class="p-2 bg-white rounded-full border border-gray-100 shadow-sm relative">
                    🔔 <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <button class="p-2 bg-white rounded-full border border-gray-100 shadow-sm">⚙️</button>
            </div>
        </header>

        <div class="flex gap-2 mb-8 bg-gray-100/50 p-1 rounded-xl w-fit">
            <a href="?tab=unit_bisnis" class="px-6 py-2 {{ $tab == 'unit_bisnis' ? 'bg-white text-green-800 font-bold shadow-sm' : 'text-gray-500' }} rounded-lg transition">Unit Bisnis</a>
            <a href="?tab=komunitas" class="px-6 py-2 {{ $tab == 'komunitas' ? 'bg-white text-green-800 font-bold shadow-sm' : 'text-gray-500' }} rounded-lg transition">Komunitas / Individu</a>
            <a href="#" class="px-6 py-2 text-gray-500 hover:bg-white/50 rounded-lg transition cursor-not-allowed">Verifikasi NIB</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="p-4 bg-green-50 rounded-2xl text-2xl">🏪</div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total Unit Bisnis</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_bisnis'] ?? 0 }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="p-4 bg-orange-50 rounded-2xl text-2xl">🛡️</div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Pending Verifikasi</p>
                    <p class="text-2xl font-bold text-gray-800">42</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="p-4 bg-indigo-50 rounded-2xl text-2xl">👥</div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">User Aktif</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['aktif_komunitas'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 mb-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Daftar {{ $tab == 'unit_bisnis' ? 'Unit Bisnis' : 'Komunitas / Individu' }}</h3>
                <p class="text-sm text-gray-400">Kelola status dan informasi pengguna yang terdaftar.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-4 font-semibold">Nama {{ $tab == 'unit_bisnis' ? 'Bisnis' : 'User' }}</th>
                            <th class="pb-4 font-semibold">Tipe</th>
                            <th class="pb-4 font-semibold">Email</th>
                            <th class="pb-4 font-semibold">Tanggal Bergabung</th>
                            <th class="pb-4 font-semibold">Status</th>
                            <th class="pb-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($users as $user)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition">
                            <td class="py-5">
                                <div class="font-bold text-gray-800">
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[9px] font-bold uppercase">
                                    {{ $user->type }}
                                </span>
                            </td>
                            <td class="text-gray-500 text-xs">{{ $user->Email }}</td>
                            <td class="text-gray-500 text-xs">{{ date('d M Y', strtotime($user->created_at)) }}</td>
                            <td>
                                @if($tab == 'unit_bisnis')
                                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold">
                                        • Verified
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold">
                                        • Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-3 text-xs">
                                    <button class="text-orange-400 hover:text-orange-600 transition">✏️</button>
                                    <button class="text-gray-300 hover:text-red-500 transition">🗑️</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-10 text-center text-gray-400">Belum ada data tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-green-900 flex items-center gap-2">
                    <span class="text-green-600 text-xl">✅</span> Verifikasi NIB Terbaru
                </h3>
                <a href="#" class="text-green-600 font-bold text-sm">Lihat Semua Antrean</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-orange-50 rounded-xl">📄</div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Restoran Padang Sederhana</h4>
                                <p class="text-[10px] text-gray-400">NIB: 9120001234567</p>
                            </div>
                        </div>
                        <span class="text-[9px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded font-bold uppercase">Urgent</span>
                    </div>
                    <div class="bg-green-50/50 p-3 rounded-xl flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2 text-[10px] text-gray-500 font-medium">
                            <span>📄</span> Dokumen_NIB_Sederhana.pdf
                        </div>
                        <button class="text-[10px] text-green-700 font-bold hover:underline">Pratinjau</button>
                    </div>
                    <button class="w-full bg-[#036B3D] text-white py-3 rounded-xl font-bold hover:bg-green-800 transition shadow-lg shadow-green-900/10 text-xs">Review</button>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm opacity-60">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gray-50 rounded-xl">📄</div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Healthy Garden Catering</h4>
                                <p class="text-[10px] text-gray-400">NIB: 8121029384756</p>
                            </div>
                        </div>
                        <span class="text-[9px] bg-gray-100 text-gray-400 px-2 py-0.5 rounded font-bold uppercase">New</span>
                    </div>
                    <div class="bg-green-50/50 p-3 rounded-xl flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2 text-[10px] text-gray-500 font-medium">
                            <span>📎</span> Sertifikat_OSS_HGC.pdf
                        </div>
                        <button class="text-[10px] text-green-700 font-bold hover:underline">Pratinjau</button>
                    </div>
                    <button class="w-full bg-[#036B3D] text-white py-3 rounded-xl font-bold hover:bg-green-800 transition text-xs">Review</button>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>
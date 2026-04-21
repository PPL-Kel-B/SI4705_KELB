@extends('layouts.app')

@section('title', 'Registrasi Unit Bisnis')

@section('content')
<div class="flex min-h-screen bg-white">
    <div class="hidden lg:flex w-1/3 bg-gradient-to-b from-[#10B981] to-[#059669] p-12 flex-col justify-between text-white">
        <div>
            <img src="{{ asset('images/Logo Sharebite.png') }}" alt="ShareBite" class="h-8">
            <h1 class="mt-20 text-5xl font-bold leading-tight">
                Transformasi <br> <span class="text-[#D1FAE5]">Surplus</span> <br> Menjadi Solusi.
            </h1>
            <p class="mt-6 text-lg opacity-90">Jadilah pionir dalam ekosistem pangan berkelanjutan Indonesia.</p>
        </div>

        <div class="space-y-6">
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                <p class="text-2xl font-bold">1,200+</p>
                <p class="text-sm opacity-80 uppercase tracking-wider">Unit Bisnis Terdaftar</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                <p class="text-2xl font-bold">50+ Ton</p>
                <p class="text-sm opacity-80 uppercase tracking-wider">Makanan Terselamatkan</p>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-2/3 p-8 md:p-16 overflow-y-auto">
        <div class="flex bg-gray-100 p-1 rounded-full w-fit mb-10">
            <button class="px-6 py-2 rounded-full bg-[#10B981] text-white font-medium">Unit Bisnis</button>
            <button class="px-6 py-2 rounded-full text-gray-500">Komunitas</button>
            <button class="px-6 py-2 rounded-full text-gray-500">Relawan</button>
        </div>

        <form action="{{ route('register.unit-bisnis.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="p-2 bg-green-50 text-green-600 rounded-lg">🏢</span>
                    <h2 class="text-xl font-bold text-gray-800">Informasi Bisnis</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha</label>
                        <input type="text" name="Nama_Usaha" placeholder="Contoh: Resto Lestari" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Usaha</label>
                        <select name="Jenis_Usaha" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                            <option value="Restoran">Restoran</option>
                            <option value="Cafe">Cafe</option>
                            <option value="Catering">Catering</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="Alamat" rows="3" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none" placeholder="Jl. Keberlanjutan No. 88..."></textarea>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="p-2 bg-green-50 text-green-600 rounded-lg">🛡️</span>
                    <h2 class="text-xl font-bold text-gray-800">Kontak & Keamanan</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="Nomor_hp" placeholder="Nomor HP" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                    <input type="email" name="Email" placeholder="Email Bisnis" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                </div>
                <input type="password" name="Password" placeholder="Kata Sandi" class="w-full mt-4 p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
            </div>

            <button type="submit" class="w-full py-4 bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-xl transition-all shadow-lg shadow-green-200">
                Daftar Sekarang
            </button>

            <p class="text-center mt-6 text-gray-500">
                Sudah memiliki akun mitra? <a href="#" class="text-green-600 font-bold">Masuk ke Dashboard</a>
            </p>
        </form>
    </div>
</div>
@endsection

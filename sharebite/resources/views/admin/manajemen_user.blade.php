@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div x-data="{ showEditModal: false, showDeleteModal: false, userToEdit: {}, userToDelete: {} }" class="space-y-2 -mt-4">

    @if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.opacity class="fixed top-6 right-6 bg-[#1cb764] text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="font-bold">{{ session('success') }}</span>
        <button @click="show = false" class="ml-4 text-white/80 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-1">
        <h1 class="text-3xl font-extrabold text-gray-900">Manajemen Pengguna</h1>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mb-2 bg-white border border-gray-100 p-1 rounded-xl w-fit shadow-sm">
        <a href="?tab=unit_bisnis" class="px-6 py-2.5 text-sm {{ $tab == 'unit_bisnis' ? 'bg-[#eefcf4] text-[#1cb764] font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-all">Unit Bisnis</a>
        <a href="?tab=komunitas" class="px-6 py-2.5 text-sm {{ $tab == 'komunitas' ? 'bg-[#eefcf4] text-[#1cb764] font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-all">Komunitas / Individu</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Total Unit Bisnis</p>
                <p class="text-3xl font-black text-gray-900">{{ $stats['total_bisnis'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-[#fcf3e8] rounded-2xl flex items-center justify-center text-[#f7b055]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Pending Verifikasi</p>
                <p class="text-3xl font-black text-gray-900">{{ $stats['pending_verifikasi'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-[#f0f2f9] rounded-2xl flex items-center justify-center text-[#4c54a4]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Aktif Komunitas</p>
                <p class="text-3xl font-black text-gray-900">{{ $stats['aktif_komunitas'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 mb-6">
        <div class="mb-6">
            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Daftar {{ $tab == 'unit_bisnis' ? 'Unit Bisnis' : 'Komunitas / Individu' }}</h3>
            <p class="text-sm text-gray-500 font-medium">Kelola informasi dan status pengguna yang terdaftar.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-100">
                        <th class="pb-4 font-bold">Nama {{ $tab == 'unit_bisnis' ? 'Bisnis' : 'User' }}</th>
                        <th class="pb-4 font-bold">Tipe</th>
                        <th class="pb-4 font-bold">Email</th>
                        <th class="pb-4 font-bold">Tanggal Bergabung</th>
                        <th class="pb-4 font-bold">Status</th>
                        <th class="pb-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4">
                            <div class="font-bold text-gray-900 flex items-center gap-4">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-xl object-cover shadow-sm border border-gray-100">
                                @else
                                    <div class="w-10 h-10 bg-[#eefcf4] text-[#1cb764] font-black flex justify-center items-center rounded-xl text-xs uppercase shadow-sm border border-[#1cb764]/10">{{ substr($user->name, 0, 2) }}</div>
                                @endif
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-widest border border-gray-200">
                                {{ $user->type }}
                            </span>
                        </td>
                        <td class="py-4 text-gray-500 text-xs font-medium">{{ $user->Email }}</td>
                        <td class="py-4 text-gray-500 text-xs font-medium">{{ date('d M Y', strtotime($user->created_at)) }}</td>
                        <td class="py-4">
                            @if($tab == 'unit_bisnis')
                                @if($user->status_verifikasi == 'terverifikasi')
                                    <span class="px-3 py-1.5 bg-[#eefcf4] text-[#1cb764] rounded-full text-[10px] font-bold flex w-fit items-center gap-1.5 border border-[#1cb764]/20 shadow-sm">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[#1cb764]"></div> Verified
                                    </span>
                                @elseif($user->status_verifikasi == 'pending')
                                    <span class="px-3 py-1.5 bg-[#fcf3e8] text-[#f7b055] rounded-full text-[10px] font-bold flex w-fit items-center gap-1.5 border border-[#f7b055]/20 shadow-sm">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[#f7b055]"></div> Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 bg-red-50 text-red-500 rounded-full text-[10px] font-bold flex w-fit items-center gap-1.5 border border-red-200 shadow-sm">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Ditolak
                                    </span>
                                @endif
                            @else
                                <span class="px-3 py-1.5 bg-[#eefcf4] text-[#1cb764] rounded-full text-[10px] font-bold flex w-fit items-center gap-1.5 border border-[#1cb764]/20 shadow-sm">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#1cb764]"></div> Aktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex justify-end gap-2 text-xs">
                                <button @click="showEditModal = true; userToEdit = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->Email) }}', alamat: '{{ addslashes($user->alamat) }}' }" class="p-2 text-[#1cb764] bg-[#eefcf4] hover:bg-[#1cb764] hover:text-white rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="showDeleteModal = true; userToDelete = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}' }" class="p-2 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-400 font-medium bg-gray-50/50 rounded-xl mt-4">Belum ada pengguna terdaftar untuk kategori ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- EDIT MODAL -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showEditModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 overflow-hidden z-10 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <h3 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                <div class="p-2 bg-[#eefcf4] text-[#1cb764] rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                Edit Pengguna
            </h3>
            <form :action="`/admin/manajemen-pengguna/${userToEdit?.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="mb-4 text-center">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3 text-left">Foto Profil</label>
                    <div class="relative w-24 h-24 mx-auto mb-2 group cursor-pointer" onclick="document.getElementById('foto_profil_input').click()">
                        <div class="w-24 h-24 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 overflow-hidden group-hover:border-[#1cb764] group-hover:text-[#1cb764] transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <input type="file" id="foto_profil_input" name="foto_profil" class="hidden" accept="image/*">
                    </div>
                    <p class="text-[10px] text-gray-400">Klik untuk mengganti foto</p>
                </div>

                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Pengguna / Bisnis</label>
                    <input type="text" name="name" x-model="userToEdit.name" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:border-[#1cb764] transition-colors shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" x-model="userToEdit.email" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-bold text-gray-400 cursor-not-allowed" readonly required>
                </div>
                <div class="mb-8">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" x-model="userToEdit.alamat" rows="2" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:border-[#1cb764] transition-colors shadow-sm resize-none"></textarea>
                </div>
                
                <div class="flex gap-3 mt-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-6 py-3.5 bg-[#1cb764] hover:bg-[#19a55a] text-white font-bold rounded-xl transition-all shadow-lg shadow-[#1cb764]/20 hover:-translate-y-0.5">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 overflow-hidden z-10 text-center">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-red-50 rounded-full blur-3xl opacity-50"></div>
            
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 text-red-500 mb-6 shadow-inner">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Pengguna?</h3>
            <p class="text-gray-500 font-medium mb-8 leading-relaxed">Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-900 px-1" x-text="userToDelete?.name"></span>? Tindakan ini tidak dapat dibatalkan dan semua data terkait akan hilang.</p>
            
            <div class="flex gap-3">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">Batal</button>
                <form :action="`/admin/manajemen-pengguna/${userToDelete?.id}`" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <button type="submit" class="w-full px-6 py-3.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-red-500/20 hover:-translate-y-0.5">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
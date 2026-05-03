@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div x-data="{ showEditModal: false, userToEdit: {}, previewUrl: null }"
    class="space-y-2 -mt-4">


    <!-- Header -->
    <div class="flex justify-between items-center mb-6 -mt-8">
        <h1 class="text-4xl font-black text-[#1cb764] tracking-tight">Manajemen User</h1>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mb-8 bg-white border border-gray-100 p-1.5 rounded-[1.5rem] w-fit shadow-sm">
        <a href="?tab=unit_bisnis"
            class="px-8 py-3 text-sm {{ $tab == 'unit_bisnis' ? 'bg-[#eefcf4] text-[#1cb764] font-black shadow-sm' : 'text-gray-400 font-bold hover:bg-gray-50' }} rounded-[1.2rem] transition-all duration-300">Unit
            Bisnis</a>
        <a href="?tab=komunitas"
            class="px-8 py-3 text-sm {{ $tab == 'komunitas' ? 'bg-[#eefcf4] text-[#1cb764] font-black shadow-sm' : 'text-gray-400 font-bold hover:bg-gray-50' }} rounded-[1.2rem] transition-all duration-300">Komunitas
            / Individu</a>
        <a href="?tab=verifikasi_nib"
            class="px-8 py-3 text-sm {{ $tab == 'verifikasi_nib' ? 'bg-[#eefcf4] text-[#1cb764] font-black shadow-sm' : 'text-gray-400 font-bold hover:bg-gray-50' }} rounded-[1.2rem] transition-all duration-300">Verifikasi
            NIB</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-all">
            <div class="w-16 h-16 bg-[#eefcf4] rounded-2xl flex items-center justify-center text-[#1cb764]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-black uppercase tracking-widest mb-1">Total Unit Bisnis</p>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_bisnis'] ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-all">
            <div class="w-16 h-16 bg-[#fcf3e8] rounded-2xl flex items-center justify-center text-[#f7b055]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-black uppercase tracking-widest mb-1">Pending Verifikasi</p>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['pending_verifikasi'] ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center gap-6 hover:shadow-md transition-all">
            <div class="w-16 h-16 bg-[#f0f2f9] rounded-2xl flex items-center justify-center text-[#4c54a4]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[11px] font-black uppercase tracking-widest mb-1">User Aktif</p>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['aktif_komunitas'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Table / Grid Section -->
    @if($tab == 'verifikasi_nib')
        <!-- Section Verifikasi NIB Terbaru (Premium Grid Layout) -->
        <div style="margin-top: 40px;">
            <div class="flex items-center gap-3 mb-10">
                <div class="p-2.5 bg-[#1cb764] rounded-2xl text-white shadow-lg shadow-[#1cb764]/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-[#0a2e1f] tracking-tight">Verifikasi NIB Terbaru</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10">
                @forelse($users as $user)
                    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative">
                        <div class="flex items-center gap-6 mb-10">
                            <div class="w-20 h-20 bg-[#fcf3e8] rounded-[2rem] flex items-center justify-center text-[#f7b055] shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#0a2e1f] leading-tight mb-1">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">ID PENGAJUAN: #SHB-{{ $user->id }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-[2rem] p-6 flex items-center justify-between mb-10 border border-gray-100 group-hover:bg-[#eefcf4] group-hover:border-[#1cb764]/20 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-red-500 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-black text-gray-700 block mb-0.5">Dokumen_NIB_{{ Str::slug($user->name) }}.pdf</span>
                                    <span class="text-[10px] text-gray-400 font-bold">PDF • 1.2 MB</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-[#1cb764] uppercase tracking-[0.2em] bg-white px-4 py-2 rounded-xl shadow-sm">Pratinjau</span>
                        </div>

                        <a href="{{ route('admin.manajemen_pengguna.review_nib', $user->id) }}" class="block w-full py-6 bg-[#1cb764] hover:bg-[#0a2e1f] text-white text-center font-black rounded-[2rem] transition-all duration-300 shadow-xl shadow-[#1cb764]/20 text-lg flex items-center justify-center gap-3">
                            Review
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                @empty
                    <div class="col-span-2 py-32 text-center bg-white rounded-[4rem] border-2 border-dashed border-gray-100">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto mb-6">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-gray-400 font-black text-xl">Tidak ada antrean verifikasi NIB saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <!-- Table (Unit Bisnis & Komunitas) -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm p-10 mt-4">
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-black text-[#0a2e1f] tracking-tight mb-2">Daftar
                        {{ $tab == 'unit_bisnis' ? 'Unit Bisnis' : 'Komunitas / Individu' }}</h3>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Total: {{ count($users) }} Terdaftar</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] border-b border-gray-50">
                            <th class="pb-6 font-black">Nama {{ $tab == 'unit_bisnis' ? 'Bisnis' : 'User' }}</th>
                            <th class="pb-6 font-black">Tipe</th>
                            <th class="pb-6 font-black">Email</th>
                            <th class="pb-6 font-black">Tanggal Bergabung</th>
                            <th class="pb-6 font-black">Status</th>
                            <th class="pb-6 font-black text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="py-6">
                                    <div class="font-black text-gray-900 flex items-center gap-4">
                                        @if(isset($user->foto_profil) && $user->foto_profil)
                                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}"
                                                class="w-12 h-12 rounded-[1.2rem] object-cover shadow-sm border border-gray-100">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-[#eefcf4] text-[#1cb764] font-black flex justify-center items-center rounded-[1.2rem] text-sm uppercase shadow-sm border border-[#1cb764]/10">
                                                {{ substr($user->name, 0, 2) }}</div>
                                        @endif
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="py-6 font-bold text-gray-500">
                                    <span class="px-4 py-1.5 bg-gray-100 rounded-full text-[10px] uppercase tracking-widest font-black">
                                        {{ $user->type ?? 'User' }}
                                    </span>
                                </td>
                                <td class="py-6 text-gray-500 font-bold text-xs">{{ $user->Email }}</td>
                                <td class="py-6 text-gray-500 font-bold text-xs">
                                    {{ date('d M Y', strtotime($user->created_at)) }}</td>
                                <td class="py-6">
                                    @if($tab == 'unit_bisnis')
                                        @if($user->status_verifikasi == 'terverifikasi')
                                            <span class="px-4 py-2 bg-[#eefcf4] text-[#1cb764] rounded-full text-[10px] font-black flex w-fit items-center gap-2 border border-[#1cb764]/10 shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-[#1cb764] animate-pulse"></div> Verified
                                            </span>
                                        @elseif($user->status_verifikasi == 'pending')
                                            <span class="px-4 py-2 bg-[#fcf3e8] text-[#f7b055] rounded-full text-[10px] font-black flex w-fit items-center gap-2 border border-[#f7b055]/10 shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-[#f7b055] animate-pulse"></div> Pending
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-red-50 text-red-500 rounded-full text-[10px] font-black flex w-fit items-center gap-2 border border-red-100 shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Ditolak
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-4 py-2 bg-[#eefcf4] text-[#1cb764] rounded-full text-[10px] font-black flex w-fit items-center gap-2 border border-[#1cb764]/10 shadow-sm">
                                            <div class="w-1.5 h-1.5 rounded-full bg-[#1cb764]"></div> Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button
                                            @click="showEditModal = true; userToEdit = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->Email) }}', alamat: '{{ addslashes($user->alamat ?? '') }}', foto_profil: '{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : '' }}' }; previewUrl = userToEdit.foto_profil;"
                                            class="w-10 h-10 flex items-center justify-center text-[#1cb764] bg-[#eefcf4] hover:bg-[#1cb764] hover:text-white rounded-xl transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.manajemen_pengguna.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                @click="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')"
                                                class="w-10 h-10 flex items-center justify-center text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="py-24 text-center text-gray-400 font-black text-xl">Belum ada pengguna terdaftar untuk kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <!-- MODAL EDIT -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-[3rem] shadow-2xl max-w-md w-full p-10 overflow-hidden z-10 max-h-[90vh] overflow-y-auto">
            <h3 class="text-3xl font-black text-gray-900 mb-8 flex items-center gap-4">
                <div class="p-3 bg-[#eefcf4] text-[#1cb764] rounded-2xl shadow-inner"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                Edit Profil
            </h3>
            <form :action="`/admin/manajemen-pengguna/${userToEdit?.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="mb-10 text-center">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 text-left">Foto Profil</label>
                    <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer" onclick="document.getElementById('foto_profil_input').click()">
                        <div class="w-32 h-32 rounded-[2.5rem] bg-gray-50 border-4 border-dashed border-gray-100 flex items-center justify-center text-gray-300 overflow-hidden group-hover:border-[#1cb764] group-hover:text-[#1cb764] transition-all duration-500 shadow-inner">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </template>
                        </div>
                        <input type="file" id="foto_profil_input" name="foto_profil" class="hidden" accept="image/*" @change="previewUrl = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : userToEdit.foto_profil">
                    </div>
                </div>

                <div class="space-y-6 mb-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-1">Nama</label>
                        <input type="text" name="name" x-model="userToEdit.name" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-800 focus:outline-none focus:border-[#1cb764] transition-all shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-1">Email</label>
                        <input type="email" name="email" x-model="userToEdit.email" class="w-full bg-gray-100 border-2 border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-400 cursor-not-allowed shadow-inner" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-1">Alamat</label>
                        <textarea name="alamat" x-model="userToEdit.alamat" rows="3" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:outline-none focus:border-[#1cb764] transition-all shadow-sm resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-5 bg-gray-100 hover:bg-gray-200 text-gray-500 font-black rounded-2xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-5 bg-[#1cb764] hover:bg-[#0a2e1f] text-white font-black rounded-2xl transition-all shadow-xl shadow-[#1cb764]/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "Anda akan menghapus '" + name + "'. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[3rem] p-10',
                title: 'text-2xl font-black text-gray-900',
                htmlContainer: 'text-gray-500 font-bold',
                confirmButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest text-xs',
                cancelButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Success Toast/Popup
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[2.5rem] p-8',
                title: 'text-xl font-black text-[#1cb764]',
                htmlContainer: 'text-gray-500 font-bold'
            }
        });
    @endif
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua parameter filter dari URL
        $statusFilter = $request->query('status', 'all');
        $searchKeyword = $request->query('search'); 
        $startDate = $request->query('start_date'); 
        $endDate = $request->query('end_date');     

        // 2. Ambil ID akun yang sedang login saat ini
        $userId = auth()->id();

        // 3. Query dasar + PROTEKSI: Hanya ambil data riwayat milik user yang sedang login
        $query = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->join('unit_bisnis_profiles', 'pesanans.unit_bisnis_id', '=', 'unit_bisnis_profiles.id')
            ->where('pesanans.user_id', $userId) // Proteksi riwayat per akun
            ->select(
                'pesanans.*', 
                'master_makanans.nama_makanan', 
                'master_makanans.kategori',
                'master_makanans.foto',
                'unit_bisnis_profiles.nama_usaha'        
            );

        // 4. Logic Filter Status 
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'menunggu_pembayaran') {
                $query->where('pesanans.status', 'menunggu_pembayaran'); 
            } elseif ($statusFilter === 'proses') {
                $query->whereIn('pesanans.status', ['proses', 'siap_diambil', 'dibayar']); 
            } elseif ($statusFilter === 'batal') {
                $query->where('pesanans.status', 'dibatalkan'); 
            } else {
                $query->where('pesanans.status', $statusFilter);
            }
        }

        // 5. Logic Fitur Pencarian (Mencari di dalam riwayat akun itu sendiri)
        if (!empty($searchKeyword)) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('master_makanans.nama_makanan', 'LIKE', '%' . $searchKeyword . '%')
                  ->orWhere('unit_bisnis_profiles.nama_usaha', 'LIKE', '%' . $searchKeyword . '%');
            });
        }

        // 6. Logic Filter Rentang Tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween(DB::raw('DATE(pesanans.waktu_pesan)'), [$startDate, $endDate]);
        }

        // 7. Eksekusi Pagination
        $pesanans = $query->orderBy('pesanans.waktu_pesan', 'desc')
                          ->paginate(7)
                          ->withQueryString(); 

        // =========================================================================
        // PERBAIKAN DI SINI: Dropdown Search dinamis HANYA dari riwayat milik sendiri
        // =========================================================================
        $daftarMakanan = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->where('pesanans.user_id', $userId) // Saring hanya yang pernah dipesan akun ini
            ->distinct()
            ->pluck('master_makanans.nama_makanan');

        $daftarUsaha = DB::table('pesanans')
            ->join('unit_bisnis_profiles', 'pesanans.unit_bisnis_id', '=', 'unit_bisnis_profiles.id')
            ->where('pesanans.user_id', $userId) // Saring hanya toko/mitra yang pernah berinteraksi dengan akun ini
            ->distinct()
            ->pluck('unit_bisnis_profiles.nama_usaha');

        // Gabungkan rekomendasi dan hilangkan nilai kosong/duplikat
        $searchSuggestions = $daftarMakanan->merge($daftarUsaha)->unique()->filter();

        // 8. Kirim data ke view Blade
        return view('user.riwayat', compact('pesanans', 'searchSuggestions'));
    }

    public function show($id)
    {
        $userId = auth()->id();

        $pesanan = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->join('unit_bisnis_profiles', 'pesanans.unit_bisnis_id', '=', 'unit_bisnis_profiles.id')
            ->select(
                'pesanans.*', 
                'master_makanans.nama_makanan', 
                'master_makanans.kategori',
                'master_makanans.foto',
                'unit_bisnis_profiles.nama_usaha'        
            )
            ->where('pesanans.id', $id)
            ->where('pesanans.user_id', $userId) // Proteksi detail riwayat agar tidak bisa diintip via URL
            ->first();

        if (!$pesanan) {
            return redirect()->route('riwayat.index')->with('error', 'Data riwayat tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $ratingTerisi = DB::table('ratings')->where('pesanan_id', $id)->first();

        return view('user.detail_riwayat', compact('pesanan', 'ratingTerisi'));
    }

    public function storeRating(Request $request, $id)
    {
        // 1. Validasi Input Form
        $request->validate([
            'skor_rating' => 'required|integer|between:1,5',
            'catatan_pengalaman' => 'nullable|string',
            'bukti_berbagi' => 'required|image|mimes:png,jpg,jpeg,mp4|max:5000',
        ]);

        // 2. Ambil data pesanan untuk mendapatkan unit_bisnis_id
        $pesanan = DB::table('pesanans')->where('id', $id)->first();
        if (!$pesanan) {
            return redirect()->route('user.riwayat')->with('error', 'Pesanan tidak ditemukan.');
        }

        // 3. Proses Upload Foto Bukti ke Storage
        $pathFoto = null;
        if ($request->hasFile('bukti_berbagi')) {
            $pathFoto = $request->file('bukti_berbagi')->store('bukti_berbagi', 'public');
        }

        // =========================================================================
        // ALUR BARU: PECAH DATA SESUAI MODEL & FUNGSINYA (BUKTI vs RATING)
        // =========================================================================

        // A. MASUK KE TABEL BUKTI DONASI (Khusus Foto Berkas Dokumentasi)
        DB::table('bukti_donasis')->insert([
            'pesanan_id' => $id,
            'foto'       => $pathFoto,
            'tanggal'    => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // B. MASUK KE TABEL RATINGS (Khusus Skor Bintang & Review Komentar)
        DB::table('ratings')->insert([
            'user_id'            => auth()->id(),
            'unit_bisnis_id'     => $pesanan->unit_bisnis_id,
            'pesanan_id'         => $id,
            'skor_rating'        => $request->skor_rating,
            'catatan_pengalaman' => $request->catatan_pengalaman,
            'nilai'              => $request->skor_rating, // Menjaga kompatibilitas kolom 'nilai'
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Log activity
        \App\Models\UserActivity::log(
            auth()->id(),
            'ulasan',
            'Ulasan Dikirim',
            'Memberikan ulasan bintang ' . $request->skor_rating . ' untuk pesanan di ' . ($pesanan->nama_usaha ?? 'Mitra') . '.'
        );

        return redirect()->route('user.riwayat')->with('success', 'Bukti donasi dan ulasan rating berhasil disimpan ke dalam sistem!');
    }
}
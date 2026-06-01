<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\MenuAktif;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * 1. Menampilkan Halaman Pembayaran QRIS (Berdasarkan ID Pesanan)
     */
    public function show(Request $request, string $id)
    {
        // Cari data transaksi langsung dari tabel pesanans asli
        $pesanan = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->join('unit_bisnis_profiles', 'pesanans.unit_bisnis_id', '=', 'unit_bisnis_profiles.id')
            ->join('users', 'unit_bisnis_profiles.user_id', '=', 'users.id')
            ->select(
                'pesanans.*',
                'master_makanans.nama_makanan',
                'master_makanans.foto',
                'unit_bisnis_profiles.nama_usaha',
                'users.name',
                'users.alamat',
                'users.latitude',
                'users.longitude'
            )
            ->where('pesanans.id', $id)
            ->firstOrFail();

        // Buat objek palsu penampung relasi agar kode Blade lamamu tidak pecah/error
        $makanan = new \stdClass();
        $makanan->harga_jual = $pesanan->total_harga / $pesanan->jumlah_porsi;
        $makanan->masterMakanan = (object) ['nama_makanan' => $pesanan->nama_makanan, 'foto' => $pesanan->foto];
        $makanan->unitBisnis = (object) ['user' => (object) [
            'name' => $pesanan->name,
            'alamat' => $pesanan->alamat,
            'latitude' => $pesanan->latitude,
            'longitude' => $pesanan->longitude
        ]];

        $qty = $pesanan->jumlah_porsi;
        $subtotal = $pesanan->total_harga;
        $ref = $pesanan->kode_unik; // Gunakan kode unik asli dari database (misal: TRX009)
        $slug = Str::slug($pesanan->nama_makanan);

        return view('user.pembayaran', compact('makanan', 'qty', 'subtotal', 'ref', 'slug', 'id'));
    }

    /**
     * 2. Memproses Simulasi Berhasil / Gagal (Update Status Database Toko)
     */
    public function store(Request $request, string $id)
    {
        $status = $request->input('status'); 

        if ($status === 'Berhasil') {
            // Ubah status di database lokal phpMyAdmin menjadi 'dibayar' atau 'proses'
            DB::table('pesanans')->where('id', $id)->update([
                'status' => 'proses',
                'updated_at' => now()
            ]);

            return redirect()->route('user.pembayaran.berhasil', $id);
        }

        return redirect()->route('user.riwayat')->with('status_pembayaran', 'Gagal');
    }

    /**
     * 3. Menampilkan Halaman Tiket/Pembayaran Berhasil
     */
    public function berhasil(Request $request, string $id)
    {
        // Tarik data asli dari baris database pesanans
        $pesanan = DB::table('pesanans')
            ->join('menu_aktifs', 'pesanans.menu_aktif_id', '=', 'menu_aktifs.id')
            ->join('master_makanans', 'menu_aktifs.master_makanan_id', '=', 'master_makanans.id')
            ->join('unit_bisnis_profiles', 'pesanans.unit_bisnis_id', '=', 'unit_bisnis_profiles.id')
            ->join('users', 'unit_bisnis_profiles.user_id', '=', 'users.id')
            ->select(
                'pesanans.*',
                'master_makanans.nama_makanan',
                'master_makanans.foto',
                'unit_bisnis_profiles.nama_usaha',
                'users.name',
                'users.alamat',
                'users.latitude',
                'users.longitude'
            )
            ->where('pesanans.id', $id)
            ->firstOrFail();

        // Buat objek palsu penampung relasi agar maps blade membaca data
        $makanan = new \stdClass();
        $makanan->batas_pengambilan = Carbon::parse($pesanan->waktu_pesan)->addHours(2); // Durasi penjemputan gerai
        $makanan->masterMakanan = (object) ['nama_makanan' => $pesanan->nama_makanan, 'foto' => $pesanan->foto];
        $makanan->unitBisnis = (object) ['user' => (object) [
            'name' => $pesanan->name,
            'alamat' => $pesanan->alamat,
            'latitude' => $pesanan->latitude,
            'longitude' => $pesanan->longitude
        ]];

        $qty = $pesanan->jumlah_porsi;
        $subtotal = $pesanan->total_harga;
        $kode_verifikasi = $pesanan->kode_unik; // Ambil kode unik asli dari database (misal: SB-0015)

        return view('user.pembayaran_berhasil', compact('makanan', 'qty', 'subtotal', 'kode_verifikasi', 'id'));
    }

    /**
     * Jalur HP saat scan QR Code simulator
     */
    public function simulasiScan($id)
    {
        Cache::put('scan_qris_' . $id, true, now()->addMinutes(5));
        
        return "<div style='font-family:sans-serif; text-align:center; padding-top:20vh; background-color:#F0F7F2; height:100vh;'>
                    <h1 style='color:#189347; font-size:32px; margin-bottom:10px;'>Pembayaran Berhasil! ✅</h1>
                    <p style='color:#666; font-size:16px;'>Silakan lihat layar laptop Anda, halaman akan otomatis berpindah.</p>
                </div>";
    }

    /**
     * Dipanggil AJAX otomatis setiap 2 detik
     */
    public function cekStatusScan($id)
    {
        if (Cache::has('scan_qris_' . $id)) {
            Cache::forget('scan_qris_' . $id);
            return response()->json(['status' => 'sukses']);
        }
        return response()->json(['status' => 'pending']);
    }
}
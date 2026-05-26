<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PembayaranController extends Controller
{
    /**
     * Menampilkan Halaman Pembayaran QRIS
     */
    public function show(Request $request, string $slug)
    {
        $qty = $request->input('qty', 1);

        $makanan = (object) [
            'nama'   => ucwords(str_replace('-', ' ', $slug)),
            'slug'   => $slug,
            'harga'  => 15000, 
            'resto'  => 'Healthy Garden Bistro',
            'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
            'gambar' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80',
            'batas_pengambilan' => '2026-05-24 17:30:00' 
        ];

        $subtotal = $makanan->harga * $qty;
        $ref = 'SB-' . strtoupper(substr(md5($slug . time()), 0, 8));

        return view('user.pembayaran', compact('makanan', 'qty', 'subtotal', 'ref'));
    }

    /**
     * Memproses Simulasi Berhasil / Gagal (Form & Timer Timeout)
     */
    public function store(Request $request, string $slug)
    {
        $status = $request->input('status'); 
        $qty = $request->input('qty', 1);

        if ($status === 'Berhasil') {
            return redirect()->route('user.pembayaran.berhasil', ['slug' => $slug, 'qty' => $qty]);
        }

        return redirect()->route('user.riwayat')->with('status_pembayaran', 'Gagal');
    }

    /**
     * Menampilkan Halaman Tiket/Pembayaran Berhasil
     */
    public function berhasil(Request $request, string $slug)
    {
        $qty = $request->input('qty', 1);

        $makanan = (object) [
            'nama'   => ucwords(str_replace('-', ' ', $slug)),
            'slug'   => $slug,
            'harga'  => 15000, 
            'resto'  => 'Healthy Garden Bistro',
            'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
            'gambar' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80',
            'batas_pengambilan' => '2026-05-24 17:30:00' 
        ];

        $subtotal = $makanan->harga * $qty;
        
        $kode_verifikasi = 'GP-' . rand(1000, 9999) . '-' . strtoupper(Str::random(3));

        return view('user.pembayaran_berhasil', compact('makanan', 'qty', 'subtotal', 'kode_verifikasi'));
    }

    /**
     * URL ini yang akan terbuka di HP saat QR Code di-scan
     */
    public function simulasiScan($slug)
    {
        // Menyimpan sinyal "SUKSES" di memori sementara Laravel (Cache) selama 5 menit
        Cache::put('scan_qris_' . $slug, true, now()->addMinutes(5));
        
        // Tampilan sederhana di layar HP saat di-scan
        return "<div style='font-family:sans-serif; text-align:center; padding-top:20vh; background-color:#F0F7F2; height:100vh;'>
                    <h1 style='color:#189347; font-size:32px; margin-bottom:10px;'>Pembayaran Berhasil! ✅</h1>
                    <p style='color:#666; font-size:16px;'>Silakan lihat layar laptop Anda, halaman akan otomatis berpindah.</p>
                </div>";
    }

    /**
     * URL ini dipanggil oleh laptop setiap 2 detik tanpa refresh layar
     */
    public function cekStatusScan($slug)
    {
        // Mengecek apakah ada sinyal "SUKSES" dari HP (Cache)
        if (Cache::has('scan_qris_' . $slug)) {
            Cache::forget('scan_qris_' . $slug); // Hapus sinyal agar tidak looping
            return response()->json(['status' => 'sukses']);
        }
        
        return response()->json(['status' => 'pending']);
    }
}
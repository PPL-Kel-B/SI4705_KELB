<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\MenuAktif;
use App\Models\Pesanan;    
use App\Models\Pembayaran; 
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Menampilkan Halaman Pembayaran QRIS (Status: Menunggu Pembayaran)
     */
    public function show(Request $request, string $slug)
    {
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        // Mengambil data makanan dari database
        $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])
                    ->whereHas('masterMakanan', function ($query) use ($slug) {
                        $query->where('nama_makanan', str_replace('-', ' ', $slug));
                    })
                    ->firstOrFail();

        $subtotal = $makanan->harga_jual * $qty;

        // 1. Simpan/Cek data ke tabel Pesanan
        // 1. Simpan/Cek data ke tabel Pesanan (Update jika ada, Create jika tidak)
        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'menunggu_pembayaran')
                    ->first();

        if ($pesanan) {
            // Jika pesanan gantung sudah ada, UPDATE porsi dan harga terbarunya
            $pesanan->update([
                'jumlah_porsi' => $qty,
                'total_harga'  => $subtotal,
                'waktu_pesan'  => now(),
            ]);
        } else {
            // Jika belum ada sama sekali, BUAT BARU
            $pesanan = Pesanan::create([
                'user_id'        => $user->id,
                'menu_aktif_id'  => $makanan->id,
                'status'         => 'menunggu_pembayaran',
                'unit_bisnis_id' => $makanan->unit_bisnis_id,
                'jumlah_porsi'   => $qty,
                'total_harga'    => $subtotal,
                'kode_unik'      => 'SB-' . rand(1000, 9999) . '-' . strtoupper(Str::random(3)),
                'waktu_pesan'    => now(),
            ]);
        }

        $ref = 'SB-' . strtoupper(substr(md5($pesanan->id . time()), 0, 8));

        // 2. Simpan data ke tabel Pembayaran
        $pembayaran = Pembayaran::firstOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'status' => 'menunggu', // Harus huruf kecil sesuai ENUM database
                'qrcode' => $ref,
            ]
        );

        // Gunakan kode qrcode dari database agar konsisten
        $ref = $pembayaran->qrcode; 

        return view('user.pembayaran', compact('makanan', 'qty', 'subtotal', 'ref', 'slug'));
    }

    /**
     * Memproses Simulasi Berhasil / Gagal (Form & Timer Timeout)
     */
    public function store(Request $request, string $slug)
    {
        $status = $request->input('status'); 
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        // Cari menu aktifnya
        $makanan = MenuAktif::whereHas('masterMakanan', function ($query) use ($slug) {
            $query->where('nama_makanan', str_replace('-', ' ', $slug));
        })->firstOrFail();

        // Cari pesanan yang sedang 'menunggu_pembayaran'
        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'menunggu_pembayaran')
                    ->first();

        if ($pesanan) {
            if ($status === 'Berhasil') {
                // Update tabel pesanans
                $pesanan->update(['status' => 'proses']); 
                
                // Update tabel pembayarans 
                Pembayaran::where('pesanan_id', $pesanan->id)->update([
                    'status' => 'berhasil', 
                    'waktu_bayar' => now(),
                ]);

                // ---> TAMBAHKAN 1 BARIS INI UNTUK MENGURANGI STOK <---
                $makanan->decrement('stok_porsi', $pesanan->jumlah_porsi);

                return redirect()->route('user.pembayaran.berhasil', ['slug' => $slug, 'qty' => $qty]);
            } else {
                // Update tabel pesanans
                $pesanan->update(['status' => 'dibatalkan']);
                
                // Update tabel pembayarans (huruf kecil sesuai ENUM)
                Pembayaran::where('pesanan_id', $pesanan->id)->update([
                    'status' => 'gagal', 
                ]);

                return redirect()->route('user.riwayat')->with('status_pembayaran', 'Gagal');
            }
        }

        return redirect()->route('user.riwayat');
    }

    /**
     * Menampilkan Halaman Tiket/Pembayaran Berhasil
     */
    public function berhasil(Request $request, string $slug)
    {
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        // Ambil data makanan
        $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])
                    ->whereHas('masterMakanan', function ($query) use ($slug) {
                        $query->where('nama_makanan', str_replace('-', ' ', $slug));
                    })
                    ->firstOrFail();

        // Ambil pesanan yang baru saja sukses (status 'dibayar')
        // Ambil pesanan yang baru saja sukses
        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'proses') // <--- UBAH 'dibayar' JADI 'proses'
                    ->latest()
                    ->firstOrFail();

        $subtotal = $pesanan->total_harga; 
        $kode_verifikasi = $pesanan->kode_unik; 
        $qty = $pesanan->jumlah_porsi; // <--- Mengambil porsi asli yang tersimpan di database 

        return view('user.pembayaran_berhasil', compact('makanan', 'qty', 'subtotal', 'kode_verifikasi', 'slug'));
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
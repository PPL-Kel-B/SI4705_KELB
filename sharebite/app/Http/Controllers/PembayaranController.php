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
    public function show(Request $request, string $id)
    {
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        // 1. Deteksi Cerdas (Ide Tim): Apakah ini ID Pesanan atau ID Menu?
        $pesananExist = Pesanan::where('id', $id)->where('user_id', $user->id)->first();

        if ($pesananExist) {
            // Jika masuk dari Riwayat (ID adalah ID Pesanan)
            $menuAktifId = $pesananExist->menu_aktif_id;
            $pesanan = $pesananExist;
        } else {
            // Jika masuk dari Dashboard (ID adalah ID Menu Aktif)
            $menuAktifId = $id;
            $pesanan = Pesanan::where('user_id', $user->id)
                            ->where('menu_aktif_id', $menuAktifId)
                            ->where('status', 'menunggu_pembayaran')
                            ->first();
        }

        // 2. Ambil data makanan berdasarkan ID yang sudah divalidasi
        $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])->findOrFail($menuAktifId);

        // Jika makanan diset gratis, subtotal 0
        if ($makanan->is_gratis == 1) { 
            $subtotal = 0;
        } else {
            $subtotal = $makanan->harga_jual * $qty;
        }

        // 3. Sinkronisasi atau pembuatan data pesanan baru
        if ($pesanan) {
            // Jika ada perubahan porsi saat refresh/masuk lagi, sesuaikan stoknya
            $selisih_porsi = $qty - $pesanan->jumlah_porsi;
            if ($selisih_porsi != 0) {
                $makanan->decrement('stok_porsi', $selisih_porsi);
            }
            
            // Selalu pastikan jumlah_porsi dan total_harga sinkron dengan state terpilih
            $pesanan->update([
                'jumlah_porsi' => $qty,
                'total_harga'  => $subtotal,
                'waktu_pesan'  => $pesanan->waktu_pesan,
            ]);
        } else {
            // KURANGI STOK SAAT BARU MASUK HALAMAN PEMBAYARAN
            $makanan->decrement('stok_porsi', $qty);

            do {
                $kodeBaru = 'SB-' . rand(100, 999) . '-' . strtoupper(Str::random(3));
            } while (Pesanan::where('kode_unik', $kodeBaru)->exists());

            $pesanan = Pesanan::create([
                'user_id'        => $user->id,
                'menu_aktif_id'  => $makanan->id,
                'status'         => 'menunggu_pembayaran',
                'unit_bisnis_id' => $makanan->unit_bisnis_id,
                'jumlah_porsi'   => $qty,
                'total_harga'    => $subtotal,
                'kode_unik'      => $kodeBaru, // Masukkan kode yang sudah dijamin unik
                'waktu_pesan'    => now(),
            ]);
        }

        $ref = 'SB-' . strtoupper(substr(md5($pesanan->id . time()), 0, 8));

        $pembayaran = Pembayaran::firstOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'status' => 'menunggu', 
                'qrcode' => $ref,
            ]
        );

        $ref = $pembayaran->qrcode; 

        $waktuPesan = \Carbon\Carbon::parse($pesanan->waktu_pesan);
        $deadline = $waktuPesan->copy()->addSeconds(900);
        $remainingSeconds = now()->greaterThanOrEqualTo($deadline) ? 0 : (int) ceil(now()->diffInSeconds($deadline));

        return view('user.pembayaran', compact('makanan', 'qty', 'subtotal', 'ref', 'id', 'remainingSeconds'));
    }

    public function store(Request $request, string $id)
    {
        $status = $request->input('status'); 
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        // Deteksi ID yang masuk untuk pencarian data pesanan yang valid
        $pesananExist = Pesanan::where('id', $id)->where('user_id', $user->id)->first();

        if ($pesananExist) {
            $pesanan = $pesananExist;
            $makanan = MenuAktif::findOrFail($pesanan->menu_aktif_id);
        } else {
            $makanan = MenuAktif::findOrFail($id);
            $pesanan = Pesanan::where('user_id', $user->id)
                            ->where('menu_aktif_id', $makanan->id)
                            ->where('status', 'menunggu_pembayaran')
                            ->first();
        }

        if ($pesanan) {
            if ($status === 'Berhasil') {
                $pesanan->update([
                    'status' => 'dibayar',
                    'waktu_pesan' => $pesanan->waktu_pesan,
                ]); 
                
                Pembayaran::where('pesanan_id', $pesanan->id)->update([
                    'status' => 'berhasil', 
                    'waktu_bayar' => now(),
                ]);

                // Stok TIDAK dikurangi lagi di sini karena sudah dikurangi di fungsi show()

                // Mengembalikan $id parameter asli agar redirect route mencocokkan URL asal
                return redirect()->route('user.pembayaran.berhasil', ['id' => $id, 'qty' => $qty]);
            } else {
                $pesanan->update([
                    'status' => 'dibatalkan',
                    'waktu_pesan' => $pesanan->waktu_pesan,
                ]);
                
                Pembayaran::where('pesanan_id', $pesanan->id)->update([
                    'status' => 'gagal', 
                ]);

                // KEMBALIKAN STOK karena pesanan batal/waktu habis
                $makanan->increment('stok_porsi', $pesanan->jumlah_porsi);

                return redirect()->route('user.riwayat')->with('status_pembayaran', 'Gagal');
            }
        }

        return redirect()->route('user.riwayat');
    }

    public function berhasil(Request $request, string $id)
    {
        $user = auth()->user();

        // Deteksi apakah ID dari halaman riwayat (ID Pesanan) atau dari alur pembayaran langsung (ID Menu)
        $pesananDirect = Pesanan::where('id', $id)->where('user_id', $user->id)->first();

        if ($pesananDirect) {
            $pesanan = $pesananDirect;
            $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])->findOrFail($pesanan->menu_aktif_id);
        } else {
            $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])->findOrFail($id);
            $pesanan = Pesanan::where('user_id', $user->id)
                        ->where('menu_aktif_id', $makanan->id)
                        // (Ide Kamu) Memastikan semua status sukses dikenali
                        ->whereIn('status', ['proses', 'siap_diambil', 'dibayar', 'diambil']) 
                        ->latest()
                        ->firstOrFail();
        }

        $subtotal = $pesanan->total_harga; 
        $kode_verifikasi = $pesanan->kode_unik; 
        $qty = $pesanan->jumlah_porsi; 

        return view('user.pembayaran_berhasil', compact('makanan', 'qty', 'subtotal', 'kode_verifikasi', 'id'));
    }

    public function simulasiScan($id)
    {
        Cache::put('scan_qris_' . $id, true, now()->addMinutes(5));
        
        return "<div style='font-family:sans-serif; text-align:center; padding-top:20vh; background-color:#F0F7F2; height:100vh;'>
                    <h1 style='color:#189347; font-size:32px; margin-bottom:10px;'>Pembayaran Berhasil! ✅</h1>
                    <p style='color:#666; font-size:16px;'>Silakan lihat layar laptop Anda, halaman akan otomatis berpindah.</p>
                </div>";
    }

    public function cekStatusScan($id)
    {
        if (Cache::has('scan_qris_' . $id)) {
            Cache::forget('scan_qris_' . $id); 
            return response()->json(['status' => 'sukses']);
        }
        return response()->json(['status' => 'pending']);
    }
}
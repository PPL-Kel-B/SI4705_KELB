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

        // Cari berdasarkan ID Menu Aktif 
        $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])->findOrFail($id);

        // Jika makanan diset gratis, subtotal 0
        if ($makanan->is_gratis == 1) { 
            $subtotal = 0;
        } else {
            $subtotal = $makanan->harga_jual * $qty;
        }

        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'menunggu_pembayaran')
                    ->first();

        if ($pesanan) {
            // Jika ada perubahan porsi saat refresh/masuk lagi, sesuaikan stoknya
            $selisih_porsi = $qty - $pesanan->jumlah_porsi;
            if ($selisih_porsi != 0) {
                $makanan->decrement('stok_porsi', $selisih_porsi);
            }

            $pesanan->update([
                'jumlah_porsi' => $qty,
                'total_harga'  => $subtotal,
                'waktu_pesan'  => now(),
            ]);
        } else {
            // KURANGI STOK SAAT BARU MASUK HALAMAN PEMBAYARAN
            $makanan->decrement('stok_porsi', $qty);

            do {
                $angkaAcak = mt_rand(100, 999); // Menghasilkan 3 angka (100 - 999)
                $hurufAcak = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3); // Menghasilkan 3 huruf kapital
                $kodeBaru = 'SB-' . $angkaAcak . '-' . $hurufAcak;
            } while (Pesanan::where('kode_unik', $kodeBaru)->exists()); // Ulangi terus jika kodenya sudah ada di database

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

        return view('user.pembayaran', compact('makanan', 'qty', 'subtotal', 'ref', 'id'));
    }

    public function store(Request $request, string $id)
    {
        $status = $request->input('status'); 
        $qty = $request->input('qty', 1);
        $user = auth()->user();

        $makanan = MenuAktif::findOrFail($id);

        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'menunggu_pembayaran')
                    ->first();

        if ($pesanan) {
            if ($status === 'Berhasil') {
                $pesanan->update(['status' => 'dibayar']); 
                
                Pembayaran::where('pesanan_id', $pesanan->id)->update([
                    'status' => 'berhasil', 
                    'waktu_bayar' => now(),
                ]);

                // Stok TIDAK dikurangi lagi di sini karena sudah dikurangi di fungsi show()

                return redirect()->route('user.pembayaran.berhasil', ['id' => $id, 'qty' => $qty]);
            } else {
                $pesanan->update(['status' => 'dibatalkan']);
                
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

        $makanan = MenuAktif::with(['masterMakanan', 'unitBisnis.user'])->findOrFail($id);

        $pesanan = Pesanan::where('user_id', $user->id)
                    ->where('menu_aktif_id', $makanan->id)
                    ->where('status', 'dibayar') 
                    ->latest()
                    ->firstOrFail();

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
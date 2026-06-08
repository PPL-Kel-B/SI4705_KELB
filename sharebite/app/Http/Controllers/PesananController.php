<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\UnitBisnisProfile;

class PesananController extends Controller
{
    public function index()
    {
        $unitBisnis = UnitBisnisProfile::where('user_id', auth()->id())->firstOrFail();

        // Auto-update expired orders
        Pesanan::updateExpiredOrders();

        $pesanans = Pesanan::with(['menuAktif.masterMakanan', 'user', 'pembayaran'])
            ->where('unit_bisnis_id', $unitBisnis->id)
            ->where(function($query) {
                $query->whereIn('status', ['dibayar', 'siap_diambil', 'selesai'])
                      ->orWhere(function($q) {
                          $q->where('status', 'dibatalkan')
                            ->whereHas('pembayaran', function($pQuery) {
                                $pQuery->where('status', 'berhasil');
                            });
                      });
            })
            ->orderByRaw("CASE WHEN status = 'selesai' OR status = 'dibatalkan' THEN 1 ELSE 0 END ASC")
            ->orderBy('created_at', 'desc')
            ->get();

        $menunggu = $pesanans->whereIn('status', ['dibayar', 'siap_diambil']);
        
        // Stat card "Selesai Distribusi" counts only successfully completed orders ('selesai')
        $selesai = $pesanans->where('status', 'selesai');

        // Perhitungan pesanan masuk hari ini
        $todayCount = Pesanan::where('unit_bisnis_id', $unitBisnis->id)
            ->where(function($query) {
                $query->whereIn('status', ['dibayar', 'siap_diambil', 'selesai'])
                      ->orWhere(function($q) {
                          $q->where('status', 'dibatalkan')
                            ->whereHas('pembayaran', function($pQuery) {
                                $pQuery->where('status', 'berhasil');
                            });
                      });
            })
            ->whereDate('waktu_pesan', today())
            ->count();

        $percentChangeText = '+' . $todayCount . ' pesanan masuk hari ini';

        return view('unit_bisnis.pesanan', compact('pesanans', 'menunggu', 'selesai', 'percentChangeText'));
    }

    public function create() {}
    public function store(Request $request) {}

    public function show(string $id)
    {
        Pesanan::updateExpiredOrders();

        $pesanan = Pesanan::with(['menuAktif.masterMakanan', 'user', 'pembayaran'])->findOrFail($id);

        $buyer = $pesanan->user;
        $tahun = $buyer->created_at ? $buyer->created_at->format('Y') : date('Y');
        $idPad = str_pad($buyer->id, 3, '0', STR_PAD_LEFT);
        $prefix = ($buyer->role === 'individu') ? 'ID' : 'KM';
        $volId = "{$prefix}-{$tahun}-{$idPad}";

        $totalPesananBuyer = Pesanan::where('user_id', $buyer->id)
            ->where('status', 'selesai')
            ->count();

        $averageRating = \App\Models\Rating::where('user_id', $buyer->id)->avg('skor_rating') 
            ?? \App\Models\Rating::where('user_id', $buyer->id)->avg('nilai') 
            ?? 5.0;

        return view('unit_bisnis.detail_pesanan', compact('pesanan', 'volId', 'totalPesananBuyer', 'averageRating'));
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        if (in_array($pesanan->status, ['dibayar', 'siap_diambil'])) {
            $pesanan->update([
                'status' => 'selesai', 
                'waktu_diambil' => now()
            ]);
            return redirect()->route('unit.riwayat.show', $pesanan->id)->with('success', 'Pesanan berhasil diserahkan!');
        }

        return back()->with('error', 'Gagal memverifikasi pesanan.');
    }

    public function destroy(string $id) {}

    public function verifikasi(Request $request)
    {
        $unitBisnis = UnitBisnisProfile::where('user_id', auth()->id())->firstOrFail();
        $averageRating = 0;
        $pesanan = null;
        $volId = null;
        $totalPesananBuyer = 0;

        // Auto-update expired orders
        Pesanan::updateExpiredOrders();

        if ($request->has('code')) {
            $fullCode = 'SB-' . strtoupper($request->code);
            
            $foundPesanan = Pesanan::with(['menuAktif.masterMakanan', 'user', 'pembayaran'])
                ->where('unit_bisnis_id', $unitBisnis->id)
                ->where('kode_unik', $fullCode)
                ->first();

            if ($foundPesanan) {
                if ($foundPesanan->isTidakDiambil() || ($foundPesanan->menuAktif && $foundPesanan->menuAktif->isKadaluarsa())) {
                    if ($foundPesanan->status !== 'dibatalkan') {
                        $foundPesanan->update(['status' => 'dibatalkan']);
                    }
                    return back()->with('error', 'Pesanan ini sudah kadaluarsa (tidak diambil oleh relawan)!');
                }

                if (!in_array($foundPesanan->status, ['dibayar', 'siap_diambil'])) {
                    return back()->with('error', 'Kode salah atau pesanan sudah diambil!');
                }

                $pesanan = $foundPesanan;
                $buyer = $pesanan->user;
                $tahun = $buyer->created_at ? $buyer->created_at->format('Y') : date('Y');
                $idPad = str_pad($buyer->id, 3, '0', STR_PAD_LEFT);
                $prefix = ($buyer->role === 'individu') ? 'ID' : 'KM';
                $volId = "{$prefix}-{$tahun}-{$idPad}";
                $totalPesananBuyer = Pesanan::where('user_id', $buyer->id)
                    ->where('status', 'selesai')
                    ->count();
                $averageRating = \App\Models\Rating::where('user_id', $buyer->id)->avg('skor_rating') 
                    ?? \App\Models\Rating::where('user_id', $buyer->id)->avg('nilai') 
                    ?? 5.0;
            } else {
                return back()->with('error', 'Kode salah atau pesanan sudah diambil!');
            }
        }

        return view('unit_bisnis.verifikasi_code', compact('pesanan', 'volId', 'totalPesananBuyer', 'averageRating'));
    }

    public function panduan()
    {
        return view('unit_bisnis.panduan_pengambilan');
    }
}
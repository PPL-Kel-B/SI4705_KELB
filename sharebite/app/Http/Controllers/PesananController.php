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

        $pesanans = Pesanan::with(['menuAktif.masterMakanan', 'user'])
            ->where('unit_bisnis_id', $unitBisnis->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $menunggu = $pesanans->where('status', 'dibayar');
        $selesai = $pesanans->whereIn('status', ['diambil', 'selesai']);

        return view('unit_bisnis.pesanan', compact('pesanans', 'menunggu', 'selesai'));
    }

    public function create() {}
    public function store(Request $request) {}

    public function show(string $id)
    {
        $pesanan = Pesanan::with(['menuAktif.masterMakanan', 'user'])->findOrFail($id);

        $buyer = $pesanan->user;
        $tahun = $buyer->created_at->format('Y');
        $idPad = str_pad($buyer->id, 3, '0', STR_PAD_LEFT);
        $prefix = ($buyer->role === 'individu') ? 'ID' : 'KM';
        $volId = "{$prefix}-{$tahun}-{$idPad}";

        $totalPesananBuyer = Pesanan::where('user_id', $buyer->id)
            ->whereIn('status', ['dibayar', 'diambil', 'selesai'])
            ->count();

        return view('unit_bisnis.detail_pesanan', compact('pesanan', 'volId', 'totalPesananBuyer'));
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        if ($pesanan->status == 'dibayar') {
            $pesanan->update([
                'status' => 'diambil', 
                'waktu_diambil' => now()
            ]);
            return redirect()->route('unit.pesanan.index')->with('success', 'Pesanan berhasil diserahkan!');
        }

        return back()->with('error', 'Gagal memverifikasi pesanan.');
    }

    public function destroy(string $id) {}

    public function verifikasi(Request $request)
    {
        $unitBisnis = UnitBisnisProfile::where('user_id', auth()->id())->firstOrFail();
        $pesanan = null;
        $volId = null;
        $totalPesananBuyer = 0;

        if ($request->has('code')) {
            $fullCode = 'SB-' . strtoupper($request->code);
            
            $pesanan = Pesanan::with(['menuAktif.masterMakanan', 'user'])
                ->where('unit_bisnis_id', $unitBisnis->id)
                ->where('kode_unik', $fullCode)
                ->where('status', 'dibayar')
                ->first();

            if ($pesanan) {
                $buyer = $pesanan->user;
                $tahun = $buyer->created_at->format('Y');
                $idPad = str_pad($buyer->id, 3, '0', STR_PAD_LEFT);
                $prefix = ($buyer->role === 'individu') ? 'ID' : 'KM';
                $volId = "{$prefix}-{$tahun}-{$idPad}";
                $totalPesananBuyer = Pesanan::where('user_id', $buyer->id)->whereIn('status', ['dibayar', 'diambil', 'selesai'])->count();
            } else {
                return back()->with('error', 'Kode salah atau pesanan sudah diambil!');
            }
        }

        return view('unit_bisnis.verifikasi_code', compact('pesanan', 'volId', 'totalPesananBuyer'));
    }
}
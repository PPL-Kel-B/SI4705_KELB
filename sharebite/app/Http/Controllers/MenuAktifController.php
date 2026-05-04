<?php

namespace App\Http\Controllers;

use App\Models\MenuAktif;
use App\Models\MasterMakanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MenuAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->unitBisnisProfile;

        if (!$profile) {
            $profile = \App\Models\UnitBisnisProfile::create([
                'user_id' => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha' => 'Restoran',
                'alamat' => $user->alamat ?? '-',
            ]);
        }

        $query = MenuAktif::with('masterMakanan')
            ->where('unit_bisnis_id', $profile->id)
            ->where('status', 'aktif');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('masterMakanan', function ($q) use ($search) {
                $q->where('nama_makanan', 'like', "%{$search}%");
            });
        }

        $menuAktifs = $query->latest()->get();

        // Calculate stats
        $totalMenuAktif = MenuAktif::where('unit_bisnis_id', $profile->id)
            ->where('status', 'aktif')
            ->where('stok_porsi', '>', 0)
            ->where('batas_pengambilan', '>', Carbon::now())
            ->count();
            
        $totalMenuHabis = MenuAktif::where('unit_bisnis_id', $profile->id)
            ->where('status', 'aktif')
            ->where(function($q) {
                $q->where('stok_porsi', '<=', 0)
                  ->orWhere('batas_pengambilan', '<=', Carbon::now());
            })
            ->count();
            
        // For 'Porsi Terjual Hari Ini', calculate from pesanan
        $porsiTerjualHariIni = \App\Models\Pesanan::where('unit_bisnis_id', $profile->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('jumlah_porsi');

        return view('unit_bisnis.kelola_makanan.menu_aktif', compact(
            'menuAktifs', 'totalMenuAktif', 'totalMenuHabis', 'porsiTerjualHariIni'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $profile = $user->unitBisnisProfile;

        if (!$profile) {
            $profile = \App\Models\UnitBisnisProfile::create([
                'user_id' => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha' => 'Restoran',
                'alamat' => $user->alamat ?? '-',
            ]);
        }

        // Get Master Makanan and map them for JS
        $masterMakanans = MasterMakanan::where('unit_bisnis_id', $profile->id)
            ->latest()
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_makanan' => $item->nama_makanan,
                    'kategori' => $item->kategori,
                    'deskripsi' => $item->deskripsi ?? 'Tidak ada deskripsi',
                    'harga' => $item->harga,
                    'foto_url' => $item->foto ? asset('storage/' . $item->foto) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80',
                ];
            });

        return view('unit_bisnis.kelola_makanan.tambah_menu_aktif', compact('masterMakanans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_makanan_id' => 'required|exists:master_makanans,id',
            'is_gratis' => 'nullable|boolean',
            'stok_porsi' => 'required|integer|min:1',
            'batas_pengambilan' => 'required', // format HH:MM
        ]);

        $user = auth()->user();
        $profile = $user->unitBisnisProfile;

        $masterMakanan = MasterMakanan::findOrFail($request->master_makanan_id);
        
        // Ensure the master makanan belongs to this unit bisnis
        if ($masterMakanan->unit_bisnis_id !== $profile->id) {
            abort(403, 'Unauthorized action.');
        }

        $isGratis = $request->has('is_gratis') ? true : false;
        
        // Parse the time string (HH:MM) to a datetime string for today
        $batasWaktu = Carbon::createFromFormat('H:i', $request->batas_pengambilan);
        
        // If the selected time has passed today, assume it's for tomorrow
        if ($batasWaktu->isPast()) {
            $batasWaktu->addDay();
        }

        MenuAktif::create([
            'master_makanan_id' => $masterMakanan->id,
            'unit_bisnis_id' => $profile->id,
            'is_gratis' => $isGratis,
            'harga_jual' => $masterMakanan->harga, // Save current price at time of publication
            'stok_porsi' => $request->stok_porsi,
            'batas_pengambilan' => $batasWaktu,
            'status' => 'aktif',
        ]);

        return redirect()->route('unit.kelola_makanan')
                        ->with('success', 'Menu berhasil diaktifkan');
    }
}

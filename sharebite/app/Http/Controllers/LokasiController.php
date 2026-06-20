<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitBisnisProfile;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
    /**
     * Menampilkan view peta lokasi
     */
    public function index()
    {
        return view('user.lokasi');
    }

    /**
     * Menyimpan lokasi GPS user ke database
     */
    public function simpanLokasi(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = auth()->user();
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('user.lokasi')->with('success', 'Lokasi berhasil disimpan!');
    }


    /**
     * API untuk mendapatkan unit bisnis terdekat (dalam radius tertentu)
     */
    public function getTerdekat(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $kategori = $request->query('kategori'); // Hotel, Restoran, Cafe, Lainnya
        $search = $request->query('search'); // Tambahan parameter pencarian
        $radius = 10; // 10 kilometer

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and longitude are required'], 400);
        }

        // Haversine formula
        $haversine = "(6371 * acos(cos(radians($lat)) 
                     * cos(radians(lokasi_lat)) 
                     * cos(radians(lokasi_lng) - radians($lng)) 
                     + sin(radians($lat)) 
                     * sin(radians(lokasi_lat))))";

        $query = UnitBisnisProfile::select('unit_bisnis_profiles.*')
            ->selectRaw("{$haversine} AS distance")
            ->whereNotNull('lokasi_lat')
            ->whereNotNull('lokasi_lng')
            ->having('distance', '<=', $radius)
            ->orderBy('distance');

        if ($kategori) {
            if ($kategori === 'Lainnya') {
                $query->whereNotIn('jenis_usaha', ['Hotel', 'Restoran', 'Cafe', 'Kafe']);
            } else if ($kategori === 'Cafe' || $kategori === 'Kafe') {
                $query->whereIn('jenis_usaha', ['Cafe', 'Kafe']);
            } else {
                $query->where('jenis_usaha', $kategori);
            }
        }

        if ($search) {
            $query->where('nama_usaha', 'like', '%' . $search . '%');
        }

        $unitBisnis = $query->get();

        return response()->json($unitBisnis);
    }
}

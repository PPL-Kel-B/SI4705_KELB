<?php

namespace App\Http\Controllers;

use App\Models\MasterMakanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterMakananController extends Controller
{
    /**
     * Display a listing of master makanan.
     */
    public function index(Request $request)
    {
        $user    = auth()->user();
        $profile = $user->unitBisnisProfile;

        if (!$profile) {
            $profile = \App\Models\UnitBisnisProfile::create([
                'user_id'    => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha'=> 'Restoran',
            ]);
        }

        $query = MasterMakanan::where('unit_bisnis_id', $profile->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_makanan', 'like', "%{$search}%");
        }

        $makanans = $query->latest()->paginate(12)->withQueryString();

        return view('unit_bisnis.kelola_makanan.master_data_index', compact('makanans'));
    }

    /**
     * Show the form for creating a new master makanan.
     */
    public function create()
    {
        return view('unit_bisnis.kelola_makanan.master_data_create');
    }

    /**
     * Store a newly created master makanan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Makanan' => 'required|string|max:255',
            'Kategori'     => 'required|string|max:100',
            'Foto'         => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'deskripsi'    => 'nullable|string',
            'Harga'        => 'required|string',
            'Berat'        => 'required|numeric|min:0',
        ]);

        $user    = auth()->user();
        $profile = $user->unitBisnisProfile;

        if (!$profile) {
            $profile = \App\Models\UnitBisnisProfile::create([
                'user_id'    => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha'=> 'Restoran',
            ]);
        }

        // Clean formatted rupiah → integer
        $harga = (int) str_replace(['.', ','], ['', ''], $request->Harga);

        $fotoPath = null;
        if ($request->hasFile('Foto')) {
            $fotoPath = $request->file('Foto')->store('master_makanan', 'public');
        }

        MasterMakanan::create([
            'unit_bisnis_id' => $profile->id,
            'nama_makanan'   => $request->Nama_Makanan,
            'kategori'       => $request->Kategori,
            'foto'           => $fotoPath,
            'deskripsi'      => $request->deskripsi,
            'harga'          => $harga,
            'berat'          => $request->Berat,
        ]);

        return redirect()->route('unit.master_data.index')
                         ->with('success', 'Master menu berhasil ditambahkan!');
    }
}

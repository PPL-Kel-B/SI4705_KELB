<?php

namespace App\Http\Controllers;

use App\Models\MasterMakanan;
use Illuminate\Http\Request;

class KelolaMasterDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterMakanan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_makanan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $makanans = $query->latest()->paginate(12)->withQueryString();
        return view('kelola-master-data.index', compact('makanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelola-master-data.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Makanan'            => 'required|string|max:100',
            'Kategori'                => 'required|string|max:50',
            'deskripsi'               => 'nullable|string',
            'Harga'                   => 'required|numeric|min:0',
            'Berat'                   => 'required|numeric|min:0',
            'Jumlah_porsi'            => 'nullable|integer|min:0',
            'Status'                  => 'nullable|string',
            'Batas_waktu_pengambilan' => 'nullable|date',
            'Foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_makanan'   => $request->Nama_Makanan,
            'kategori'       => $request->Kategori,
            'deskripsi'      => $request->deskripsi,
            'harga'          => $request->Harga,
            'berat'          => $request->Berat,
            'unit_bisnis_id' => null, // Diisi setelah integrasi unit bisnis
        ];

        if ($request->hasFile('Foto')) {
            $file      = $request->file('Foto');
            $filename  = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/master-makanan'), $filename);
            $data['foto'] = 'master-makanan/' . $filename;
        }

        MasterMakanan::create($data);

        return redirect()->route('kelola-master-data.index')
                        ->with('success', 'Master data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterMakanan $kelolaMasterDatum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterMakanan $kelolaMasterDatum)
    {
        return view('kelola-master-data.edit', ['makanan' => $kelolaMasterDatum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterMakanan $kelolaMasterDatum)
    {
        $request->validate([
            'Nama_Makanan'            => 'required|string|max:100',
            'Kategori'                => 'required|string|max:50',
            'deskripsi'               => 'nullable|string',
            'Harga'                   => 'required|numeric|min:0',
            'Berat'                   => 'required|numeric|min:0',
            'Jumlah_porsi'            => 'nullable|integer|min:0',
            'Status'                  => 'nullable|string',
            'Batas_waktu_pengambilan' => 'nullable|date',
            'Foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_makanan' => $request->Nama_Makanan,
            'kategori'     => $request->Kategori,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->Harga,
            'berat'        => $request->Berat,
        ];

        if ($request->hasFile('Foto')) {
            $file      = $request->file('Foto');
            $filename  = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/master-makanan'), $filename);
            $data['foto'] = 'master-makanan/' . $filename;
        }

        $kelolaMasterDatum->update($data);

        return redirect()->route('kelola-master-data.index')
                        ->with('success', 'Master data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterMakanan $kelolaMasterDatum)
    {
        $kelolaMasterDatum->delete();

        return redirect()->route('kelola-master-data.index')
                        ->with('success', 'Master data berhasil dihapus');
    }
}

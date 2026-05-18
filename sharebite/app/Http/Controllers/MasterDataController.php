<?php

namespace App\Http\Controllers;

use App\Models\MasterMakanan;
use App\Models\UnitBisnisProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->unitBisnisProfile;

        if (!$profile) {
            $profile = UnitBisnisProfile::create([
                'user_id' => $user->id,
                'nama_usaha' => $user->name,
                'jenis_usaha' => 'Restoran',
                'alamat' => $user->alamat ?? '-',
            ]);
        }

        $query = MasterMakanan::where('unit_bisnis_id', $profile->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_makanan', 'like', "%{$search}%");
        }

        $makanans = $query->latest()->paginate(12);

        return view('unit_bisnis.kelola_makanan.master_data_index', compact('makanans'));
    }

    public function create()
    {
        return view('unit_bisnis.kelola_makanan.master_data_create');
    }

    public function store(Request $request)
    {
        // Clean Harga formatting before validation
        if ($request->has('Harga')) {
            $request->merge([
                'Harga' => str_replace('.', '', $request->Harga)
            ]);
        }

        $request->validate([
            'Nama_Makanan' => 'required|string|max:255',
            'Kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'Harga' => 'required|numeric|min:0|max:1000000',
            'Berat' => 'nullable|numeric|min:0|max:10',
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'Harga.max' => 'Harga tidak boleh lebih dari Rp 1.000.000',
            'Berat.max' => 'Berat tidak boleh lebih dari 10 kg',
        ]);

        $user = auth()->user();
        $profile = $user->unitBisnisProfile;

        $data = [
            'unit_bisnis_id' => $profile->id,
            'nama_makanan' => $request->Nama_Makanan,
            'kategori' => $request->Kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->Harga,
            'berat' => $request->Berat,
        ];

        if ($request->hasFile('Foto')) {
            $data['foto'] = $request->file('Foto')->store('makanan_images', 'public');
        }

        MasterMakanan::create($data);

        return redirect()->route('unit.master_data.index')->with('success', 'Master data makanan berhasil ditambahkan!');
    }

    public function edit(MasterMakanan $master_datum)
    {
        // Parameter automatically injected
        $user = auth()->user();
        if ($master_datum->unit_bisnis_id !== $user->unitBisnisProfile->id) {
            abort(403);
        }

        return view('unit_bisnis.kelola_makanan.master_data_edit', compact('master_datum'));
    }

    public function update(Request $request, MasterMakanan $master_datum)
    {
        $user = auth()->user();
        if ($master_datum->unit_bisnis_id !== $user->unitBisnisProfile->id) {
            abort(403);
        }

        // Clean Harga formatting before validation
        if ($request->has('Harga')) {
            $request->merge([
                'Harga' => str_replace('.', '', $request->Harga)
            ]);
        }

        $request->validate([
            'Nama_Makanan' => 'required|string|max:255',
            'Kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'Harga' => 'required|numeric|min:0|max:1000000',
            'Berat' => 'nullable|numeric|min:0|max:10',
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'Harga.max' => 'Harga tidak boleh lebih dari Rp 1.000.000',
            'Berat.max' => 'Berat tidak boleh lebih dari 10 kg',
        ]);

        $data = [
            'nama_makanan' => $request->Nama_Makanan,
            'kategori' => $request->Kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->Harga,
            'berat' => $request->Berat,
        ];

        if ($request->hasFile('Foto')) {
            if ($master_datum->foto && Storage::disk('public')->exists($master_datum->foto)) {
                Storage::disk('public')->delete($master_datum->foto);
            }
            $data['foto'] = $request->file('Foto')->store('makanan_images', 'public');
        }

        $master_datum->update($data);

        return redirect()->route('unit.master_data.index')->with('success', 'Master data makanan berhasil diubah!');
    }

    public function destroy(MasterMakanan $master_datum)
    {
        $user = auth()->user();
        if ($master_datum->unit_bisnis_id !== $user->unitBisnisProfile->id) {
            abort(403);
        }

        if ($master_datum->foto && Storage::disk('public')->exists($master_datum->foto)) {
            Storage::disk('public')->delete($master_datum->foto);
        }

        // Menghapus semua menu aktif yang berhubungan dengan master data ini
        $master_datum->menuAktifs()->delete();

        $master_datum->delete();

        return redirect()->route('unit.master_data.index')->with('success', 'Master data makanan berhasil dihapus!');
    }
}

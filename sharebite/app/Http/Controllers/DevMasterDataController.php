<?php

namespace App\Http\Controllers;

use App\Models\MasterMakanan;
use App\Models\UnitBisnisProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * DevMasterDataController
 * Controller khusus untuk testing/review tanpa login.
 * Menggunakan unit_bisnis_id pertama yang tersedia di database.
 * HAPUS atau nonaktifkan routes /dev/* sebelum production!
 */
class DevMasterDataController extends Controller
{
    /**
     * Ambil profil unit bisnis pertama yang ada, atau buat dummy jika belum ada.
     */
    private function getDevProfile(): UnitBisnisProfile
    {
        $profile = UnitBisnisProfile::first();

        if (!$profile) {
            // Buat user dummy jika belum ada sama sekali
            $user = \App\Models\User::firstOrCreate(
                ['email' => 'dev@sharebite.test'],
                [
                    'name'     => 'Dev Unit Bisnis',
                    'password' => bcrypt('password'),
                    'role'     => 'unit_bisnis',
                    'no_hp'    => '08000000000',
                ]
            );

            $profile = UnitBisnisProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_usaha'  => 'Dev Unit Bisnis',
                    'jenis_usaha' => 'Restoran',
                    'status_verifikasi' => 'terverifikasi',
                ]
            );
        }

        return $profile;
    }

    public function index(Request $request)
    {
        $profile = $this->getDevProfile();

        $query = MasterMakanan::where('unit_bisnis_id', $profile->id);

        if ($request->filled('search')) {
            $query->where('nama_makanan', 'like', '%' . $request->search . '%');
        }

        $makanans = $query->latest()->paginate(12)->withQueryString();

        return view('unit_bisnis.kelola_makanan.master_data_index', compact('makanans'));
    }

    public function create()
    {
        return view('unit_bisnis.kelola_makanan.master_data_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Makanan' => 'required|string|max:255',
            'Kategori'     => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'Harga'        => 'required|string',
            'Berat'        => 'nullable|numeric|min:0',
            'Foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $profile = $this->getDevProfile();
        $hargaClean = (int) str_replace(['.', ','], ['', ''], $request->Harga);

        $data = [
            'unit_bisnis_id' => $profile->id,
            'nama_makanan'   => $request->Nama_Makanan,
            'kategori'       => $request->Kategori,
            'deskripsi'      => $request->deskripsi,
            'harga'          => $hargaClean,
            'berat'          => $request->Berat,
        ];

        if ($request->hasFile('Foto')) {
            $data['foto'] = $request->file('Foto')->store('makanan_images', 'public');
        }

        MasterMakanan::create($data);

        return redirect()->route('dev.master_data.index')
                         ->with('success', 'Master data berhasil ditambahkan!');
    }

    public function edit(MasterMakanan $master_datum)
    {
        return view('unit_bisnis.kelola_makanan.master_data_edit', compact('master_datum'));
    }

    public function update(Request $request, MasterMakanan $master_datum)
    {
        $request->validate([
            'Nama_Makanan' => 'required|string|max:255',
            'Kategori'     => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'Harga'        => 'required|string',
            'Berat'        => 'nullable|numeric|min:0',
            'Foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $hargaClean = (int) str_replace(['.', ','], ['', ''], $request->Harga);

        $data = [
            'nama_makanan' => $request->Nama_Makanan,
            'kategori'     => $request->Kategori,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $hargaClean,
            'berat'        => $request->Berat,
        ];

        if ($request->hasFile('Foto')) {
            if ($master_datum->foto && Storage::disk('public')->exists($master_datum->foto)) {
                Storage::disk('public')->delete($master_datum->foto);
            }
            $data['foto'] = $request->file('Foto')->store('makanan_images', 'public');
        }

        $master_datum->update($data);

        return redirect()->route('dev.master_data.index')
                         ->with('success', 'Master data berhasil diubah!');
    }

    public function destroy(MasterMakanan $master_datum)
    {
        if ($master_datum->foto && Storage::disk('public')->exists($master_datum->foto)) {
            Storage::disk('public')->delete($master_datum->foto);
        }

        $master_datum->menuAktifs()->delete();
        $master_datum->delete();

        return redirect()->route('dev.master_data.index')
                         ->with('success', 'Master data berhasil dihapus!');
    }
}

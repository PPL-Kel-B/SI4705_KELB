<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    /**
     * Tampilkan daftar master data makanan.
     */
    public function index(Request $request)
    {
        try {
            $query = Makanan::query();

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('Nama_Makanan', 'like', "%{$search}%")
                      ->orWhere('Kategori', 'like', "%{$search}%");
                });
            }

            $makanans = $query->latest()->paginate(12)->withQueryString();

            return view('unit_bisnis.kelolamasterdata.index', compact('makanans'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form tambah master data makanan baru.
     */
    public function create()
    {
        return view('unit_bisnis.kelolamasterdata.create');
    }

    /**
     * Simpan master data makanan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Makanan'            => 'required|string|max:100',
            'Kategori'                => 'required|string|max:50',
            'Berat'                   => 'required|numeric|min:0',
            'Jumlah_porsi'            => 'required|integer|min:0',
            'Harga'                   => 'required|numeric|min:0',
            'Batas_waktu_pengambilan' => 'required|date',
            'Status'                  => 'required|string|max:20',
            'Foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'Nama_Makanan.required'            => 'Nama makanan wajib diisi.',
            'Kategori.required'                => 'Kategori wajib diisi.',
            'Berat.required'                   => 'Berat wajib diisi.',
            'Berat.numeric'                    => 'Berat harus berupa angka.',
            'Jumlah_porsi.required'            => 'Jumlah porsi wajib diisi.',
            'Harga.required'                   => 'Harga wajib diisi.',
            'Batas_waktu_pengambilan.required' => 'Batas waktu pengambilan wajib diisi.',
            'Status.required'                  => 'Status wajib diisi.',
            'Foto.image'                       => 'File harus berupa gambar.',
            'Foto.max'                         => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $data = $request->only([
                'Nama_Makanan',
                'Kategori',
                'Berat',
                'Jumlah_porsi',
                'Harga',
                'Batas_waktu_pengambilan',
                'Status',
            ]);

            // Handle upload foto
            if ($request->hasFile('Foto')) {
                $data['Foto'] = $request->file('Foto')->store('makanan', 'public');
            }

            // Jika ada UnitBisnisID dari session/auth, set di sini
            // $data['UnitBisnisID'] = auth()->id(); // sesuaikan dengan auth Anda

            Makanan::create($data);

            return redirect()->route('kelolamasterdata.index')
                ->with('success', 'Master data makanan "' . $data['Nama_Makanan'] . '" berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit master data makanan.
     */
    public function edit($id)
    {
        try {
            $makanan = Makanan::findOrFail($id);
            return view('unit_bisnis.kelolamasterdata.edit', compact('makanan'));
        } catch (\Exception $e) {
            return redirect()->route('kelolamasterdata.index')
                ->with('error', 'Data makanan tidak ditemukan.');
        }
    }

    /**
     * Update data makanan di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Makanan'            => 'required|string|max:100',
            'Kategori'                => 'required|string|max:50',
            'Berat'                   => 'required|numeric|min:0',
            'Jumlah_porsi'            => 'required|integer|min:0',
            'Harga'                   => 'required|numeric|min:0',
            'Batas_waktu_pengambilan' => 'required|date',
            'Status'                  => 'required|string|max:20',
            'Foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'Nama_Makanan.required'            => 'Nama makanan wajib diisi.',
            'Kategori.required'                => 'Kategori wajib diisi.',
            'Berat.required'                   => 'Berat wajib diisi.',
            'Berat.numeric'                    => 'Berat harus berupa angka.',
            'Jumlah_porsi.required'            => 'Jumlah porsi wajib diisi.',
            'Harga.required'                   => 'Harga wajib diisi.',
            'Batas_waktu_pengambilan.required' => 'Batas waktu pengambilan wajib diisi.',
            'Status.required'                  => 'Status wajib diisi.',
            'Foto.image'                       => 'File harus berupa gambar.',
            'Foto.max'                         => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $makanan = Makanan::findOrFail($id);

            $data = $request->only([
                'Nama_Makanan',
                'Kategori',
                'Berat',
                'Jumlah_porsi',
                'Harga',
                'Batas_waktu_pengambilan',
                'Status',
            ]);

            // Handle upload foto
            if ($request->hasFile('Foto')) {
                // Hapus foto lama jika ada
                if ($makanan->Foto && Storage::disk('public')->exists($makanan->Foto)) {
                    Storage::disk('public')->delete($makanan->Foto);
                }
                $data['Foto'] = $request->file('Foto')->store('makanan', 'public');
            }

            $makanan->update($data);

            return redirect()->route('kelolamasterdata.index')
                ->with('success', 'Data makanan "' . $makanan->Nama_Makanan . '" berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data makanan dari database.
     */
    public function destroy($id)
    {
        try {
            $makanan = Makanan::findOrFail($id);
            $nama    = $makanan->Nama_Makanan;

            // Hapus foto dari storage jika ada
            if ($makanan->Foto && Storage::disk('public')->exists($makanan->Foto)) {
                Storage::disk('public')->delete($makanan->Foto);
            }

            $makanan->delete();

            return redirect()->route('kelolamasterdata.index')
                ->with('success', 'Data makanan "' . $nama . '" berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kelolamasterdata.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MasterMakanan;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the foods (Master Makanan).
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MasterMakanan::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_makanan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $foods = $query->latest()->paginate(12)->withQueryString();

        return view('foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new food.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('foods.create');
    }

    /**
     * Store a newly created food in storage.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_makanan'            => 'required|string|max:100',
            'kategori'                => 'required|string|max:50',
            'deskripsi'               => 'nullable|string',
            'harga'                   => 'required|numeric|min:0',
            'berat'                   => 'required|numeric|min:0',
            'jumlah_porsi'            => 'nullable|integer|min:0',
            'status'                  => 'nullable|string',
            'batas_waktu_pengambilan' => 'nullable|date',
            'foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_makanan'   => $validated['nama_makanan'],
            'kategori'       => $validated['kategori'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'harga'          => $validated['harga'],
            'berat'          => $validated['berat'],
            'unit_bisnis_id' => null, // Diisi setelah integrasi unit bisnis
        ];

        // Handle image upload
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/master-makanan'), $filename);
            $data['foto'] = 'master-makanan/' . $filename;
        }

        MasterMakanan::create($data);

        return redirect()->route('foods.index')
                        ->with('success', 'Menu makanan berhasil ditambahkan');
    }

    /**
     * Display the specified food.
     * 
     * @param MasterMakanan $food
     * @return \Illuminate\View\View
     */
    public function show(MasterMakanan $food)
    {
        return view('foods.show', compact('food'));
    }

    /**
     * Show the form for editing the specified food.
     * 
     * @param MasterMakanan $food
     * @return \Illuminate\View\View
     */
    public function edit(MasterMakanan $food)
    {
        return view('foods.edit', compact('food'));
    }

    /**
     * Update the specified food in storage.
     * 
     * @param Request $request
     * @param MasterMakanan $food
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MasterMakanan $food)
    {
        $validated = $request->validate([
            'nama_makanan'            => 'required|string|max:100',
            'kategori'                => 'required|string|max:50',
            'deskripsi'               => 'nullable|string',
            'harga'                   => 'required|numeric|min:0',
            'berat'                   => 'required|numeric|min:0',
            'jumlah_porsi'            => 'nullable|integer|min:0',
            'status'                  => 'nullable|string',
            'batas_waktu_pengambilan' => 'nullable|date',
            'foto'                    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_makanan' => $validated['nama_makanan'],
            'kategori'     => $validated['kategori'],
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'harga'        => $validated['harga'],
            'berat'        => $validated['berat'],
        ];

        // Handle image upload
        if ($request->hasFile('foto')) {
            // Delete old image if exists
            if ($food->foto && file_exists(public_path('storage/' . $food->foto))) {
                unlink(public_path('storage/' . $food->foto));
            }

            $file     = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/master-makanan'), $filename);
            $data['foto'] = 'master-makanan/' . $filename;
        }

        $food->update($data);

        return redirect()->route('foods.index')
                        ->with('success', 'Menu makanan berhasil diperbarui');
    }

    /**
     * Remove the specified food from storage.
     * 
     * @param MasterMakanan $food
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MasterMakanan $food)
    {
        // Delete image if exists
        if ($food->foto && file_exists(public_path('storage/' . $food->foto))) {
            unlink(public_path('storage/' . $food->foto));
        }

        $food->delete();

        return redirect()->route('foods.index')
                        ->with('success', 'Menu makanan berhasil dihapus');
    }
}

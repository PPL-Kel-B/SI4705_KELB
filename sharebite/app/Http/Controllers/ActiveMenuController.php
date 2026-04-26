<?php

namespace App\Http\Controllers;

use App\Models\ActiveMenu;
use App\Models\Makanan;
use App\Models\Unitbisnis;
use Illuminate\Http\Request;

class ActiveMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Assuming user is from unit bisnis - use correct attribute name
        $unitBisnisId = auth()->user()->UnitBisnisID ?? 1;
        
        $activeMenus = ActiveMenu::where('unit_bisnis_id', $unitBisnisId)
            ->with('menu')
            ->latest()
            ->get();

        $allMenus = Makanan::all();
        $soldToday = [];

        foreach ($activeMenus as $activeMenu) {
            $soldToday[$activeMenu->id] = $activeMenu->getSoldPortionsToday();
        }

        // Check if request expects JSON (from AJAX)
        if (request()->wantsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'data' => $activeMenus
            ]);
        }

        return view('active_menus.index', compact('activeMenus', 'allMenus', 'soldToday', 'unitBisnisId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:makanans,MakananID',
            'is_free' => 'boolean',
            'stock' => 'required|integer|min:0',
            'limit_per_user' => 'required|integer|min:1',
        ]);

        $unitBisnisId = auth()->user()->UnitBisnisID ?? 1;

        // Check if menu already exists for this unit bisnis
        $existing = ActiveMenu::where('menu_id', $validated['menu_id'])
            ->where('unit_bisnis_id', $unitBisnisId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Menu ini sudah ada di daftar menu aktif Anda'
            ], 422);
        }

        $validated['unit_bisnis_id'] = $unitBisnisId;
        $validated['is_free'] = $request->has('is_free') || $request->input('is_free') === '1';

        $activeMenu = ActiveMenu::create($validated);
        $activeMenu->updateStatus();
        $activeMenu->load('menu');

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan',
            'data' => $activeMenu,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActiveMenu $activeMenu)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'limit_per_user' => 'required|integer|min:1',
            'is_free' => 'boolean',
        ]);

        $validated['is_free'] = $request->has('is_free');

        $activeMenu->update($validated);
        $activeMenu->updateStatus();
        $activeMenu->load('menu');

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui',
            'data' => $activeMenu,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActiveMenu $activeMenu)
    {
        $activeMenu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus',
        ]);
    }

    /**
     * Get filtered menus by status
     */
    public function getByStatus(Request $request)
    {
        $status = $request->query('status', 'semua');
        $unitBisnisId = auth()->user()->UnitBisnisID ?? 1;

        $query = ActiveMenu::where('unit_bisnis_id', $unitBisnisId)->with('menu');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $activeMenus = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $activeMenus,
        ]);
    }

    /**
     * Get all available menus for selection
     */
    public function getAvailableMenus()
    {
        $unitBisnisId = auth()->user()->UnitBisnisID ?? 1;
        
        $usedMenuIds = ActiveMenu::where('unit_bisnis_id', $unitBisnisId)
            ->pluck('menu_id')
            ->toArray();

        $availableMenus = Makanan::whereNotIn('MakananID', $usedMenuIds)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableMenus,
        ]);
    }
}

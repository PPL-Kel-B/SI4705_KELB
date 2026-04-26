<?php

namespace App\Http\Controllers;

use App\Models\Unitbisnis;
use App\Http\Requests\RegistUnitBisnisRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegistUnitBisnisController extends Controller
{
    public function create()
    {
        return view('unit_bisnis.create');
    }

    public function store(RegistUnitBisnisRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('NIB_File')) {
            $path = $request->file('NIB_File')->store('nib_files', 'public');
            $validated['NIB_File'] = $path;
        }

        // Hash the password before saving
        $validated['Password'] = Hash::make($validated['Password']);

        Unitbisnis::create($validated);

        return redirect()->route('unit-bisnis.create')->with('success', 'Pendaftaran Unit Bisnis berhasil! Silakan masuk ke dashboard.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UnitBisnis;
use App\Http\Requests\RegisterUnitBisnisRequest;
use Illuminate\Support\Facades\Hash;

class RegisterUnitBisnisController extends Controller
{
    public function index()
    {
        return view('auth.register_unit_bisnis');
    }

    public function store(RegisterUnitBisnisRequest $request)
    {
        // Data sudah tervalidasi oleh RegisterUnitBisnisRequest
        $data = $request->validated();
        $data['Password'] = Hash::make($request->Password);

        // Sesuai ERD: Unit Bisnis butuh AdminID (default/dummy untuk pendaftaran awal)
        $data['AdminID'] = 1;

        UnitBisnis::create($data);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }
}
